<?php

namespace Modules\Core\Repositories;

use Modules\Core\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Core\Interfaces\UserAuthRepositoryInterface;

class UserAuthRepository implements UserAuthRepositoryInterface
{
    protected static Model $model;

    public function __construct(){
        self::$model =  new User();
    }
    public function createNewUser(array $data): Authenticatable
    {
        return static::$model->create($data);
    }
}
