<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'title' => 'sometimes|required',
            'content' => 'sometimes|required',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'released_at' => 'sometimes|date',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required' => 'عنوان پست الزامی است',
            'content.required' => 'محتوای پست الزامی است',
            'image.image' => 'فایل انتخاب شده باید یک تصویر باشد',
            'image.mimes' => 'فرمت تصویر باید یکی از موارد jpeg, png, jpg, gif, svg باشد',
            'image.max' => 'حجم تصویر نباید بیشتر از 2048 کیلوبایت باشد',
            'released_at.date' => 'تاریخ انتشار باید یک تاریخ معتبر باشد',
        ];
    }
}
