<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activities;
use App\Models\Formtag;
use Response;

class ActivitiesController extends Controller
{
    public function __construct()
    {
        $this->activity = new Activities();
        $this->formtag = new Formtag();
    }

    public function list(){
        $activities = Activities::select('id', 'title')->paginate(15);
        return $activities;
    }

    public function getActivity($id){
        $activity = $this->activity->getActivities($id);
        $formtags = $this->formtag->getFormtags();
        return response()->json([
            'status' => true,
            'activity' => $activity,
            'formtags' => $formtags
        ]);
    }
}
