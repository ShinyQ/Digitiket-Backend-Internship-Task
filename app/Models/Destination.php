<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;
    protected $table = "destination";

    public $timestamps = true;
    protected $fillable = ['title', 'description', 'keyword', 'images', 'address', 'views'];
}
