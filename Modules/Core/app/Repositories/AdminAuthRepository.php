<?php

namespace Modules\Core\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Modules\Core\Interfaces\AdminAuthRepositoryInterface;
use Modules\Core\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminAuthRepository implements AdminAuthRepositoryInterface
{
     protected static Builder $model;

    public function __construct(){
        // self::$model =  new Admin();
         self::$model = Admin::newQuery();
    }

    public function createNewAdmin(array $data): Authenticatable
    {
        return static::$model->create($data);
    }
}
