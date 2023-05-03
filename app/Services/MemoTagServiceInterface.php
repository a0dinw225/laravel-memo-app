<?php
namespace App\Services;

interface MemoTagServiceInterface
{
    /**
     * Delete memo tag
     *
     * @param int $memoId
     * @return void
     */
    public function deleteMemoTag(int $memoId): void;
}
