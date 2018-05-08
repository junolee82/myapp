<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use \Illuminate\Database\Eloquent\Collection;
use \Illuminate\Http\Response;

class ArticlesController extends Controller
{   
    // 사용자 객체 접근 가능
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function cacheTags()
    {
        return 'articles';
    }

    // Article 컬렉션 조회
    public function index(Request $request, $slug = null)
    {
        // $cacheKey = cache_key('articles.index');

        $query = $slug
            ?  \App\Tag::whereSlug($slug)->firstOrFail()->articles()
            : new \App\Article;
        
        $query = $query->orderBy(
            $request->input('sort', 'created_at'),
            $request->input('order', 'desc')
        );

        if ($keyword = request()->input('q')) {
            $raw = 'MATCH(title, content) AGAINST(? IN BOOLEAN MODE)';
            $query = $query->whereRaw($raw, [$keyword]);
        }

        $articles = $query->paginate(5);
        // $articles = $this->cache($cacheKey, 5, $query, 'paginate', 5);
        
        return $this->respondCollection($articles);
    }
    
    // Article 컬렉션을 만들기 휘한 폼을 담은 뷰 반환
    public function create()
    {
        $article = new \App\Article;
        
        return view('articles.create', compact('article'));
    }
    
    // 사용자의 입력한 폼 데이터로 새로운 Article 컬렉션을 만듬
    public function store(\App\Http\Requests\ArticlesRequest $request)
    { 
        $user = $request->user();

        $article = $user->articles()->create($request->getPayload());

        if (! $article) {
            return back()->with('flash_massage', '글이 저장되지 않았습니다.')->withInput();
        }

        // 태그 싱크
        $article->tags()->sync($request->input('tags'));

        // 첨부파일 연결
        $request->getAttachments()->each(function ($attachment) use ($article) {
            $attachment->article()->associate($article);
            $attachment->save();
        });
                
        event(new \App\Providers\App\Events\ArticlesEvent($article));
        event(new \App\Events\ModelChanged(['articles']));

        return $this->respondCreated($article);
    }
    
    // 기본키를 가진 Article 모델을 조회
    public function show(\App\Article $article)
    {
        if (! is_api_domain()) {
            $article->view_count += 1;
            $article->save();
        }
        
        // 댓글 목록 조회
        $comments = $article->comments()->with('replies')
        ->withTrashed()->whereNull('parent_id')->latest()->get();

        return $this->respondInstance($article, $comments);
    }
    
    // 기본 키를 가진 Article 모델을 수정하기 위한 폼을 담은 뷰를 반환
    public function edit(\App\Article $article)
    {
        $this->authorize('update', $article);

        return view('articles.edit', compact('article'));
    }
    
    // 사용자의 입력한 폼 데이터로 다음 기본 키를 가진 Article 모델을 수정
    public function update(\App\Http\Requests\ArticlesRequest $request, \App\Article $article)
    {
        $this->authorize('update', $article);

        $payload = array_merge($request->all(), [
            'notification' => $request->has('notification'),
        ]);

        $article->update($payload);
        $article->tags()->sync($request->input('tags'));

        event(new \App\Events\ModelChanged(['articles']));
        flash()->success(
            trans('forum.articles.success_updating')
        );

        return $this->respondUpdated($article);
    }
    
    // 기본 키를 가진 Article 모델을 삭제
    public function destroy(\App\Article $article)
    {
        $this->authorize('delete', $article);

        $this->deleteAttachments($article->attachments);        

        $article->delete();

        event(new \App\Events\ModelChanged(['articles']));

        return response()->json([], 204, [], JSON_PRETTY_PRINT);
    }

    /* 
     * --- Response Methods ---
     */

    protected function respondCollection(LengthAwarePaginator $articles, $cacheKey = null)
    {
        return view('articles.index', compact('articles'));
    }

    protected function respondCreated($article)
    {
        flash()->success(
            trans('forum.articles.success_writing')
        );

        return redirect(route('articles.show', $article->id));
    }

    protected function respondInstance(\App\Article $article, Collection $comments)
    {
        return view('articles.show', compact('article', 'comments'));
    }

    protected function respondUpdated(\App\Article $article)
    {
        flash()->success(trans('forum.articles.success_updating'));

        return redirect(route('articles.show', $article->id));
    }
}
