<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title'=>['required','string'],
            'subTitle'=>['required','string'],
            'previewUrl'=>['required'],
            'videoUrl'=>['required'],
            'description'=>['nullable','string']
        ];
    }
}
