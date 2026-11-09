# Todo

Prioritised list of known issues and improvements. `P0` = correctness or
security, `P1` = robustness/design, `P2` = packaging/housekeeping.

## P0 - Correctness and security

- [X] **Fix the namespace mismatch.** `src/YouTubeProbe.php` declares
      `WishgranterProject\YoutubeProbe` (lowercase `t`), while `composer.json`
      and `src/YouTubeApi.php` use `WishgranterProject\YouTubeProbe` (capital
      `T`). The lowercase FQCN does not autoload on its own; it only works
      because examples use the capital-`T` name and PHP class names are
      case-insensitive once loaded. Standardise on `YouTubeProbe` everywhere.
- [X] **Guard against empty/failed API responses.** `YouTubeProbe::search()`
      (`src/YouTubeProbe.php:82`) iterates `$data->items` without checking it,
      but `YouTubeApi::search()` can return `null`. Return `[]` early.
- [ ] **Handle pagination.** Only the first page (25 results) is ever returned.
      Expose `nextPageToken` support or document the limitation.
- [X] **Harden `buildQuery()`** (`src/YouTubeProbe.php:51-65`):
  - [X] Do not emit `""` when the title is empty.
  - [X] Escape or strip embedded quotes in the title (currently
        `He said "hi"` produces an invalid-looking query).
  - [X] Decide whether to include `album`, `genre`, and `cover`; they are
        currently ignored, and `album`/`cover` are strong disambiguators.
- [ ] **Remove the XSS footgun.** `htmlspecialchars_decode()` on title and
      description (`src/YouTubeProbe.php:91-92`) undoes escaping, and the
      examples then echo results raw. Either keep entities in the library or
      document that consumers must escape output.
- [X] **Fix the `with-cache` example fields.** It reads `$result->source`
      (`examples/with-cache/index.php:132`); the property is `sourceId`.
      It also prints `$result->src`, which is always empty for YouTube.

## P1 - Robustness and design

- [X] **Null-safe result mapping.** Guard `$item->id->videoId` and
      `$item->snippet->thumbnails->default->url` (`src/YouTubeProbe.php:90-93`).
      Prefer `medium`/`high` thumbnails with a `default` fallback.
- [X] **Depend on an abstraction.** `YouTubeProbe` type-hints the concrete
      `YouTubeApi`, which makes isolated unit tests awkward. Extract a small
      search-client interface.
- [X] **Fix API-key URL construction.** `YouTubeApi::getFullUrl()` hardcodes
      `&key=` (`src/YouTubeApi.php:79`); it only works because `search()`
      always includes a query string. Build `?`/`&` correctly.
- [X] **Improve music-specific API parameters.** Add `videoCategoryId=10`
      (Music), optionally `contentDetails`/duration filtering, region/language,
      and make `maxResults` and `safeSearch` configurable rather than hardcoded
      (`src/YouTubeApi.php:55-64`).
- [ ] **Stop returning raw `\stdClass`.** Map the API response to a small DTO or
      at least document the expected shape in the `search()` docblock.
- [ ] **Document error behaviour.** Note `@throws` for quota/rate-limit errors
      and consider retry/backoff for 429 responses.
- [ ] **Add `declare(strict_types=1);`** to `src/` files for consistency with
      the sibling `music-probe` package.

## P2 - Packaging and hygiene

- [X] **Update AGENTS.md** after fixing the namespace; its current note calls
      the README casing a typo, but the actual source is the inconsistent part.
- [ ] **Tighten `composer.json`**:
  - [ ] Add a `"php"` platform requirement (target 8.4).
  - [ ] Declare directly used PSR packages (`psr/http-client`,
        `psr/http-factory`, `psr/simple-cache`) instead of relying on
        transitive dependencies.
  - [ ] Add `require-dev` with PHPUnit and a `unit-tests` script, matching the
        sibling Wishgranter projects.
- [ ] **Write unit tests.**
  - [ ] `buildQuery()`: empty title, quotes, artist/soundtrack/featuring,
        album/genre/cover behaviour.
  - [ ] `search()`: inject a stub PSR-18 client into `YouTubeApi` and assert
        result mapping, filtering of non-video entries, and empty responses.
- [X] **Consider renaming `CHANGESLOG.md` to `CHANGELOG.md`** and fixing the
      heading levels (`## Fixed` should be `### Fixed`); fix the "Dismembered"
      typo.
- [ ] **Drop the unused MariaDB container** from `.ddev/config.yaml`; the
      library never touches a database.
- [X] **Review whether `composer.lock` should be committed** (debatable for a
      library).
- [X] **Fix README typos** ("find music in on YouTube") and align the class
      casing shown in the snippets.
