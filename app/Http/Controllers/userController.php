<?php

namespace App\Http\Controllers;

use App\Helper\JwtToken;
use Exception;
use App\Models\User;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use App\Helper\ResponseHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class userController extends Controller
{
    public function userLogin(Request $request){
        try{
         $UserEmail=$request->userEmail;
            $OTP=rand(100000,999999);
            $details = ['code' => $OTP];
            Mail::to($UserEmail)->send(new OTPMail($details));
            User::updateOrCreate(['email' =>$UserEmail], ['email'=>$UserEmail,'otp'=>$OTP]);
            return ResponseHelper::Out('success',"A 6 Digit OTP has been send to your email address",200);
        }catch(Exception $e){
          // return ResponseHelper::Out("fail",$e,401);
          return response()->json([
            "status"=>"fail",
            "message"=>$e->getMessage()
            
            ]);
        }
    }

    public function userVerify(Request $request){
        $UserEmail = $request->UserEmail;
        $otp = $request->otp;
        $verification = User::where('email',$UserEmail)->where('otp',$otp)->first();
            if($verification){
                User::where('email',$UserEmail)->where('otp',$otp)->update(['otp'=>'0']); 
                $token = JwtToken::createToken($UserEmail,$verification->id);
                return ResponseHelper::Out('success','',200)->cookie('token',$token,60*24*30);
            }else{
                return ResponseHelper::Out('fail','',401);
            }
       
    }

    public function userLogOut(){
        return ResponseHelper::Out('fail','',401)->cookie('token','',-1);
    }
}
