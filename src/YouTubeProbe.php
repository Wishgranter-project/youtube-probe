<?php

namespace WishgranterProject\YouTubeProbe;

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
        /**
         * See https://developers.google.com/youtube/v3/docs/search/list .
         *
         * In my experience, the boolean operator ( -this -that ) only makes
         * the results poorer, so it is best avoided.
         */

        $parts = [];

        if (isset($description->title)) {
            $parts[] = '"' . $description->title . '"';
        }

        if (!empty($description->soundtrack)) {
            $parts[] = implode(' ', $description->soundtrack);
        }

        if (!empty($description->artist)) {
            $parts[] = implode(' ', $description->artist);
        }

        if (!empty($description->featuring)) {
            $parts[] = implode(' ', $description->featuring);
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

        if (!$data || empty($data->items)) {
            return [];
        }

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
