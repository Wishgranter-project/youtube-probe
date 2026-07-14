# YouTube probe

A library to find music in on YouTube.

Implementing the [wishgranter-project/music-probe](https://github.com/Wishgranter-project/music-probe/) interface.

<br><br>

## How to use ot

First we instantiate the probe.

```php
use WishgranterProject\YouTubeProbe\YouTubeApi;
use WishgranterProject\YoutubeProbe\YouTubeProbe;

$api = new YouTubeApi('your-api-key-goes-here');
$probe = new YouTubeProbe($api);
```

<br><br>

## Describe what we want

```php
use WishgranterProject\MusicProbe\Description;

// Title and artist, simple.
$description = Description::createFromArray([
    'title'  => 'Stolen Waters',
    'artist' => 'Cain\'s Offering',
]);
```

<br><br>

## Search

Then we search for it.

```php
$resources = $probe->search($description);
foreach ($resources as $r) {
    echo "$r->title $r->thumbnail $r->href ...";
}
```

See the examples directory for more details.

<br><br>

## License

MIT
