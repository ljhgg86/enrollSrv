<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activities;
use App\Models\Formtag;
use App\Models\Formitem;
use Response;

class ActivitiesController extends Controller
{
    public function __construct()
    {
        $this->activity = new Activities();
        $this->formtag = new Formtag();
        $this->formitem = new Formitem();
    }

    //获取所有活动列表
    public function list(){
        $activities = Activities::select('id', 'title')->orderBy('id','desc')->paginate(15);
        return $activities;
    }

    //获取指定活动编辑信息和选项可选类型列表
    public function getActivity($id){
        $activity = $this->activity->getActivities($id);
        $formtags = $this->formtag->getFormtags();
        return response()->json([
            'status' => true,
            'activity' => $activity,
            'formtags' => $formtags
        ]);
    }

    //获取选项可选类型列表
    public function getFormtags(){
        $formtags = $this->formtag->getFormtags();
        return response()->json([
            'status'=>true,
            'formtags'=>$formtags
        ]);
    }

    //保存编辑信息
    public  function storeActivity(Request $request){
        $id = $request->input('id');
        $title = $request->input('title');
        $topimg = $request->input('topimg');
        $beginTime = date("Y-m-d",strtotime($request->input('begintime'))).'T'.date("h:i:s",strtotime($request->input('begintime')));
        $endTime = date("Y-m-d",strtotime($request->input('endtime'))).'T'.date("h:i:s",strtotime($request->input('endtime')));
        $formitems = $request->input('formitems');
        if(!$id){
            $activity = $this->activity->create(['title'=>$title,'topimg'=>$topimg,'begintime'=>$beginTime,'endtime'=>$endTime]);
            $id = $activity->id;
        }
        else{
            $this->activity
            ->where('id', $id)
            ->update(['title'=>$title,'topimg'=>$topimg,'begintime'=>$beginTime,'endtime'=>$endTime]);
        }
        if(!empty($formitems)){
            $this->formitem->saveFormitems($formitems,$id);
        }
        return response()->json([
            'status'=>true
        ]);
    }
}
