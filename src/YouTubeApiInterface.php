<?php

namespace WishgranterProject\YouTubeProbe;

interface YouTubeApiInterface
{
    /**
     * @param string $query
     *   A query for our search.
     *
     * @return \stdClass|null
     */
    public function search(string $query): ?\stdClass;
}
