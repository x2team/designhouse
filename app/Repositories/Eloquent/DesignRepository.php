<?php

namespace App\Repositories\Eloquent; 

use App\Models\Design;
use App\Repositories\Contracts\IDesign;
use App\Repositories\Eloquent\BaseRepository;

class DesignRepository extends BaseRepository implements IDesign
{
   
    // public function model()
    // {
    //     return Design::class; // App\Models\Design
    // }

    public function all()
    {
        return Design::all(); // App\Models\Design
    }



}