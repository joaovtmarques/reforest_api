<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\GreenPoint;
use App\Models\InfoGreenPoint;

class GreenPointController extends Controller
{
    private $loggedUser;

    public function __construct() {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function addGreenPoint(Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'point_avatar' => 'required',
            'description' => 'required',
            'street' => 'required',
            'district' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'state' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if(!$validator->fails()) {
            $id_user = $this->loggedUser['id'];
            $name = $request->input('name');
            $point_avatar = $request->input('point_avatar');
            $description = $request->input('description');

            $street = $request->input('street');
            $district = $request->input('district');
            $zipcode = $request->input('zipcode');
            $city = $request->input('city');
            $state = $request->input('state');
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');

            $pointExists = InfoGreenPoint::where('zipcode', $zipcode)->count();
            if($pointExists === 0) {

                $newPoint = new GreenPoint();
                $newPoint->id_user = $id_user;
                $newPoint->name = $name;
                $newPoint->point_avatar = $point_avatar;
                $newPoint->description = $description;
                $newPoint->save();

                $newInfoGreenPoint = new InfoGreenPoint();
                $newInfoGreenPoint->id_greenpoint = $newPoint->id;
                $newInfoGreenPoint->street = $street;
                $newInfoGreenPoint->district = $district;
                $newInfoGreenPoint->zipcode = $zipcode;
                $newInfoGreenPoint->city = $city;
                $newInfoGreenPoint->state = $state;
                $newInfoGreenPoint->latitude = $latitude;
                $newInfoGreenPoint->longitude = $longitude;
                $newInfoGreenPoint->save();

            } else {
                $array['error'] = 'Ponto Verde já cadastrado';
                return $array;
            }
        } else {
            $array['error'] = 'Dados incorretos';
            return $array;
        }

        return $array;
    }

    public function list() {
        $array = ['error'=>''];

        $results = GreenPoint::select()
            ->join('infogreenpoints', 'greenpoints.id', '=', 'infogreenpoints.id_greenpoint')
            ->get();

        $array['data'] = $results;

        return $array;
    }

    public function one($id) {
        $array = ['error'=>''];

        $results = GreenPoint::select()
            ->where('greenpoints.id', $id)
            ->join('infogreenpoints', 'greenpoints.id', '=', 'infogreenpoints.id_greenpoint')
            ->get();
            
        $array['data'] = $results;

        return $array;
    }

    public function search(Request $request) {
        $array = ['error'=>'', 'list'=>[]];

        $q = $request->input('q');

        if($q) {
            $points = GreenPoint::select()
                ->where('name', 'LIKE', '%'.$q.'%')
                ->get();

            $array['list'] = $points;
        } else {
            $array['error'] = 'Digite algo para buscar';
            return $array;
        }

        return $array;
    }
}
