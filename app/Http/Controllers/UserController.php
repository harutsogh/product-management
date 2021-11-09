<?php

namespace App\Http\Controllers;

use App\Services\UserService;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends BaseController
{
    /**
     * UserController constructor.
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        $this->baseService = $service;
    }
}
