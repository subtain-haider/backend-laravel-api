<?php

namespace App\Services;

interface NewsSourceInterface
{
    public function fetchArticles(array $params): array;
}
