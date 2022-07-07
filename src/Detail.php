<?php

namespace GingDev\Xvideos;

use Goutte\Client;
use Peast\Peast;
use Peast\Syntax\Node\Literal;

class Detail
{
    public const BASE_URL = 'https://www.xv-videos1.com';

    private Client $client;

    public function __construct(Client $client = null)
    {
        $this->client = $client ?: new Client();
    }

    /**
     * @return array{
     *  title: string,
     *  low: string,
     *  high: string,
     *  thumb: string
     * }
     */
    public function get(string $id)
    {
        $crawler = $this->client->request('GET', sprintf('%s/video%s/_', self::BASE_URL, $id));

        $jsContent = $crawler->filter('script')->eq(8)->text();

        if (!str_starts_with($jsContent, 'logged_user')) {
            throw new \RuntimeException('Video is not available');
        }

        $ast = Peast::latest($jsContent)->parse();

        [$title,,$low, $high,,$thumb] = array_map(function (Literal $node): string {
            return $node->getValue();
        }, (array) $ast->query('CallExpression > Literal')->getIterator());

        $title = html_entity_decode($title, ENT_QUOTES, 'UTF-8');

        return compact('title', 'low', 'high', 'thumb');
    }
}
