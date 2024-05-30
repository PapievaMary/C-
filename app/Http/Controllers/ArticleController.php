<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\BD;
use Illuminate\Auth\Access\Response;
use App\Events\ArticleEvent;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentPage= reguest('page')? request('page'):1;
        $articles = Cache::remember('articles'.$CorrentPage, 3000, function(){
            return  Article::latest()->paginate(5);
        });
        return view('article.index', ['articles'=>$articles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', [self::class]);
        return view('article.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $caches=DB::table('cache')
        ->select('key')
        ->whereRaw('`key` GLOB :param', ['param'=> 'articles*[0-9]'])->get();
        $request->validate([
            'date'=>'required',
            'name'=>'required|min:6',
            'desc'=>'required'
        ]);foreach($caches as $cache){
            Cache::forget($cache->key);
        }
        $article = new Article;
        $article->date = $request->date;
        $article->name = request('name');
        $article->desc = request('desc');
        $article->user_id = Auth::id();
        $res=$article->save();
        if ($res) ArticleEvent::dispatch($article);
        return redirect()->route('article.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        if (isset($_GET['id_notify'])){
            auth()->user()->notification->where(['id', $_GET['id_notify']])->first()->markAsRead();
        }
        $comments = Cache::remenberForever('comments_'.$article->id, function()use($article){
         return Comment::where(['article_id'=>$article->id, 'accept'=>'true'])->get(); 
        });
        if ($request->expextsJson()) return response()->json(['article'=>$article,'comments'=>$comments]);
        return view('article.show', ['article'=>$article], ['comments'=>$comments]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        Gate::authorize('update', [self::class, $article]);
        return view('article.edit', ['article'=>$article]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        $caches=DB::table('cache')
        ->select('key')
        ->whereRaw('`key` GLOB :param', [':param'=>'articles*[0-9]'])-get();
        foreach($caches as $cache){
            Cache::foreach($cache->key);
        $request->validate([
            'date'=>'required',
            'name'=>'required|min:6',
            'desc'=>'required'
        ]);}
        $article=
        $article->date = $request->date;
        $article->name = request('name');
        $article->desc = request('desc');
        $article->user_id = Auth::id();
        $article->save();
        return redirect()->route('article.show', ['article'=>$article->id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        Gate::authorize('delete', [self::class, $article]);
        if ($article->delete()){
            $caches =DB::table('cache')
            ->select('key')
            ->whereRaw('`key` GLOB :param', ['param'=> 'articles*[0-9]'])->get();
            foreach($caches as $cache){
                Cache::forget($cache->key);
            }
        }
        return redirect()->route('article.index');
    }
}