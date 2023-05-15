<?php

namespace App\Services;

use GuzzleHttp\Exception\GuzzleException;

class NewsAPIService extends NewsSource
{
    protected $apiKey;

    public function __construct()
    {
        parent::__construct();
        $this->apiKey = env('NEWS_API_KEY');
    }

    public function fetchArticles(array $params): array
    {
        $apiParams = [
            'apiKey' => $this->apiKey,
            'q' => $params['searchTerm'] ?? null,
            'from' => $params['fromDate'] ?? null,
            'to' => $params['toDate'] ?? null,
            'category' => $params['category'] ?? null,
            'country' => $params['country'] ?? 'us',
        ];

        try {
            $response = $this->client->request('GET', 'https://newsapi.org/v2/top-headlines', [
                'query' => array_filter($apiParams),
            ]);

            $articles = json_decode($response->getBody(), true);

            return $articles['articles'] ?? [];
        } catch (GuzzleException $e) {
            // Log the error and return an empty array
            \Log::error($e->getMessage());
            return [];
        }
    }

}
