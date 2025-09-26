<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Agent;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'userid',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function username()
    {
        $login = request()->input('login'); // input field name = login

        // check if it's email or user_id
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'userid';

        // merge into request for auth attempt
        request()->merge([$field => $login]);

        return $field;
    }


    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }


    public function isAdmin()
    {
        return (bool) $this->agent->is_admin;
    }
}
