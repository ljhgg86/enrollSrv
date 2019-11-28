<?php

namespace App\Admin\Controllers;

use App\Models\Activities;
use App\Models\Formtag;
use App\Models\Inputtype;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Admin;
use App\Admin\Actions\Activities\Operate;

class ActivitiesController extends AdminController
{
    /**
    *Create a new instance.
    */
    public function __construct()
    {
        $this->activities=new Activities();
    }
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '活动列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Activities);

        $grid->column('id', __('序号'))->sortable();
        $grid->column('title', __('标题'));
        $grid->column('created_at', __('创建时间'));
        $grid->model()->orderBy('id', 'desc');
        $grid->actions(function ($actions) {
            $actions->add(new Operate);
        });

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
        $show = new Show(Activities::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('topimg', __('Topimg'));
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
        $formtags = Formtag::pluck('tag','id');
        $form = new Form(new Activities);
        $form->tab('基本信息', function($form){
            $form->text('title', __('活动标题'));
            $form->image('topimg', __('封面图片'))->uniqueName();
            $form->datetime('begintime',__('开始时间'));
            $form->datetime('endtime',__('结束时间'));
        })->tab('编辑选项', function($form) use ($formtags) {
            $form->hasMany('formitems', '编辑选项', function(Form\NestedForm $form) use ($formtags) {
                $form->text('name',__('选项名'));
                $form->select('formtag_id',__('类型'))->options($formtags)->load('inputtypes','/admin/inputtype/getInputtypes');
                $form->select('inputtypes',__('输入类型'));
            });
        });

        return $form;
    }


    public function myList(Content $content){
        //$content = new Content();
        $content->header("活动列表");
        $activities = Activities::select('id', 'title')->paginate(15);
        //$activities = Activities::all();
        $content->view('enroll.activities', ['activities' => $activities]);
        return $content;
    }

    public function operate($id){
        $content = new Content();
        $activities = $this->activities->getActivities($id);
        $content->view('enroll.formitem', ['activities' => $activities]);
        return $content;
    }
}
