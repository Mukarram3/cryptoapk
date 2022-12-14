<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transferdetail extends Model
{
    use HasFactory;
    protected $table= 'transferdetails';
    protected $fillable= ['id','from','to','amountsend'];


    public function fromuser(){
        return $this->belongsTo(User::class,'from');
    }

    public function touser(){
        return $this->belongsTo(User::class,'to');
    }

}
