<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 17/09/15
 * Time: 19:21
 */

namespace CodeProject\Validators;


use Prettus\Validator\LaravelValidator;

class ProjectTaskValidator extends LaravelValidator
{
    protected $rules = [
        		'project_id' => 'required|integer',
        		'name' => 'required|max:255',
        		'start_date' => 'required|date',
        		'due_date' => 'required|date',
        		'status' => 'required|integer'];

}