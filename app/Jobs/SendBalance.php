<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Transferdetail;

class SendBalance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $touser;
    public $fromuser;
    public $balance;
    public $timedelay;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($touser,$fromuser,$balance,$timedelay)
    {
        $this->touser= $touser;
        $this->fromuser= $fromuser;
        $this->balance= $balance;
        $this->timedelay= $timedelay;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $touser= User::where('paymentaddress',$request->paymentaddress)->first();
        $Transferdetail= new Transferdetail();

        $Transferdetail->amountsend= $this->balance;
        $Transferdetail->from= $this->fromuser->id;
        $Transferdetail->to= $this->touser->id;

        $sendtouser=User::find($this->touser->id);
        if($this->timedelay == 0){
            $sendtouser->balance= $sendtouser->balance + ($this->balance- 4.40);
        }

        else if($this->timedelay == 1){
            $sendtouser->balance= $sendtouser->balance + ($this->balance- 1.20);
        }

        else if($this->timedelay == 2880){
            $sendtouser->balance= $sendtouser->balance + ($this->balance- 2.60);
        }

        else if($this->timedelay == 4320){
            $sendtouser->balance= $sendtouser->balance + ($this->balance- 5.25);
        }

        
        $Transferdetail->save();
        $sendtouser->save();
    }
}
