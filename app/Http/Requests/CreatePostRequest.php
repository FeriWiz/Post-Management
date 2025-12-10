<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'released_at' => 'required|date ',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'عنوان پست را وارد کنید',
            'title.string' => 'عنوان پست باید یک رشته باشد',
            'title.max' => 'عنوان پست نباید بیشتر از 255 کاراکتر باشد',
            'content.required' => 'محتوای پست را وارد کنید',
            'content.string' => 'محتوای پست باید یک رشته باشد',
            'image.image' => 'مسیر تصویر باید یک فایل تصویری باشد',
            'image.max' => 'حجم تصویر نباید بیشتر از 2 مگابایت باشد',
            'released_at.required' => 'تاریخ انتشار را وارد کنید',
            'released_at.date' => 'تاریخ انتشار باید یک تاریخ معتبر باشد',
        ];
    }
}
