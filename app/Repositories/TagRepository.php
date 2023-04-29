<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TagRepository
{
  public function __construct(Tag $tag)
  {
    $this->tag = $tag;
  }

  public function findUserTags($userId): JsonResponse
  {
    $tags =  $this->tag->where('user_id', '=', $userId)->whereNull('deleted_at')->orderBy('id', 'DESC')->get();
    return response()->json($tags, Response::HTTP_OK);
  }
}