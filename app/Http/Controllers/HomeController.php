<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\MemoRequest;
use Illuminate\Contracts\View\View;
use App\Models\Memo;
use App\Services\TagService;
use App\Services\MemoService;
use App\Services\MemoTagService;
use DB;

class HomeController extends Controller
{
    /** @var TagService */
    protected $tagService;

    /** @var MemoService */
    protected $memoService;

    /** @var MemoTagService */
    protected $memoTagService;

    /**
     * Create a new controller instance.
     *
     * @param TagService $tagService
     * @param MemoService $memoService
     * @param MemoTagService $memoTagService
     */
    public function __construct(TagService $tagService, MemoService $memoService, MemoTagService $memoTagService)
    {
        $this->tagService = $tagService;
        $this->memoService = $memoService;
        $this->memoTagService = $memoTagService;
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
                $memoId = $this->memoService->createNewMemoGetId($posts, $authId);
                $tagExists = $this->tagService->checkIfTagExists($authId, $posts['new_tag']);

                $this->tagService->attachTagsToMemo($posts, $memoId, $tagExists, $authId);
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

    public function update(MemoRequest $request): RedirectResponse
    {
        $posts = $request->all();

        //===== トランザクション開始 =====
        DB::transaction(function () use($posts) {
            $authId = \Auth::id();
            $memoId = $posts['memo_id'];

            $this->memoService->updateMemo($memoId, $posts['content']);
            $this->memoTagService->deleteMemoTag($memoId);

            $tagExists = $this->tagService->checkIfTagExists($authId, $posts['new_tag']);

            $this->tagService->attachTagsToMemo($posts, $memoId, $tagExists, $authId);
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
