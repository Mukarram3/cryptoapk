<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transferdetail;
use App\Models\User;
use App\Jobs\SendBalance;
use Illuminate\Http\Request;

class apiController extends Controller
{
    public function transfer(Request $request){

        $fromuser= User::find($request->from);


        if($fromuser->balance > $request->balance){

            $fromuser->balance= $fromuser->balance-$request->amountsend;

            $touser= User::where('paymentaddress',$request->paymentaddress)->first();
            $balance= $request->amountsend;
            $timedelay=$request->timedelay;
            SendBalance::dispatch($touser,$fromuser,$balance,$timedelay)->delay(now()->addMinutes($request->timedelay));
    
            $fromuser->save();
          
            if($fromuser){
                return response()->json(['message' => true]);
            }
            else{
                return response()->json(['message' => false]);
            }
        
        }
        else{
            return response()->json(['success' => false,'message' => 'low Balance']);
        }

        
        
    }

    public function tranferdetails(Request $request){
        $table= Transferdetail::where('from',$request->id)->orwhere('to',$request->id)->with('hasusers')->get();
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

}
