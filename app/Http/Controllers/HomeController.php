<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\MemoRequest;
use Illuminate\Contracts\View\View;
use App\Services\TagServiceInterface;
use App\Services\MemoServiceInterface;
use App\Services\MemoTagServiceInterface;
use Exception;
use DB;

class HomeController extends Controller
{
    /** @var TagServiceInterface */
    protected $tagService;

    /** @var MemoServiceInterface */
    protected $memoService;

    /** @var MemoTagServiceInterface */
    protected $memoTagService;

    /**
     * HomeController constructor.
     *
     * @param TagServiceInterface $tagService
     * @param MemoServiceInterface $memoService
     * @param MemoTagServiceInterface $memoTagService
     */
    public function __construct(
        TagServiceInterface $tagService,
        MemoServiceInterface $memoService,
        MemoTagServiceInterface $memoTagService
    ) {
        $this->middleware('auth');

        $this->tagService = $tagService;
        $this->memoService = $memoService;
        $this->memoTagService = $memoTagService;
    }

    /**
     * Show the application dashboard.
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View
    {
        $authId = \Auth::id();
        $tags = $this->tagService->getUserTags($authId);

        return view('create', compact('tags'));
    }

    /**
     * Create new memo
     *
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

        } catch (Exception $e) {
            return back()->withInput()->withErrors(['message' => 'データの保存中にエラーが発生しました。']);
        }
    }

    /**
     * Edit screen for memo
     *
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

    /**
     * Update memo
     *
     * @param MemoRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(MemoRequest $request): RedirectResponse
    {
        try {
            $posts = $request->all();

            DB::transaction(function () use($posts) {
                $authId = \Auth::id();
                $memoId = $posts['memo_id'];

                $this->memoService->updateMemo($memoId, $posts['content']);
                $this->memoTagService->deleteMemoTag($memoId);

                $tagExists = $this->tagService->checkIfTagExists($authId, $posts['new_tag']);

                $this->tagService->attachTagsToMemo($posts, $memoId, $tagExists, $authId);
            });

            return redirect( route('home') );
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['message' => 'データの保存中にエラーが発生しました。']);
        }
    }

    /**
     * Destroy memo
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destory(Request $request): RedirectResponse
    {
        $posts = $request->all();
        $this->memoService->deleteMemo($posts['memo_id']);

        return redirect( route('home') );
    }
}
