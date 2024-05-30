<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;///
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\VeryLongJob;
use App\Notifications\CommentNotify;////

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = DB::table('comments')
                    ->join('users', 'users.id', '=', 'comments.user_id')
                    ->join('articles', 'articles.id', '=', 'comments.article_id')
                    ->select('comments.*', 'users.name', 
                    'articles.name as article_name', 'articles.id as article_id')
                    ->get();

        return view('comment.index', ['comments'=> $comments]);
    }

    public function accept(Comment $comment)
    {
        $users=User::where('id', '!=',$comment->user_id)->get();
        Log::alert($users);
        $comment->accept = 'true';
        $res= $comment->save();
        if ($res) {
            Notificatiom::send($useer, new CommentNotify($comment->title, $comment->$article_id));
            Cache::forget('comment');
            $caches =DB::table('cache')
            ->select('key')
            ->whereRaw('`key` GLOB :param', ['param'=> 'articles*[0-9]'])->get();
            foreach($caches as $cache){
                Cache::forget($cache->key);
            }

        }
        return redirect()->route('comment.index');
    }
   
    public function reject(Comment $comment)
    {
        $comment->accept = 'false';
        if( $comment->save()){
            Cache::flush();
        }
    
        return redirect()->route('comment.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'text'=>'required'
        ]);

        $article = Article::findOrFail(request('article_id'));
        $comment = new Comment;
        $comment->title = request('title');
        $comment->text = request('text');
        $comment->user_id = Auth::id();
        $comment->article_id = request('article_id');
        $res= $comment->save();

        if($res) {
            VeryLongJob::dispatch($article);
            ///
        }
        return redirect()-> route('article.show',['article'=>request('article_id')])->with(['res'=>$res]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        return view('comment.edit',['comment'=>$comment]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'title'=>'required',
            'text'=>'required'
        ]);
        $comment->title = request('title');
        $comment->text = request('text');
        $comment->user_id = Auth::id();
        $res= $comment->save();
        return redirect()->route('article.show',['article'=>$comment->article_id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('article.show',['article'=>$comment->article_id]);
    }
}
