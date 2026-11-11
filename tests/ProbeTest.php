<?php

declare(strict_types=1);

namespace WishgranterProject\YouTubeProbe\Tests;

use PHPUnit\Framework\TestCase;
use WishgranterProject\MusicProbe\Description;
use WishgranterProject\MusicProbe\ResourceInterface;
use WishgranterProject\YouTubeProbe\YouTubeProbe;

class ProbeTest extends TestCase
{
    public function testQueryBuilding()
    {
        $api   = new DummyYouTubeApi();
        $probe = new YouTubeProbe($api);

        $description = Description::createFromArray([
            'title'      => $title = 'Title of the music',
            'artist'     => $artist = 'Performing artist',
            'featuring'  => $featuring = 'Guest artist',
            'genre'      => $genre = 'Musical Genre',
            'soundtrack' => $soundtrack = 'Intelectual property featuring the music',
            'cover'      => $cover = 'Original author',
        ]);

        $query = $probe->buildQuery($description);

        $this->assertStringContainsString($title, $query);
        $this->assertStringContainsString($artist, $query);
        $this->assertStringContainsString($featuring, $query);
        $this->assertStringContainsString($soundtrack, $query);
        $this->assertStringNotContainsString($genre, $query);
        $this->assertStringNotContainsString($cover, $query);
    }

    public function testSearchResults()
    {
        $api   = new DummyYouTubeApi();
        $probe = new YouTubeProbe($api);

        $description = Description::createFromArray([
            'title'      => 'Title of the music',
            'artist'     => 'Performing artist',
            'featuring'  => 'Guest artist',
            'genre'      => 'Musical Genre',
            'soundtrack' => 'Intelectual property featuring the music',
            'cover'      => 'Original author',
        ]);

        $api->setDataToReturn(json_decode('{
            "items": [
                {
                    "id": {
                        "kind": "youtube#video",
                        "videoId": "videoId"
                    },
                    "snippet": {
                        "title": "Video title",
                        "description": "Video description",
                        "thumbnails": {
                            "medium": {
                                "url": "https://youtube/medium/video.jpg"
                            },
                            "default": {
                                "url": "https://youtube/default/video.jpg"
                            }
                        }
                    }
                }
            ]
        }'));

        $results = $probe->search($description);
        $first = reset($results);

        $this->assertInstanceOf(ResourceInterface::class, $first);
        $this->assertEquals('videoId', $first->id);
        $this->assertEquals('Video title', $first->title);
        $this->assertEquals('Video description', $first->description);
        $this->assertEquals('https://youtube/medium/video.jpg', $first->thumbnail);
        $this->assertEquals('https://youtube.com/watch?v=videoId', $first->href);

        //------------------

        $api->setDataToReturn(json_decode('{
            "items": [
                {
                    "id": {
                        "kind": "youtube#video",
                        "videoId": "videoId"
                    },
                    "snippet": {
                        "title": "Video title",
                        "description": "Video description",
                        "thumbnails": {
                            "default": {
                                "url": "https://youtube/default/video.jpg"
                            }
                        }
                    }
                }
            ]
        }'));

        $results = $probe->search($description);
        $first = reset($results);

        $this->assertEquals('https://youtube/default/video.jpg', $first->thumbnail);
    }
}
