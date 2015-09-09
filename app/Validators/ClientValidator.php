<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 08/09/15
 * Time: 20:28
 */

namespace CodeProject\Validators;


use Prettus\Validator\LaravelValidator;

class ClientValidator extends  LaravelValidator
{
    protected $rules =[
        'name' => 'required|max:255',
        'responsible' => 'required|55',
        'email' => 'required|email',
        'phone' => 'required',
        'address' => 'required'
    ];
}