<?php

namespace App\Http\Requests\Lesson;

use Illuminate\Foundation\Http\FormRequest;
class UpdateLessonRequest extends FormRequest
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
            'description'=>['nullable','string']
        ];
    }
}
