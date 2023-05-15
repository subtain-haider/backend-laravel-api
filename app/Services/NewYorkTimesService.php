<?php

namespace App\Services;

use GuzzleHttp\Exception\GuzzleException;

class NewYorkTimesService extends NewsSource
{
    protected $apiKey;

    public function __construct()
    {
        parent::__construct();
        $this->apiKey = env('NEW_YORK_TIMES_API_KEY');
    }

    public function fetchArticles(array $params): array
    {
        $apiParams = [
            'api-key' => $this->apiKey,
            'q' => $params['searchTerm'] ?? null,
            'begin_date' => isset($params['fromDate']) ? date('Ymd', strtotime($params['fromDate'])) : null,
            'end_date' => isset($params['toDate']) ? date('Ymd', strtotime($params['toDate'])) : null,
            'section' => $params['category'] ?? null,
        ];

        try {
            $response = $this->client->request('GET', 'https://api.nytimes.com/svc/search/v2/articlesearch.json', [
                'query' => array_filter($apiParams),
            ]);

            $data = json_decode($response->getBody(), true);

            return $data['response']['docs'] ?? [];
        } catch (GuzzleException $e) {
            // Log the error and return an empty array
            \Log::error($e->getMessage());
            return [];
        }
    }
}
