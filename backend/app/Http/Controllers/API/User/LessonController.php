<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use App\Models\Lesson;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lessons = Lesson::all();

        $result = [];
        foreach ($lessons as $lesson) {
            $result[] = [
             'id' =>  $lesson['id'],
             'previewUrl' =>  asset('storage/' . $lesson['preview_url']),
             'title' =>  $lesson['title'],
             'subtitle' =>  $lesson['subtitle'],
             'description' =>  $lesson['description'],
            ];
        }

        return $result;
    }
}
