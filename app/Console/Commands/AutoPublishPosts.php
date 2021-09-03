<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;

class AutoPublishPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Sexy:AutoPublishPosts';

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
        \Log::info ("Auto publish posts: ".date('Y-m-d H:i:s'));
        Post::autoPublishPosts(7);
    }
}
