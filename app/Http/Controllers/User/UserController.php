<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use App\Repositories\Contracts\IUser;

class UserController extends Controller
{
    protected $users;
    
    public function __construct(IUser $users)
    {
        $this->users = $users;
    }


    public function index()
    {
        // $users = User::all();
        $users = $this->users->all();

        return UserResource::collection($users);

        // $user = User::findOrFail(1);
        // return UserResource::collection($user);
    }
}
