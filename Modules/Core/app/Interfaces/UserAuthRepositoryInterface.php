<?php

namespace Modules\Core\Interfaces;

use illuminate\Foundation\Auth\User as Authenticatable;

interface UserAuthRepositoryInterface
{
    public function createNewUser(array $data): Authenticatable;
}
