<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Repositories\UserRepository;
use Illuminate\Http\Request;
use CodeProject\Http\Requests;
use CodeProject\Http\Controllers\Controller;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

class UserController extends Controller
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     *
     */
    public function authenticated()
    {
        $userId = Authorizer::getResourceOwnerId();
        return $this->userRepository->find($userId);
    }
}
