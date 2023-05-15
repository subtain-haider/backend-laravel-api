<?php

namespace App\Services;

use GuzzleHttp\Client;

abstract class NewsSource implements NewsSourceInterface
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    abstract public function fetchArticles(array $params): array;
}
