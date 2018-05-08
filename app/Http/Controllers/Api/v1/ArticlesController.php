<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\ArticlesController as ParentController;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use \App\Article;
use \Illuminate\Database\Eloquent\Collection;
use \Illuminate\Http\JsonResponse;

class ArticlesController extends ParentController
{   
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * @param LengthAwarePaginator $articles
     * @param string|null $cacheKey
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCollection(LengthAwarePaginator $articles, $cacheKey = null)
    {
        return $articles->toJson(JSON_PRETTY_PRINT);
    }
    
    /**
     * @param \App\Article $article
     * @param \Illuminate\Database\Eloquent\Collection $comments
     * @return string
     */
    protected function respondInstance(Article $article, Collection $comments)
    {
        $cacheKey = cache_key('articles.'.$article->id);
        $reqEtag = request()->getETags();
        $genEtag = $this->etag($article, $cacheKey);

        if (config('project.etag') and isset($reqEtag[0]) and $reqEtag[0] === $genEtag) {
            return json()->notModified();
        }

        return json()->setHeaders(['Etag' => $genEtag])->withItem(
            $article,
            new \App\Transformers\ArticleTransformer
        );
    }

    /**
     * @param $article
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated($article)
    {
        return json()->setHeaders([
            'Location' => route('api.v1.articles.show', $article->id),
        ])->created('created');
    }

    /**
     * @param \App\Article $article
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondUpdated(Article $article)
    {
        return json()->success('updated');
    }
}
