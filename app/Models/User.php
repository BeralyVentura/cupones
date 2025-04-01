<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;  // Importa el trait

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;  // Agrega el trait HasApiTokens

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    public function businesses()
    {
        return $this->hasMany(Business::class);
    }
}