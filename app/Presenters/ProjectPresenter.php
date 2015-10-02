<?php
/**
 * Created by PhpStorm.
 * User: Andre
 * Date: 01/10/15
 * Time: 20:39
 */

namespace CodeProject\Presenters;

use CodeProject\Transformers\ProjectTransformer;
use Prettus\Repository\Presenter\FractalPresenter;


class ProjectPresenter extends FractalPresenter
{
    public function getTransformer(){
        return new ProjectTransformer();
    }
}