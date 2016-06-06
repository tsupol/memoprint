<?php

namespace App\Console;

use App\Commands\GetMedia;
use App\Commands\StoreMedia;
use App\Commands\TestCommand;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Bus;
use Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
//        Commands\Inspire::class,
    ];


    protected function schedule(Schedule $schedule)
    {
        function sDiff(Carbon $t1, Carbon $t2) {
            $diff = $t1->diff($t2);
            return ($diff->s + $diff->m*60 + $diff->h*3600);
        }

        if(true) {
//            Bus::dispatch(new TestCommand('555666777'));
            Bus::dispatch(new GetMedia(192));

            $startTime = Carbon::now();
            $st = Carbon::now();
            $i = 0;
            while (sDiff($startTime, Carbon::now()) < 59 && $i < 15) {
                if ($i == 0 || sDiff($st, Carbon::now()) >= 6) {
                    $st = Carbon::now();
                    Log::notice("---> $i <--- " . sDiff($startTime, Carbon::now()));
//                    Bus::dispatch(new TestCommand('sssaaa'));
                    Bus::dispatch(new GetMedia());
                    $i++;
                } else {
                    usleep(1000000);
                }
            }

            $schedule->call(function () {
                Log::notice('--- storing media ---');
                Bus::dispatch(new StoreMedia());
            })->dailyAt('1:23')->sendOutputTo(storage_path('logs/output.log'));
        }
//        $schedule->command('inspire')
//                 ->hourly();
    }
}
