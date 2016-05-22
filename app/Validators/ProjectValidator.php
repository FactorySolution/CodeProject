<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 08/09/15
 * Time: 21:16
 */

namespace CodeProject\Validators;


use Prettus\Validator\LaravelValidator;

class ProjectValidator extends LaravelValidator
{
    protected $rules = [
                'owner_id' => 'required|max:100',
                'client_id' => 'required|max:100',
                'name' => 'required|max:100',
                'description' => 'required|',
                'progress' => 'required|',
                'status' => 'required|',
                'due_date' => 'required|',
    ];

}