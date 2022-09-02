<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Transferdetail;
use App\Models\User;
use Illuminate\Http\Request;

class apiController extends Controller
{
    public function transfer(Request $request){
        $Transferdetail= new Transferdetail();
        $fromuser= User::find($request->from);
        $touser= User::where('paymentaddress',$request->paymentaddress)->first();

        if($touser){
            $Transferdetail->to= $touser->id;
        }


        else{
            return response()->json(['message' => 'Wrong Payment Address']);
        }

        $Transferdetail->amountsend= $request->amountsend;

        $fromuser->balance= $fromuser->balance-$request->balance;
        $touser->balance= $touser->balance+($request->balance- 4.40);
        $Transferdetail->save();
        $fromuser->save();
        $touser->save();
        if($Transferdetail){
            return response()->json(['message' => 'Payment Send Successfully']);
        }
        else{
            return response()->json(['message' => 'fail']);
        }
        
    }

    public function tranferdetails(Request $request){
        $table= Transferdetail::where('from',$request->id)->orwhere('to',$request->id)->with('hasusers')->get();
        if($table){


            return $table; 
        }
        else{
            return response()->json(['message' => 'No Transaction Found']);
        }

    }


    public function findaccount(Request $request){
        $table= User::where('paymentaddress', $request->paymentaddress)->first();
        if($table){
            return response()->json(['message' => 'Account exist']);
        }
        else{
            return response()->json(['message' => 'No Account Found']);
        }
    }


}
