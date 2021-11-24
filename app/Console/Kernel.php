<?php

namespace digipos\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DB;
use Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    //php /path/to/artisan schedule:run >> /dev/null 2>&1
    protected function schedule(Schedule $schedule) {
        $schedule->call(function () {
            $url = [
                        url('api/updateOrderStatus/3')
                    ];
            foreach($url as $u){
                file_get_contents($u);
            }
        })->hourly();

        // $schedule->call(function () {
        //     Artisan::command('queue:listen');
        // })->daily();
    }
}
