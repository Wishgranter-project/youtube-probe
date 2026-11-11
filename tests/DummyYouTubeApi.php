<?php

declare(strict_types=1);

namespace WishgranterProject\YouTubeProbe\Tests;

use WishgranterProject\YouTubeProbe\YouTubeApiInterface;

/**
 * Dummy API object for test purposes.
 */
class DummyYouTubeApi implements YouTubeApiInterface
{
    /**
     * Data to return.
     */
    protected \stdClass $dataToReturn;

    /**
     * {@inheritdoc}
     */
    public function search(string $query, int $itemsPerPage = 25, ?string $pageToken = null): ?\stdClass
    {
        return $this->dataToReturn;
    }

    /**
     * Set the data to return during tests.
     *
     * @param \stdClass $data
     *   The data.
     */
    public function setDataToReturn(\stdClass $data)
    {
        $this->dataToReturn = $data;
    }
}
