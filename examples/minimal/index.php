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

$resources = $youTube->search($description);

foreach ($resources as $resource) {
    echo "<pre>
    Id: $resource->id
    Probe Id: $resource->probeId
    SourceId: $resource->sourceId
    Title: $resource->title
    Description: $resource->description
    Thumbnail: $resource->thumbnail
    Href: $resource->href" .
    '</pre>';
}
