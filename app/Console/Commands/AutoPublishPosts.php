<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Stichoza\GoogleTranslate\GoogleTranslate;

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
        $tr = new GoogleTranslate();
        echo $tr->translate("20190601_六一约草寂寞人妻约炮公众号饼干手册完整版成人抖音快手APP看涧介"); die();
        Post::autoPublishPosts(7);
    }
}
