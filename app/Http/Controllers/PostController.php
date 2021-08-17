<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;

class PostController extends Controller
{
    private $loggedUser;

    public function __construct() {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function getComments($id) {
        $array = ['error' => ''];

        $post = Post::find($id);

        if($post) {
            $comment = Comment::all()->where('id_post', $id);
            $array['data'] = $comment;
        } else {
            $array['error'] = 'Comentários não encontrados';
            return $array;
        }

        return $array;
    }

    public function addComment(Request $request, $id) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'comment' => 'required',
        ]);

        if(!$validator->fails()) {
            $id_user = $this->loggedUser['id'];
            $post = Post::find($id);
            $comment = $request->input('comment');

            $newComment = new Comment();
            $newComment->id_user = $id_user;
            $newComment->id_post = $id;
            $newComment->comment = $comment;
            $newComment->save();

            $array['data'] = $newComment;
        } else {
            $array['error'] = 'Preencha corretamente os campos';
            return $array;
        }

        return $array;
    }

    public function getAllPosts() {
        $array = ['error'=>''];

        $results = Post::all();

        $array['data'] = $results;

        return $array;
    }

    public function getOnePost($id) {
        $array = ['error'=>''];

        $results = Post::find($id);
            
        $array['data'] = $results;

        return $array;
    }

    public function toggleLike(Request $request) {
        $array = ['error'=>''];

        $id_post = $request->input('post');

        $post = Post::find($id_post);

        if($post) {
            $like = Like::select()
                ->where('id_user', $this->loggedUser->id)
                ->where('id_post', $id_post)
                ->first();

                if($like) {
                    $like->delete();
                    $array['have'] = false;
                } else {
                    $newLike = new Like();
                    $newLike->id_user = $this->loggedUser->id;
                    $newLike->id_post = $id_post;
                    $newLike->save();
                    $array['have'] = true;
                }
        } else {
            $array['error'] = 'Publicação inexistente';
        }

        return $array;
    }

    public function getLikes($id) {
        $array = ['error'=>'', 'list'=>[]];

        $likes = Like::select()
            ->where('id_post', $id)
            ->count('id_post');
        
        if($likes) {
            $array['list'][] = $likes;
        } else {
            $array['error'] = 'Likes não encontrados';
        }

        return $array;
    }
}
