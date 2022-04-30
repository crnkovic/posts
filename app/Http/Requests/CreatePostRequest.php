<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:300',
            'image' => 'required|image|max:10000', // 10 MB
        ];
    }

    /**
     * Get the path to the uploaded post cover image.
     *
     * @return string
     */
    public function uploadedImagePath() : string
    {
        return $this->file('image')->storePublicly('images', [
            'disk' => Post::DISK,
        ]);
    }
}
