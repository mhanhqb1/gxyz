<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\AutoPublishPosts::class,
        Commands\CreateSiteMap::class,
        Commands\SourceDailyCrawler::class,
        Commands\UpdateYoutubeVideoDetail::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('Sexy:CreateSiteMap')->daily();
        $schedule->command('Sexy:SourceDailyCrawler')->hourly();
        $schedule->command('Sexy:UpdateYoutubeVideoDetail')->hourlyAt(15);
        $schedule->command('Sexy:AutoPublishPosts')->hourlyAt(45);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
