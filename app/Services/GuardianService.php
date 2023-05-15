<?php

namespace App\Services;

use GuzzleHttp\Exception\GuzzleException;

class GuardianService extends NewsSource
{
    protected $apiKey;

    public function __construct()
    {
        parent::__construct();
        $this->apiKey = env('GUARDIAN_API_KEY');
    }

    public function fetchArticles(array $params): array
    {
        $apiParams = [
            'api-key' => $this->apiKey,
            'q' => $params['searchTerm'] ?? null,
            'from-date' => $params['fromDate'] ?? null,
            'to-date' => $params['toDate'] ?? null,
            'section' => $params['category'] ?? null,
            'show-fields' => 'thumbnail,trailText',
        ];

        try {
            $response = $this->client->request('GET', 'https://content.guardianapis.com/search', [
                'query' => array_filter($apiParams),
            ]);

            $data = json_decode($response->getBody(), true);

            return $data['response']['results'] ?? [];
        } catch (GuzzleException $e) {
            // Log the error and return an empty array
            \Log::error($e->getMessage());
            return [];
        }
    }

}
