<?php

namespace App\Services;

use App\Models\User;

class NewsService
{
    protected $newsSources;

    public function __construct(NewsAPIService $newsAPIService, GuardianService $guardianService, NewYorkTimesService $newYorkTimesService)
    {
        $this->newsSources = [
            $newsAPIService,
            $guardianService,
            $newYorkTimesService,
        ];
    }

    public function getArticles(array $params, User $user): array
    {
        // Set default filters based on user preferences
        $defaultParams = [
            'sources' => $user->preferred_sources,
            'categories' => $user->preferred_categories,
            'authors' => $user->preferred_authors,
        ];

        // Merge user-supplied filters with default filters
        $params = array_merge($defaultParams, $params);
        $allArticles = [];

        foreach ($this->newsSources as $newsSource) {
            $articles = $newsSource->fetchArticles($params);
            $formattedArticles = array_map([$this, 'formatArticle'], $articles);
            $allArticles = array_merge($allArticles, $formattedArticles);
        }

        // Sort and filter the combined articles using Laravel's collection class
        $sortedArticles = collect($allArticles)->sortBy('publishedAt')->values()->all();

        return $sortedArticles;
    }

    protected function formatArticle($article)
    {
        if (isset($article['webTitle'])) { // Guardian article
            return [
                'id' => $article['id'],
                'title' => $article['webTitle'],
                'description' => $article['fields']['trailText'] ?? null, // Use trailText as the description
                'url' => $article['webUrl'],
                'urlToImage' => $article['fields']['thumbnail'] ?? null,
                'publishedAt' => $article['webPublicationDate'] ?? null,
            ];
        } elseif (isset($article['headline'])) { // New York Times article
            $imageUrl = null;
            if (isset($article['multimedia']) && is_array($article['multimedia'])) {
                $image = array_filter($article['multimedia'], function ($media) {
                    return $media['type'] == 'image';
                });

                if (!empty($image)) {
                    $firstImage = array_shift($image);
                    $imageUrl = 'https://www.nytimes.com/' . $firstImage['url'];
                }
            }

            return [
                'id' => $article['_id'],
                'title' => $article['headline']['main'],
                'description' => $article['abstract'],
                'url' => $article['web_url'],
                'urlToImage' => $imageUrl,
                'publishedAt' => $article['pub_date'] ?? null,
            ];
        } else { // NewsAPI article
            return [
                'id' => $article['url'],
                'title' => $article['title'],
                'description' => $article['description'],
                'url' => $article['url'],
                'urlToImage' => $article['urlToImage'],
                'publishedAt' => $article['publishedAt'] ?? null,
            ];
        }
    }

}
