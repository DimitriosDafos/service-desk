<?php

namespace App\Http\Controllers\KnowledgeBase;

use App\Http\Controllers\Controller;
use App\KnowledgeBase\Models\Article;
use App\KnowledgeBase\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $tenantId = auth()->user()->tenant_id;
        
        $query = Article::where('tenant_id', $tenantId);

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', Article::STATUS_PUBLISHED);
        }

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $query->with('category', 'author')->paginate(20);
        $categories = ArticleCategory::where('tenant_id', $tenantId)->get();

        return view('knowledge-base.articles.index', compact('articles', 'categories'));
    }

    public function create()
    {
        $tenantId = auth()->user()->tenant_id;
        $categories = ArticleCategory::where('tenant_id', $tenantId)->get();
        return view('knowledge-base.articles.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|uuid|exists:article_categories,id',
            'status' => 'required|in:draft,published,archived',
            'excerpt' => 'nullable|string',
        ]);

        $validated['tenant_id'] = auth()->user()->tenant_id;
        $validated['slug'] = Str::slug($validated['title']);
        $validated['author_id'] = auth()->user()->id;

        if ($validated['status'] === Article::STATUS_PUBLISHED) {
            $validated['published_at'] = now();
        }

        $article = Article::create($validated);

        return redirect()->route('knowledge-base.articles.show', $article)->with('success', 'Article created successfully');
    }

    public function show(Article $article)
    {
        $this->authorizeArticle($article);
        $article->load('category', 'author', 'versions');
        return view('knowledge-base.articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        $this->authorizeArticle($article);
        $tenantId = auth()->user()->tenant_id;
        $categories = ArticleCategory::where('tenant_id', $tenantId)->get();
        return view('knowledge-base.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        $this->authorizeArticle($article);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'nullable|uuid|exists:article_categories,id',
            'status' => 'required|in:draft,published,archived',
            'excerpt' => 'nullable|string',
        ]);

        if ($validated['title'] !== $article->title) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $article->update($validated);

        return redirect()->route('knowledge-base.articles.show', $article)->with('success', 'Article updated successfully');
    }

    public function destroy(Article $article)
    {
        $this->authorizeArticle($article);
        $article->delete();
        return redirect()->route('knowledge-base.articles.index')->with('success', 'Article deleted successfully');
    }

    protected function authorizeArticle($article)
    {
        $user = auth()->user();
        
        if ($user->isSystemLevel()) {
            return;
        }

        if ($article->tenant_id !== $user->tenant_id) {
            abort(403);
        }
    }
}
