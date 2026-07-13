<?php

use AdinanCenci\FileCache\Cache;
use WishgranterProject\MusicProbe\Description;
use WishgranterProject\YouTubeProbe\YouTubeApi;
use WishgranterProject\YouTubeProbe\YouTubeProbe;

if (!file_exists('../../vendor/autoload.php')) {
    die('Autoload file not found');
}

require '../../vendor/autoload.php';

//-----------------------------------------------------------------------------

$youtubeApiKey = file_exists('../.youtube-api-key')
    ? file_get_contents('../.youtube-api-key')
    : 'your-youtube-api-key-goes-here';

$youtubeApi = new YouTubeApi($youtubeApiKey);
$youTube    = new YouTubeProbe($youtubeApi);

//-----------------------------------------------------------------------------

$description = Description::createFromArray([
    'title'      => 'Johnny Guitar',
    'artist'     => 'Peggy Lee',
    'soundtrack' => 'Fallout New Vegas',
]);

$results = $youTube->search($description);

foreach ($results as $result) {
    echo "<pre>
    Id: $result->id
    Probe Id: $result->probeId
    SourceId: $result->sourceId
    Title: $result->title
    Description: $result->description
    Thumbnail: $result->thumbnail
    Href: $result->href" .
    '</pre>';
}
