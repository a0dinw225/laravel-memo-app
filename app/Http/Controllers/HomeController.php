<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\MemoRequest;
use Illuminate\Contracts\View\View;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\MemoTag;
use App\Services\TagService;
use App\Services\MemoService;
use DB;

class HomeController extends Controller
{
    /** @var TagService */
    protected $tagService;

    /** @var MemoService */
    protected $memoService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(TagService $tagService, MemoService $memoService)
    {
        $this->tagService = $tagService;
        $this->memoService = $memoService;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $auth_id = \Auth::id();
        $tags = $this->tagService->getUserTags($auth_id);

        return view('create', compact('tags'));
    }

    /**
     * Create new memo
     * @param MemoRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MemoRequest $request): RedirectResponse
    {
        try {
            $posts = $request->all();

            DB::transaction(function() use($posts) {
                $authId = \Auth::id();
                $memo_id = $this->memoService->createNewMemoGetId($posts, $authId);
                $tag_exists = $this->tagService->checkIfTagExists($authId, $posts['new_tag']);
            
                $this->tagService->attachTagsToMemo($posts, $memo_id, $tag_exists, $authId);
            });

            return redirect(route('home'));

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->view('error', ['message' => 'データの保存中にエラーが発生しました。'], 500);
        }
    }

    /**
     * Edit screen for memo
     * @param int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(int $id): View
    {
        $authId = \Auth::id();
        $tags = $this->tagService->getUserTags($authId);
        $getMemoTags = $this->memoService->getMemoTags($id, $authId);
        $editMemo = $getMemoTags['memo_with_tags'];
        $memoTagIds = $getMemoTags['memo_tag_ids'];

        return view('edit', compact('editMemo', 'memoTagIds', 'tags'));
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
