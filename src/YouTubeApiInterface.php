<?php

namespace WishgranterProject\YouTubeProbe;

interface YouTubeApiInterface
{
    /**
     * @param string $query
     *   A query for our search.
     * @param $itemsPerPage
     *   How many results there should be per page.
     * @param string|null $pageToken
     *   The page we are targeting.
     *
     * @return \stdClass|null
     */
    public function search(string $query, int $itemsPerPage = 25, ?string $pageToken = null): ?\stdClass;
}
