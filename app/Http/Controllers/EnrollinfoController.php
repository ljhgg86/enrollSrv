<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Enrollinfo;
use Response;

class EnrollinfoController extends Controller
{
    public function __construct()
    {
        $this->activity = new Enrollinfo();
    }

    public function getEnrollinfos($activityid){
        $enrollinfos =  $this->activity->getEnrollinfos($activityid);
        return response()->json([
            'status'=>true,
            'enrollinfos'=>$enrollinfos
        ]);
    }

    public function updateReadflag($id){
        $enrollinfo = Enrollinfo::find($id);
        $enrollinfo->readflag = !($enrollinfo->readflag);
        $enrollinfo->save();
    }
}
