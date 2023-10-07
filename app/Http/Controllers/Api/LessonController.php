<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Lesson;

class LessonController extends Controller
{
    //
    public function lessonList(Request $request)
    {
        $id = $request->id;
        try {

            $result = Lesson::where('course_id', '=', $id)
                ->select(
                    'description',
                    'video',
                    'name',
                    'thumbnail',
                    'id'
                )->get();
            return response()->json([
                'code' => 200,
                'msg' => 'my lesson list is here',
                'data' => $result
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'msg' => 'Server internal Error',
                'data' => $e
            ], 500);
        }

    } //
    public function lessonDetail(Request $request)
    {
        $id = $request->id;
        try {

            $result = Lesson::where('id', '=', $id)
                ->select(
                    'description',
                    'video',
                    'name',
                    'thumbnail',
                )->get();
            return response()->json([
                'code' => 200,
                'msg' => 'my lesson DEtail is here',
                'data' => $result
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'code' => 500,
                'msg' => 'Server internal Error',
                'data' => $e
            ], 500);
        }

    }
}