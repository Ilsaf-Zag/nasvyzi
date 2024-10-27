<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function show(Request $request, Lesson $lesson)
    {
        $success = false;
        $data = [];
        $code = 0;

        if ($request->user()->hasSubscription()) { // Проверка подписки
            $videoUrl = $lesson->video_url;

            if (Storage::disk('public')->exists($videoUrl)) {

                $data = [
                    'videoUrl' => asset('storage/' . $videoUrl),
                    'mimeType' => Storage::disk('public')->mimeType($videoUrl)
                ];
                $success = true;
            } else {
                $code = 10;
            }
        } else {
            $code = 20;
        }

        return response()->json([
            'success' => $success,
            'code' => $code,
            'data' => $data
        ]);
    }
}
