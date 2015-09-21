<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 17/09/15
 * Time: 20:41
 */

namespace CodeProject\OAuth;


use Illuminate\Support\Facades\Auth;

class Verifier
{
    public function verify($username, $password)
    {
        $credentials = [
            'email'    => $username,
            'password' => $password,
        ];

        if (Auth::once($credentials)) {
            return Auth::user()->id;
        }

        return false;
    }
}
