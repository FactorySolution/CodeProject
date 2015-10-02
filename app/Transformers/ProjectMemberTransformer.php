<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 01/10/15
 * Time: 20:09
 */

namespace CodeProject\Transformers;


use CodeProject\Entities\User;
use League\Fractal\TransformerAbstract;

class ProjectMemberTransformer extends TransformerAbstract
{
    public function transform(User $member)
    {
        return [
            'member_id' => $member->id,
            'name' => $member->name,
        ];
    }
}