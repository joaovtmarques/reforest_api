<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\GreenCredit;
use App\Models\Mission;
use App\Models\InfoMission;
use App\Models\User;

class MissionController extends Controller
{
    private $loggedUser;

    public function __construct() {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function getMissions() {
        $array = ['error'=>''];

        $id_user = $this->loggedUser['id'];

        $results = InfoMission::select() 
            ->join('missions', 'infomissions.id_mission', '=', 'missions.id')
            ->where('infomissions.id_user', $id_user)
            ->get();

        $array['data'] = $results;

        return $array;
    }

    public function completeMission($id) {
        $array = ['error' => ''];

        $id_user = $this->loggedUser['id'];
        $missionExists = Mission::select()->where('id', $id)->get();

        /**
         * verificar se a missao existe na tabela de missoes
         * se existir a missao, entao verificar na tabela de info missao se ela ja foi criada/feita pelo usuario
         * se nao existir na tabela de info missao, criar a missao la com suas respectivas informações
         * após isso, validar ela como feita/true
         * se a missao ja existir na tabela info missao, verificar se ela é true/false (feita ou nao)
         * se ela ja for true/feita, informar o erro como "missao ja realizada"
         * se ela for false/nao feita, validar como true/feita, adicionar os respectivos cvs e retornar isso ao usuario
         * */ 

        if($missionExists) {
            // $mission = InfoMission::select()->where(['id_user', $id_user, 'id_mission', $id])->get();
            // if($mission) {
            //     $array['data'] = $mission;
            //     return $array;
            // } else {
            //     $array['error'] = "Essa info missão não existe";
            //     return $array;
            // }
            $array['error'] = 'achouu';
            return $array;
        } else {
            $array['error'] = "Essa missão não existe";
            return $array;
        }



        // $mission = InfoMission::select()->where('id_mission', $id)->get();

        // if($mission) {
        //     $id_user = $this->loggedUser['id'];

        //     $mis = InfoMission::select()->where(['id_user' => $id_user, 'id_mission' => $id])->get();
            
        //     if($mis[0]{"complete"} === false) {
        //         InfoMission::where(['id_user' => $id_user, 'id_mission' => $id])
        //             ->update(['complete' => true]);

        //         $award = Mission::select('award_value')->where('id', $id)->get();
        //         $gc = $award[0]{"award_value"};

        //         $newGc = GreenCredit::find($this->loggedUser->id);
        //         $newGc->amountgc = $newGc->amountgc + $gc;
        //         $newGc->save();
            
        //         $completeMission = InfoMission::select()->where(['id_user' => $id_user, 'id_mission' => $id])->get();

        //         $array['data'] = $completeMission;
        //     } else {
        //         $array['error'] = 'Esta missão já está completa';
        //         return $array;
        //     }          
        // } else {
        //     $array['error'] = 'Missão não completa';
        //     return $array;
        // }

        return $array;
    }

    public function addMission(Request $request) {
        $array = ['error'=>''];

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'award_value' => 'required|integer',
            'description' => 'required'
        ]);

        if(!$validator->fails()) {
            $name = $request->input('name');
            $award_value = $request->input('award_value');
            $description = $request->input('description');

            $missionExists = Mission::where('name', $name)->count();
            if($missionExists === 0) {
                $newMission = new Mission();
                $newMission->award_value = $award_value;
                $newMission->name = $name;
                $newMission->description = $description;
                $newMission->save();
            } else {
                $array['error'] = 'Missão já cadastrada';
                return $array;
            }
        } else {
            $array['error'] = 'Insira os dados corretamente';
            return $array;
        }
        return $array;
    }
}

