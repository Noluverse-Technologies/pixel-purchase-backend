<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class cleancache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nolu:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        /**
         * *Write a command which will run 'composer dumpautoload' bash command
         */
        // execute command
        exec("composer dumpautoload", $composerautoload);
        exec("php artisan config:cache", $configcache);
        exec("php artisan route:cache", $routecache);
        exec("php artisan view:cache", $viewcache);

        // print output from command
        $this->comment(implode(PHP_EOL, $composerautoload));
        $this->comment(implode(PHP_EOL, $configcache));
        $this->comment(implode(PHP_EOL, $routecache));
        $this->comment(implode(PHP_EOL, $viewcache));




        return 0;
    }
}
