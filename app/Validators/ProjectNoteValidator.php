<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 08/09/15
 * Time: 21:16
 */

namespace CodeProject\Validators;


use Prettus\Validator\LaravelValidator;

class ProjectNoteValidator extends LaravelValidator
{
    protected $rules = [
        'project_id' => 'requided',
        'title' => 'requided',
        'note' => 'required'
    ];

}