<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PurgeOldTasks extends Command
{
    protected $signature = 'tasks:purge';
    
    protected $description = 'Purge old tasks from the trash';

    public function handle()
    { 
        DB::table('tasks')->where('deleted_at', '<=', now()->subDays(30))->forceDelete();
        
        $this->info('Old tasks purged successfully!');
    }
}