<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    //
    public function courseListe()
    {
        //returnig all the course liste
        //select it to select the fields
        $result = Course::select('name', 'thumbnail', 'lesson_num', 'price', 'id')->get();
        return response()->json([
            'code' => 200,
            'msg' => 'my course list is here',
            'data' => $result
        ], 200);
    }
}