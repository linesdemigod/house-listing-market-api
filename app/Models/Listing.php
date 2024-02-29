<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'category',
    'title',
    'bedroom',
    'bathroom',
    'land_size',
    'garage',
    'address',
    'price',
    'about',
    'image',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }

  public function wishlist()
  {
    return $this->hasMany(WishList::class);
  }

}
