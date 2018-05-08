<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentsRequest extends FormRequest
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
     * 요청에 적용되는 유효성 검사 규칙을 가져옵니다.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'content' => ['required', 'min:4'],
            'parent_id' => ['numeric', 'exists:comments,id'],
        ];
    }
}
