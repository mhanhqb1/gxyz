<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;

class UpdateYoutubeVideoDetail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Sexy:UpdateYoutubeVideoDetail';

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
        \Log::info ("Auto update video detail: ".date('Y-m-d H:i:s'));
        Post::Youtube_updateVideoDetail();
    }
}
