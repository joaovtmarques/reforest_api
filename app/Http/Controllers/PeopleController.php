<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Post;
use App\Models\People;
use App\Models\User;

class PeopleController extends Controller
{
    private $loggedUser;

    public function __construct() {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function getPeople(Request $request) {
        $array = ['error'=>''];

        $id_user = $this->loggedUser['id'];

        $id_people = $request->input('id_people');

        $results = People::find($id_people);

        if($results) {
            $peopleInfo = User::select('name', 'bio', 'avatar', 'cover')
                ->where('id', $id_people)
                ->get();
            
            $peoplePosts = Post::select('text', 'image', 'created_at')
                ->where('id_user', $id_people)
                ->get();
            
        }

        $array['data'] = $peopleInfo;
        $array['data'] = [$array['data'], $peoplePosts];

        // dar um jeito de juntar as informações no mesmo array, sem criar um novo array

        return $array;
    }
}
