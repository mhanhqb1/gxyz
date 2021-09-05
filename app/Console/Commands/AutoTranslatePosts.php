<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Providers\CommonServiceProvider;
use App\Models\Post;
use Exception;

class AutoTranslatePosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Sexy:AutoTranslatePosts';

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
        \Log::info ("Auto translate posts: ".date('Y-m-d H:i:s'));
        $tr = new GoogleTranslate();
        $data = Post::where('status', -2)->where('source_type', 'xiuren')->orderBy('id', 'desc')->get();
        foreach ($data as $k => $v) {
            try {
                $v->title = $tr->translate($v->title);
                $v->tags = $tr->translate($v->tags);
                $v->slug = CommonServiceProvider::convertURL($v->title);
                $v->status = -3;
                $v->save();
                echo $k.' - '.$v->title.PHP_EOL;
            } catch (Exception $e) {
                break;
            }
        }
    }
}
