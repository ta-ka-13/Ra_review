<?php

namespace App\Http\Controllers;

// 追加
use App\Article;

// 追加
use App\Http\Requests\ArticleRequest;

use Illuminate\Http\Request;

class ArticleController extends Controller
{

    // ポリシー使用
    public function __construct()
    {
        $this->authorizeResource(Article::class, 'article');
    }

    public function index()
    {
    // 追加
    $articles = Article::all()->sortByDesc('created_at');

    return view('articles.index', ['articles' => $articles]);
    }

    // createアクション
    public function create()
    {
        return view('articles.create');    
    }

    // storesアクション
    public function store(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all());
        $article->user_id = $request->user()->id;
        $article->save();
        return redirect()->route('articles.index');
    }

    // editアクション
    // $articleにはArticleモデルのインスタンスが代入された状態
    public function edit(Article $article)
    {
        // $articleという変数にArticleモデルのインスタンスが代入された状態
        return view('articles.edit', ['article' => $article]);
    }

    // updateアクション
    public function update(ArticleRequest $request, Article $article)
    {
        // user_idは更新しないので$article->user_id = $request->user()->idは行わない事に注意
        $article->fill($request->all())->save();
        return redirect()->route('articles.index');
    }

    // destroyアクション
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index');
    }


    public function show(Article $article)
    {
        return view('articles.show', ['article' => $article]);
    }

    // likeアクション
    public function like(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);
        $article->likes()->attach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }

    // unlikeアクション
    public function unlike(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }

}


