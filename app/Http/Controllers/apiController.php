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


}
