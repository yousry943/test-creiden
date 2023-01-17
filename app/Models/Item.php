<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Corcel\Model\Post as Corcel;
class Item extends Corcel
{
    use HasFactory;

 
    protected $fillable = [
        'post_title',
        'post_content',
        'post_name',
        'post_type',
        'post_status',
   
    ];


}
