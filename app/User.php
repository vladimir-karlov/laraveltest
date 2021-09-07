<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Validator;

class User extends Authenticatable
{
    use Notifiable;

/*
    public const VALIDATION_RULES = [
        'name' => ['required','max:128','regex:/^[a-zA-Z]+$/u', 'unique:users', 'name'],
        'email' => ['required','email','unique:users'],
    ];
*/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
/*
    public function tasks()
    {
	    return $this->hasMany('App/Task');
    }
*/
}
