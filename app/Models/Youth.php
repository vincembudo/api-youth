<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Youth extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'address',
        'contact_no',
        'school',
        'user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
