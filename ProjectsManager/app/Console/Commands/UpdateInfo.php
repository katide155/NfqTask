<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\StudentController;

class UpdateInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateinfo:statuspage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates info in status page every 10 sec.';

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
        StudentController::loadDataFromApi();
    }
}
