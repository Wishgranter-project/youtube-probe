<?php

namespace WishgranterProject\YouTubeProbe;

use AdinanCenci\GenericRestApi\ApiBase;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Client\ClientInterface;
use Psr\SimpleCache\CacheInterface;

class YouTubeApi extends ApiBase implements YouTubeApiInterface
{
    /**
     * The YouTube api key.
     *
     * Get one at https://console.cloud.google.com/apis/dashboard
     *
     * @var string
     */
    protected string $apiKey;

    /**
     * @param string $apiKey
     *   The YouTube api key.
     * @param array $options
     *   Implementation specific.
     * @param null|Psr\SimpleCache\CacheInterface $cache
     *   Cache, optional.
     * @param null|Psr\Http\Client\ClientInterface $httpClient
     *   Optional, the class will use a generic library if not informed.
     * @param Psr\Http\Message\RequestFactoryInterface $requestFactory
     *   Optional, the class will use a generic library if not informed.
     */
    public function __construct(
        string $apiKey,
        array $options = [],
        ?CacheInterface $cache = null,
        ?ClientInterface $httpClient = null,
        ?RequestFactoryInterface $requestFactory = null,
    ) {
        $this->apiKey = $apiKey;
        parent::__construct($options, $cache, $httpClient, $requestFactory);
    }

    /**
     * {@inheritdoc}
     */
    public function search(string $query): ?\stdClass
    {
        // maxResults ( itens per page ).
        // pageToken ( page )

        $parameters = [
            'maxResults'      => 25,
            'order'           => 'relevance',
            'safeSearch'      => 'none',
            'type'            => 'video',
            'part'            => 'snippet',
            'videoEmbeddable' => 'true',
            'videoSyndicated' => 'true',
            'q'               => $query,
        ];

        $query = http_build_query($parameters);
        $endPoint = 'search?' . $query;

        $json = $this->getJson($endPoint);

        return $json;
    }

    /**
     * {@inheritdoc}
     */
    protected function getFullUrl(string $uri): string
    {
        return 'https://youtube.googleapis.com/youtube/v3/' . $uri . '&key=' . $this->apiKey;
    }
}
