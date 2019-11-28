<?php

namespace App\Admin\Controllers;

use App\Models\Inputtype;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

use Illuminate\Http\Request;
use DB;

class InputtypeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '输入类型';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Inputtype);

        $grid->column('id', __('Id'));
        $grid->column('name', __('输入类型'));
        $grid->column('tag', __('说明'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Inputtype::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('tag', __('Tag'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Inputtype);

        $form->text('name', __('Name'));
        $form->text('tag', __('Tag'));

        return $form;
    }

    public function getInputtypes(Request $request){
        $formtagid = $request->get('q');
        var_dump($formtagid);
        return Inputtype::where('formtag_id',$formtagid)->get(['id', DB::raw('tag')]);
    }
}
