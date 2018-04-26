<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    
    public function index()
    {
        //$articles = \App\Article::with('user')->get();

        $articles = \App\Article::latest()->paginate(3);

        // user() 관계가 필요 없는 다른 로직 수행
        $articles->load('user');

        return view('articles.index', compact('articles'));
    }
    
    public function create()
    {
        return view('articles.create');
    }
    
    public function store(\App\Http\Requests\ArticlesRequest $request)
    {
        /* $rules = [
            'title' => ['required'],
            'content' => ['required', 'min:10'],
        ];

        $messages = [
            'title.required' => '제목은 필수 입력 항목입니다.',
            'content.required' => '본문은 필수 입력 항목입니다.',
            'content.min' => '본문은 최소 :min 글자 이상이 필요합니다.',
        ]; */

        /* $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } */
        //$this->validate($request, $rules, $messages);

        $article = \App\User::find(1)->articles()->create($request->all());

        if (! $article) {
            return back()->with('flash_massage', '글이 저장되지 않았습니다.')->withInput();
        }
        
        event(new \App\Providers\App\Events\ArticlesEvent($article));

        return redirect(route('articles.index'))->with('flash_message', '작성하신 글이 저장되었습니다.');
    }
    
    public function show($id)
    {
        $article = \App\Article::findOrFail($id);

        return $article->toArray();
    }
    
    public function edit($id)
    {
        return __METHOD__ . '은(는) 다음 기본 키를 가진 Article 모델을 수정하기 위한 폼을 담은 뷰를 반환합니다.' . $id;
    }
    
    public function update(Request $request, $id)
    {
        return __METHOD__ . '은(는) 사용자의 입력한 폼 데이터로 다음 기본 키를 가진 Article 모델을 수정합니다.' . $id;
    }
    
    public function destroy($id)
    {
        return __METHOD__ . '은(는) 다음 기본 키를 가진 Article 모델을 삭제합니다.' .  $id;
    }
}
