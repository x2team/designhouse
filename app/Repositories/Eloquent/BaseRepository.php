<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\ModelNotDefine;
use App\Models\User;
use App\Repositories\Contracts\IBase;
use Exception;

abstract class BaseRepository implements IBase
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->getModelClass();
        
    }
    
    public function all()
    {
        return $this->model->all();
    }

    protected function getModelClass()
    {
        if( !method_exists($this, 'model'))
        {
            throw new ModelNotDefine();
        }

        return app()->make($this->model());
    }
}
