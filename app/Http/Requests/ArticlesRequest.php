<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticlesRequest extends FormRequest
{    
    protected $dontFlash = [
        'files',
    ];

    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        $mimes = implode(',', config('project.mimes'));

        return [
            'title' => ['required'],
            'content' => ['required', 'min:10'],
            'files' => ['array'],
            'files.*' => ["mimes:{$mimes}", 'max:30000'],
            'attachments' => ['array'],
            'attachments.*' => ['integer', 'exists:attachments,id'],
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute은(는) 필수 입력 항목입니다.',
            'min' => ":attribute은(는) 최소 :min 글자 이상이 필요합니다.",
        ];
    }

    public function attributes()
    {
        return [
            'title' => '제목',
            'content' => '본문',
            'tags' => ['required', 'array'],
        ];
    }

    /**
     * 'notification' 입력 값을 머지한 사용자 입력값을 조회합니다.
     *
     * @return array
     */
    public function getPayload()
    {
        return array_merge($this->all(), [
            'notification' => $this->has('notification'),
        ]);
    }

    /**
     * 사용자 입력 값으로부터 첨부파일 객체를 조회합니다.
     *
     * @return Collection
     */
    public function getAttachments()
    {
        return \App\Attachment::whereIn('id', $this->input('attachments', []) )->get();
    }

}
