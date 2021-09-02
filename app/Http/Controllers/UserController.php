<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Post;
use App\Models\GreenCredit;
use App\Models\GreenPoint;
use App\Models\Exchange;
use App\Models\Favorite;
use App\Models\User;

class UserController extends Controller
{
    private $loggedUser;

    public function __construct() {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function read() {
        $array = ['error'=>''];

        $info = $this->loggedUser;
        $info['avatar'] = url('media/avatars/'.$info['avatar']);
        $array['data'] = $info;

        return $array;
    }

    public function addPost(Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'text' => 'required:null',
            'image' => 'required:null',
        ]);

        if(!$validator->fails()) {
            $id_user = $this->loggedUser['id'];
            $text = $request->input('text');
            $image = $request->input('image');

            $newPost = new Post();
            $newPost->id_user = $id_user;
            $newPost->text = $text;
            $newPost->image = $image;
            $newPost->save();

            $array['data'] = $newPost;
        } else {
            $array['error'] = 'Preencha corretamente os campos';
            return $array;
        }

        return $array;
    }

    public function getPosts() {
        $array = ['error'=>''];

        $id_user = $this->loggedUser['id'];

        $results = Post::select()->where('posts.id_user', $id_user)->get();

        $array['data'] = $results;

        return $array;
    }

    public function getGreenCredit() {
        $array = ['error'=>''];

        $id_user = $this->loggedUser['id'];

        $greencredit = GreenCredit::find($id_user);

        $array['data'] = $greencredit['amountgc'];

        return $array;
    }

    public function getExchanges() {
        $array = ['error'=>''];

        $id_user = $this->loggedUser['id'];

        $results = Exchange::select('award_name', 'award_avatar', 'description')
        ->where('exchanges.id_user', $id_user)
        ->join('awards', 'awards.id', '=', 'exchanges.id_award')
        ->get();
        
        $array['data'] = $results;

        return $array;
    }

    public function toggleFavorite(Request $request) {
        $array = ['error'=>''];

        $id_greenPoint = $request->input('point');

        $greenPoint = GreenPoint::find($id_greenPoint);

        if($greenPoint) {
            $fav = Favorite::select()
                ->where('id_user', $this->loggedUser->id)
                ->where('id_greenpoint', $id_greenPoint)
                ->first();

                if($fav) {
                    $fav->delete();
                    $array['have'] = false;
                } else {
                    $newFav = new Favorite();
                    $newFav->id_user = $this->loggedUser->id;
                    $newFav->id_greenpoint = $id_greenPoint;
                    $newFav->save();
                    $array['have'] = true;
                }
        } else {
            $array['error'] = 'Ponto Verde inexistente';
        }

        return $array;
    }

    public function getFavorites() {
        $array = ['error'=>'', 'list'=>[]];

        $favs = Favorite::select()
            ->where('id_user', $this->loggedUser->id)
            ->get();
        
        if($favs) {
            foreach($favs as $fav) {
                $point = GreenPoint::find($fav['id_greenpoint']);
                $array['list'][] = $point;
            }
        }

        return $array;
    }

    public function update(Request $request) {
        $array = ['error'=>''];

        $rules = [
            'name' => 'min:2',
            'email' => 'email|unique:users',
            'password' => 'same:password_confirm',
            'password_confirm' => 'same:password',
            'bio' => 'required:null',
            'avatar' => 'required:null',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            $array['error'] = $validator->messages();
            return $array;
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $password_confirm = $request->input('password_confirm');
        $bio = $request->input('bio');
        $avatar = $request->input('avatar');

        $user = User::find($this->loggedUser->id);

        if($name) {
            $user->name = $name;
        }
        if($email) {
            $user->email = $email;
        }
        if($password) {
            $user->password = password_hash($password, PASSWORD_DEFAULT);
        }
        if($password) {
            $user->password = password_hash($password, PASSWORD_DEFAULT);
        }
        if($bio) {
            $user->bio = $bio;
        }
        if($avatar) {
            $user->avatar = $avatar;
        }

        $user->save();

        return $array;
    }
}
