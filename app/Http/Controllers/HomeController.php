<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\MemoTag;
use App\Repositories\TagRepository;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): View
    {
        $auth_id = \Auth::id();
        $tags = $this->tagRepository->findUserTags($auth_id);

        return view('create', compact('tags'));
    }

    public function store(Request $request)
    {
        $posts = $request->all();
        $request->validate(['content' => 'required']);

        //===== トランザクション開始 =====
        DB::transaction(function() use($posts) {
            // メモIDをinsertして取得
            $memo_id = Memo::insertGetId(['content' => $posts['content'], 'user_id' => \Auth::id()]);
            $tag_exists = Tag::where('user_id', '=', \Auth::id())->where('name', '=', $posts['new_tag'])
            ->exists();
            // 新規タグが入力されているかチェック
            // 新規タグが既にtagsテーブルに存在するのかチェック
            if( !empty($posts['new_tag']) && !$tag_exists ) {
                // 新規タグが既に存在しなければ、tagsテーブルにinsertIDを取得
                $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
                // memo_tagsにinsertして、メモとタグを紐づける
                MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag_id]);
            }
            // 既存タグが紐づけられた場合
            if(!empty($posts['tags'][0])){
                foreach($posts['tags'] as $tag){
                    MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag]);
                }
            }
        });
        // ===== トランザクション終了 =====

        return redirect( route('home') );
    }

    public function edit($id)
    {
        $edit_memo = Memo::select('memos.*', 'tags.id AS tag_id')
        ->leftJoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
        ->leftJoin('tags', 'memo_tags.tag_id', '=', 'tags.id')
        ->where('memos.user_id', '=', \Auth::id())
        ->where('memos.id', '=', $id)
        ->whereNull('memos.deleted_at')
        ->get();

        $include_tags = [];
        foreach($edit_memo as $memo) {
            array_push($include_tags, $memo['tag_id']);
        }
        $tags = Tag::where('user_id', '=', \Auth::id())->where('deleted_at')->orderBy('id', 'DESC')
        ->get();

        return view('edit', compact('edit_memo', 'include_tags', 'tags'));
    }

    public function update(Request $request)
    {
        $posts = $request->all();
        $request->validate(['content' => 'required']);

        //===== トランザクション開始 =====
        DB::transaction(function () use($posts) {
            Memo::where('id', $posts['memo_id'])->update(['content' => $posts['content']]);
            MemoTag::where('memo_id', '=', $posts['memo_id'])->delete();
            foreach($posts['tags'] as $tag) {
                MemoTag::insert(['memo_id' => $posts['memo_id'], 'tag_id' => $tag]);
            }
            $tag_exists = Tag::where('user_id', '=', \Auth::id())->where('name', '=', $posts['new_tag'])
            ->exists();
            // 新規タグが入力されているかチェック
            // 新規タグが既にtagsテーブルに存在するのかチェック
            if( !empty($posts['new_tag']) && !$tag_exists ) {
                // 新規タグが既に存在しなければ、tagsテーブルにinsertIDを取得
                $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
                // memo_tagsにinsertして、メモとタグを紐づける
                MemoTag::insert(['memo_id' => $posts['memo_id'], 'tag_id' => $tag_id]);
            }
        });
        // ===== トランザクション終了 =====

        return redirect( route('home') );
    }

    public function destory(Request $request)
    {
        $posts = $request->all();

        Memo::where('id', $posts['memo_id'])->update(['deleted_at' => date("Y-m-d H:i:s", time())]);

        return redirect( route('home') );
    }
}
