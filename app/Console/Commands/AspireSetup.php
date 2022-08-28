<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class AspireSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AspireSetup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'App has successfully setup';

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        Artisan::call('cache:clear');
        Artisan::call('key:generate');
        Artisan::call('migrate:fresh --seed');

        $this->info("APP is setup");
    }
}
