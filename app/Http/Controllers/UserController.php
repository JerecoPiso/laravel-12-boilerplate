<?php

namespace App\Http\Controllers;

use App\Traits\UserTrait;
use App\Repositories\UserRepositories;

class UserController extends Controller
{
    use UserTrait;

    public $userRepo;

    public function __construct(UserRepositories $userRepo)
    {
        $this->userRepo = $userRepo;
    }
}
