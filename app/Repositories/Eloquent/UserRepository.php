<?php

namespace App\Repositores\Eloquent;
use App\Models\User;
use App\Repositores\Contracts\IUser;

class UserRepository implements IUser
{
    public function all()
    {
        return User::all();
    }
}