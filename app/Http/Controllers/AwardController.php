<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Award;
use App\Models\GreenCredit;
use App\Models\Information;
use App\Models\Exchange;
use App\Models\User;

class AwardController extends Controller
{
    private $loggedUser;

    public function __construct() {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function addAwards(Request $request) {
        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'avatar' => 'required',
            'description' => 'required',
            'costcv' => 'required'
        ]);

        if(!$validator->fails()) {
            $id_user = $this->loggedUser['id'];
            $name = $request->input('name');
            $avatar = $request->input('avatar');
            $description = $request->input('description');
            $costcv = $request->input('costcv');

            $awardExists = Award::where('award_name', $name)->count();
            if($awardExists === 0) {
                $newAward = new Award();
                $newAward->id_user = $id_user;
                $newAward->award_name = $name;
                $newAward->award_avatar = $avatar;
                $newAward->description = $description;
                $newAward->costcv = $costcv;
                $newAward->save();
            } else {
                $array['error'] = 'Prêmio já cadastrado';
                return $array;
            }

            $array['data'] = $newAward;
        } else {
            $array['error'] = 'Preencha corretamente os campos';
            return $array;
        }

        return $array;
    }

    public function getAwards() {
        $array = ['error'=>''];

        $results = Award::all();

        $array['data'] = $results;

        return $array;
    }

    public function getAward($id) {
        $array = ['error'=>''];

        $results = Award::find($id);
            
        $array['data'] = $results;

        return $array;
    }

    public function exchangeAward(Request $request, $id) {
        // diminuir os créditos verdes do usuario
        // cadastrar o endereço

        $array = ['error' => ''];

        $validator = Validator::make($request->all(), [
            'street' => 'required',
            'district' => 'required',
            'zipcode' => 'required',
            'city' => 'required',
            'state' => 'required',
        ]);

        if(!$validator->fails()) {
            $id_user = $this->loggedUser['id'];

            $street = $request->input('street');
            $district = $request->input('district');
            $zipcode = $request->input('zipcode');
            $city = $request->input('city');
            $state = $request->input('state');

            $greenCredits = GreenCredit::find($id_user);
            $awardCost = Award::find($id);
            
            if($greenCredits['amountgc'] >= $awardCost['costcv']) {

                $newExchange = new Exchange();
                $newExchange->id_user = $id_user;
                $newExchange->id_award = $id;
                $newExchange->save();

                $infoExists = Information::find($id_user);
                if(!$infoExists) {
                    $newInformation = new Information();
                    $newInformation->id_user = $id_user;
                    $newInformation->street = $street;
                    $newInformation->district = $district;
                    $newInformation->zipcode = $zipcode;
                    $newInformation->city = $city;
                    $newInformation->state = $state;
                    $newInformation->save();
                } else {
                    $array['error'] = 'Endereço já cadastrado';
                    return $array;
                }
                
                $updatedGreenCredits = ($greenCredits['amountgc'] - $awardCost['costcv']);
                GreenCredit::where('id_user', $id_user)
                    ->update(['amountgc' => $updatedGreenCredits]);
            } else {
                $array['error'] = 'Créditos Verde Insuficientes';
                return $array;
            }
        } else {
            $array['error'] = 'Dados incorretos';
            return $array;
        }

        return $array;
    }
}
