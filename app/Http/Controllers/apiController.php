<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transferdetail;
use App\Models\User;
use App\Models\sendcode;
use App\Jobs\SendBalance;
use App\Jobs\delsendcode;
use Illuminate\Http\Request;
use App\Mail\VerifyEmail;
use DB;
use Illuminate\Support\Facades\Mail;

class apiController extends Controller
{
    public function transfer(Request $request){

        $fromuser= User::find($request->from);

        if($fromuser->balance >= $request->amountsend && $request->amountsend > 0){

            $fromuser->balance= $fromuser->balance-$request->amountsend;

            $touser= User::where('paymentaddress',$request->paymentaddress)->first();
            $balance= $request->amountsend;
            $timedelay=$request->timedelay;

            SendBalance::dispatch($touser,$fromuser,$balance,$timedelay)->delay(now()->addMinutes($timedelay));
    
            $fromuser->save();
          
            if($fromuser){
                return response()->json(['success' => true]);
            }
            else{
                return response()->json(['success' => false]);
            }
        
        }
        else{
            return response()->json(['success' => false,'message' => 'low Balance']);
        }

        
        
    }

    public function tranferdetails(Request $request){
        $table= Transferdetail::where('from',$request->id)->orwhere('to',$request->id)->with('fromuser','touser')->get();
        // $table= User::where('id',$request->id)->with('hastranferdetails')->get();

        if($table){
            return response()->json(['success' => true,'data' => $table]); 
        }
        else{
            return response()->json(['success' => false,'message' => 'No Transaction Found']);
        }

    }


    public function findaccount(Request $request){
        $table= User::where('paymentaddress',$request->paymentaddress)->first();
        if($table){
            return response()->json(['success' => true, 'message' => 'Account exist', 'user' => $table]);
        }
        else{
            return response()->json([ 'success' => false,'message' => 'No Account Found']);
        }
    }

    public function profile(Request $request){
        return response()->json(['user' => User::find($request->id)]);
    }

    public function checkbalance(Request $request){
        $fromuser= User::find($request->id);
        if($fromuser->balance>=$request->balance){
            return response()->json(['success' => true,'message' => 'success']);
        }
        else{
            return response()->json(['success' => false, 'message' => 'Balance low. Please Recharge']);
        }
    }


    public function userdetails(){
        return response()->json(['success' => true,'users' => User::where('type','=','user')->get()]);
    }

    public function addbalance(Request $request){
        $admin= User::find($request->id);
        $admin->balance= $admin->balance + $request->addbalance;
        $admin->save();

        return response()->json(['success' => true,'message' => 'Balance Added Successfully']);

    }

    public function sebdverificationcode(Request $request){

        if(User::where('email',$request->email)->first()){
            return response()->json(['success' => true, 'message' => 'User already exist for this email']);
        }

        $pin = rand(100000, 999999);

        $table=sendcode::where('email',$request->email)->first();
        if($table){
            return response()->json(['success' => true, 'message' => '6-digit pin already sent to your email.']);
        }

        Mail::to($request->email)->send(new VerifyEmail($pin));

        $table= new sendcode();
        $table->email= $request->email;
        $table->code= $pin;
        $table->save();

        delsendcode::dispatch($request->email)->delay(now()->addMinutes(3));

        return response()->json(['success' => true, 'message' => 'Please check your email for a 6-digit pin to verify your email.']);
    }


    public function delhistory(Request $request){
        
        // $table=Transferdetail::where('from',$request->id)->orwhere('to',$request->id)->get();
        DB::table('transferdetails')->where('from', $request->if)->orwhere('to', $request->id)
 ->delete();
        // $table->delete();
        return response()->json(['success' => true, 'message' => 'Your History has been deleted successfully']);
    }


}
