<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Memo extends Model
{
    use HasFactory;

    public function getMyMemo(): Collection
    {
        $queryTag = request()->query('tag');

        $query = $this->buildBaseQuery();

        if ($queryTag) {
            $query = $this->applyTagFilter($query, $queryTag);
        }

        $query->orderByDesc('memos.updated_at');

        return $query->get();
    }

    private function buildBaseQuery()
    {
        return $this->newQuery()
            ->select('memos.*')
            ->where('memos.user_id', '=', auth()->id())
            ->whereNull('memos.deleted_at');
    }

    private function applyTagFilter($query, $queryTag)
    {
        return $query->leftJoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
            ->where('memo_tags.tag_id', '=', $queryTag)
            ->whereNull('memo_tags.deleted_at');
    }
}
