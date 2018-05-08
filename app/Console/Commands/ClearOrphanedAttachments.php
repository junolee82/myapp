<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ClearOrphanedAttachments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'my:coa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '불필요한 첨부 파일 청소';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $orphaned = \App\Attachment::whereNull('article_id')
            ->where('created_at', '<', \Carbon\Carbon::now()->subWeek())->get();
        
        foreach ($orphaned as $attachment) {
            $path = attachments_path($attachment->filename);
            \File::delete($path);
            $attachment->delete();
            $this->line('삭제됨: '.$path);
        }

        $this->info('불필요한 파일을 모두 청소했습니다.');

        return;
    }
}
