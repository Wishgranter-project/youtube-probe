<?php

namespace WishgranterProject\YoutubeProbe;

use WishgranterProject\MusicProbe\DescriptionInterface;
use WishgranterProject\MusicProbe\Resource;
use WishgranterProject\MusicProbe\ProbeAbstract;
use WishgranterProject\MusicProbe\ProbeInterface;

class YouTubeProbe extends ProbeAbstract implements ProbeInterface
{
    /**
     * Constructor.
     *
     * @param WishgranterProject\YouTubeProbe\YouTubeApi $youTubeApi
     */
    public function __construct(protected YouTubeApi $youTubeApi)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): string
    {
        return 'youtube';
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceId(): string
    {
        return 'youtube';
    }

    /**
     * {@inheritdoc}
     */
    public function buildQuery(DescriptionInterface $description): string
    {
        $parts = [];

        if (isset($description->title)) {
            $parts[] = $description->title;
        }

        // It is more likely for the music to be known for the soundtrack than
        // the artist, so we give it precedence.
        if (!empty($description->soundtrack)) {
            $parts[] = $description->soundtrack[0];
        } elseif (!empty($description->artist)) {
            $parts[] = $description->artist[0];
        }

        if ($description->genre) {
            // ignores genre.
        }

        $query = implode(' ', $parts);

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function search(DescriptionInterface $description): array
    {
        $resources = [];

        $query = $this->buildQuery($description);
        $data  = $this->youTubeApi->search($query);

        foreach ($data->items as $item) {
            if ($item->id->kind != 'youtube#video') {
                continue;
            }

            $resources[] = Resource::createFromArray([
                'probeId'     => $this->getId(),
                'sourceId'    => $this->getSourceId(),
                'id'          => $item->id->videoId,
                'title'       => htmlspecialchars_decode($item->snippet->title),
                'description' => htmlspecialchars_decode($item->snippet->description),
                'thumbnail'   => $item->snippet->thumbnails->default->url,
                'href'        => 'https://youtube.com/watch?v=' . $item->id->videoId,
            ]);
        }

        return $resources;
    }
}
