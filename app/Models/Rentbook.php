<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rentbook extends Model
{
    use HasFactory;



    protected $table = 'rent_book_details';

    protected $fillable = [
        'user_id',
        'book_id',
        'rent_status',
        'taken_date',
        'return_date',

    ];
}
