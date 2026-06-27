// app/Http/Controllers/Api/KnowledgeBaseController.php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBase;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KnowledgeBaseController extends Controller
{
    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * List articles
     */
    public function index(Request $request)
    {
        $query = KnowledgeBase::published();

        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        if ($request->has('search')) {
            $query->search($request->search);
        }

        $articles = $query->latest()->paginate(15);

        return response()->json($articles);
    }

    /**
     * Search articles with AI
     */
    public function search(Request $request)
    {
        $validated = $request->validate([
            'query' => 'required|string|min:2',
        ]);

        $results = $this->aiService->searchKnowledgeBase($validated['query']);

        return response()->json($results);
    }

    /**
     * Get single article
     */
    public function show(KnowledgeBase $article)
    {
        $article->incrementViews();

        return response()->json($article);
    }

    /**
     * Mark article as helpful
     */
    public function helpful(KnowledgeBase $article)
    {
        $article->markHelpful();

        return response()->json([
            'message' => 'Article marked as helpful',
            'helpful_count' => $article->helpful_count,
        ]);
    }

    /**
     * Mark article as not helpful
     */
    public function notHelpful(KnowledgeBase $article)
    {
        $article->markNotHelpful();

        return response()->json([
            'message' => 'Article marked as not helpful',
            'not_helpful_count' => $article->not_helpful_count,
        ]);
    }

    /**
     * Get popular articles
     */
    public function popular()
    {
        $articles = KnowledgeBase::published()
            ->popular()
            ->take(10)
            ->get();

        return response()->json($articles);
    }

    /**
     * Store new article (Admin only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'tags' => 'sometimes|array',
            'is_published' => 'sometimes|boolean',
        ]);

        $article = KnowledgeBase::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'tags' => $validated['tags'] ?? [],
            'is_published' => $validated['is_published'] ?? true,
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Article created successfully',
            'article' => $article,
        ], 201);
    }
}