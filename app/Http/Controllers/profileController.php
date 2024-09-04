<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use App\Models\customer_profile;

class profileController extends Controller
{
    public function CreateProfile(Request $request){
        $user_id = $request->header('id');
        $request->merge(['user_id'=>$user_id]);
        $data= customer_profile::updateOrCreate(
            ['user_id'=>$user_id],
            $request->input()
        );
        return ResponseHelper::Out('success',$data,200);
    }

    public function ReadProfile(Request $request){
        $user_id = $request->header('id');
        $data = customer_profile::where('user_id',$user_id)->with('user')->first();
        return ResponseHelper::Out('success',$data,200);
    }
}
