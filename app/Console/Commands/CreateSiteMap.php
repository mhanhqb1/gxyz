<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CreateSiteMap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Sexy:CreateSiteMap';

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
        $sitemap = \App::make('sitemap');
        $sitemap->add(url('/'), Carbon::now(), '1.0', 'daily');
        $sitemap->add(route('home.videos'), Carbon::now(), '1.0', 'daily');
        $sitemap->add(route('home.18videos'), Carbon::now(), '1.0', 'daily');
        $sitemap->add(route('home.images'), Carbon::now(), '1.0', 'daily');
        $sitemap->add(route('home.images18'), Carbon::now(), '1.0', 'daily');
        $posts = DB::table('posts')
            ->where('status', 1)
            ->where('type', 1)
            ->orderBy('created_at', 'desc')
            ->get();
        foreach ($posts as $post) {
            $sitemap->add(route('home.videoDetail', ['slug' => $post->slug, 'id' => $post->id]), $post->updated_at, '0.8', 'daily');
        }
        $sitemap->store('xml', 'sitemap');
        if (\File::exists(public_path() . '/sitemap.xml')) {
            chmod(public_path() . '/sitemap.xml', 0777);
        }
    }
}
