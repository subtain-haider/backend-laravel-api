<?php

namespace App\Http\Controllers;

use App\Services\NewsService;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function index(Request $request)
    {
        $params = $request->only(['searchTerm', 'fromDate', 'toDate', 'category', 'source']);
        $articles = $this->newsService->getArticles($params, $request->user());

        return response()->json($articles);
    }
}
