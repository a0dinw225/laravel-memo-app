<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\MemoTag;

class DeleteUnusedMemoTags extends Command
{
    protected $signature = 'memotags:delete-unused';

    protected $description = 'Delete unused memo_tags from the memo_tags table';

    /**
     * This command deletes memo tags from the memo_tags table where the 'deleted_at' field is not null.
     *
     * @return void
     */
    public function handle(): void
    {
        $unusedMemoTags = MemoTag::onlyTrashed()->get();
        $count = $unusedMemoTags->count();

        if ($count > 0) {
            MemoTag::onlyTrashed()->forceDelete();
            $this->info($count . ' unused memo tags have been deleted.');
        } else {
            $this->info('No unused memo tags found.');
        }
    }
}
