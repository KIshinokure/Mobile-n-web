<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /** Идентификатор посетителя хранится в сессии */
    private function visitor(Request $request): string
    {
        if (!$request->session()->has('visitor_id')) {
            $request->session()->put('visitor_id', Str::random(32));
        }
        return $request->session()->get('visitor_id');
    }

    private function likedIds(Request $request): array
    {
        return DB::table('news_likes')
            ->where('visitor_id', $this->visitor($request))
            ->pluck('news_id')
            ->all();
    }

    public function index(Request $request)
    {
        return view('news.index', [
            'news'  => News::orderByDesc('created_at')->get(),
            'liked' => $this->likedIds($request),
        ]);
    }

    public function show(Request $request, News $news)
    {
        return view('news.show', [
            'item'  => $news,
            'liked' => in_array($news->id, $this->likedIds($request)),
        ]);
    }

    public function like(Request $request, News $news): JsonResponse
    {
        $visitor = $this->visitor($request);
        DB::transaction(function () use ($news, $visitor) {
            $inserted = DB::table('news_likes')->insertOrIgnore([
                'news_id' => $news->id, 'visitor_id' => $visitor, 'liked_at' => now(),
            ]);
            if ($inserted) {
                $news->increment('likes_count');
            }
        });
        return $this->state($news->refresh(), true);
    }

    public function unlike(Request $request, News $news): JsonResponse
    {
        $visitor = $this->visitor($request);
        DB::transaction(function () use ($news, $visitor) {
            $deleted = DB::table('news_likes')
                ->where(['news_id' => $news->id, 'visitor_id' => $visitor])
                ->delete();
            if ($deleted && $news->likes_count > 0) {
                $news->decrement('likes_count');
            }
        });
        return $this->state($news->refresh(), false);
    }

    public function likes(Request $request, News $news): JsonResponse
    {
        return $this->state($news, in_array($news->id, $this->likedIds($request)));
    }

    private function state(News $news, bool $liked): JsonResponse
    {
        return response()->json([
            'ok' => true, 'news_id' => $news->id, 'likes' => $news->likes_count, 'liked' => $liked,
        ]);
    }
}
