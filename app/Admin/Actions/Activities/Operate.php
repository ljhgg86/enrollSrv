<?php

namespace App\Admin\Actions\Activities;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class Operate extends RowAction
{
    public $name = '详情';

    public function handle(Model $model)
    {
        // $model ...

        return $this->response()->success('Success message.')->refresh();
    }

    public function href()
    {
        return "activities/operate/".$this->getKey();
    }

}
