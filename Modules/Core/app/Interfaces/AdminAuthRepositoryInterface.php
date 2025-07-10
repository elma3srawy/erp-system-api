<?php

namespace Modules\Core\Interfaces;

use Illuminate\Foundation\Auth\User as Authenticatable;
interface AdminAuthRepositoryInterface
{
       public function createNewAdmin(array $data): Authenticatable;
}
