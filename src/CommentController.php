<?php

namespace Laravelista\Comments;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Spatie\Honeypot\ProtectAgainstSpam;
use Laravelista\Comments\Comment;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class CommentController extends Controller implements CommentControllerInterface
{
    public function __construct()
    {
        $this->middleware('web');
        
        if (Config::get('comments.guest_commenting') == true) {
            $this->middleware('auth')->except('store');
            $this->middleware(ProtectAgainstSpam::class)->only('store');
        } else {
            $this->middleware('auth');
        }
    }
    public function store(Request $request)
    {
        $comment =  app(CommentService::class)->store($request);
        return Redirect::to(URL::previous() . '#comment-' . $comment->getKey());
    }

    public function update(Request $request, Comment $comment)
    {
        $comment =  app(CommentService::class)->update($request, $comment);
        return Redirect::to(URL::previous() . '#comment-' . $comment->getKey());
    }

    public function destroy(Comment $comment)
    {
        app(CommentService::class)->destroy($comment);
        return Redirect::back();
    }

    public function reply(Request $request, Comment $comment)
    {
        $reply =  app(CommentService::class)->reply($request, $comment);
         return Redirect::to(URL::previous() . '#comment-' . $reply->getKey());
    }
}
