<?php

namespace App\Repositores\Eloquent;
use App\Models\Design;
use App\Repositores\Contracts\IDesign;

class DesignRepository implements IDesign
{
    public function all()
    {
        return Design::all();
    }
}