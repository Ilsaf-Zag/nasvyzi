<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Lesson\StoreLessonRequest;
use App\Http\Requests\Lesson\UpdateLessonRequest;
use App\Mail\FeedbackMail;
use App\Models\Lesson;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $pageSize = request()->input('pageSize', 8);

        if ((int)$pageSize === 0) {
            $pageSize = 99999;
        }

        $lessons = Lesson::orderByDesc('id')->paginate($pageSize);

        return $lessons;
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLessonRequest $request)
    {
        $data = $request->validated();

        $previewImage = $request['previewUrl'];
        $video = $request['videoUrl'];


        $imageName = Storage::disk('public')->putFile('',$previewImage);;
        $videoName = Storage::disk('public')->putFile('', $video);

        Lesson::create([
            'preview_url' => $imageName,
            'title' => $data['title'],
            'subtitle' => $data['subTitle'],
            'video_url' => $videoName,
            'description' => $data['description'],
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lesson $lesson)
    {
        return $lesson;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLessonRequest $request, Lesson $lesson)
    {
        $data = $request->validated();
        $lesson->update(['title'=>$data['title'],'subtitle'=>$data['subTitle'],'description'=>$data['description']]);

        return $lesson;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

    }
}
