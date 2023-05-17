<?php

namespace App\Http\Controllers\Api\v1;

use \Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\TagServiceInterface;

class TagController extends Controller
{
    /** @var TagServiceInterface */
    protected $tagService;

    /**
     * TagController constructor.
     *
     * @param TagServiceInterface $tagService
     */
    
    public function __construct(TagServiceInterface $tagService)
    {
        $this->middleware('auth:api');
        $this->tagService = $tagService;
    }

    /**
     * Delete tag
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        try {
            $tag = $this->tagService->getTagById($request->id);
            $this->tagService->deleteTag($tag['id']);

            return response()->json([
                'message' => 'タグを削除しました',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'タグの削除に失敗しました',
            ], 500);
        }
    }
}

