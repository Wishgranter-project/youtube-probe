# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).


## [1.0.2] - 2026-11-11

Just some small improvements.

### Fixed
- Fixed typo in the namespace.
- Added some type checks.
- Defaults to the medium quality thumbnails.
- Use interfaces.
- Small improvements to the query string used in the requests to the YouTube api.
- Like the removal of quotes from the title.
- And specifying a video category.
- Stop tracking `composer.lock`.
- Improved documentation.

### Added
- strict_types
- Unit tests.

---

## [1.0.1] - 2026-09-22

### Fixed
- Slight improvement in the query submitted to YouTube's API. It now includes
all the performing artists + the featured artists as well.

---

## [1.0.0] - 2026-07-20

First release. Dismembered from the original library [Wishgranter-project/aether-music](https://github.com/Wishgranter-project/aether-music).
