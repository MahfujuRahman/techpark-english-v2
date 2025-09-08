<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;

class SubscriberJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:process-once';
    protected $description = 'Run queue worker only until jobs are done';
    /**
     * Execute the console command.
     */
    public function handle()
    {

        Artisan::call('queue:work', [
            '--queue' => 'subscriber_emails',
            '--tries' => 3,
            '--stop-when-empty' => true,
            '--timeout' => 120,
        ]);


        $this->info('All jobs processed and worker stopped.');
    }
}
