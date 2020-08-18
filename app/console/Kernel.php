<?php

namespace app\console;
defined('IN_IA') or define('IN_IA',true);

use app\console\Commands\WriteFrame;
use app\framework\Foundation\Bootstrap\SetRequestForConsole;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use app\console\Commands\CourseReminder;
use app\console\Commands\NotPaidOrderNotice;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */

    protected $commands = [
        'app\console\Commands\UpdateVersion',
        'app\console\Commands\RepairWithdraw',
        'app\console\Commands\Test',
        'app\console\Commands\WechatOpen',
        'app\console\Commands\RebuildDb',
        'app\console\Commands\MigrateHFLevelExcelData',
        'app\console\Commands\MigrateMemberDistributor',
        'app\console\Commands\UpdateInviteCode',
        WriteFrame::class,
        CourseReminder::class,
        NotPaidOrderNotice::class,
    ];
    /**
     * The bootstrap classes for the application.
     *
     * @var array
     */
    protected $bootstrappers = [
        'Illuminate\Foundation\Bootstrap\DetectEnvironment',
        'Illuminate\Foundation\Bootstrap\LoadConfiguration',
        'Illuminate\Foundation\Bootstrap\ConfigureLogging',
        'Illuminate\Foundation\Bootstrap\HandleExceptions',
        'Illuminate\Foundation\Bootstrap\RegisterFacades',
        SetRequestForConsole::class,
        'Illuminate\Foundation\Bootstrap\RegisterProviders',
        'Illuminate\Foundation\Bootstrap\BootProviders',
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

        // 每5分钟执行新课程视频发布提醒
        $schedule->command('command:coursereminder')
            ->withoutOverlapping()
            ->everyFiveMinutes();

        // 每5分钟执行待支付订单提醒
        $schedule->command('command:notpaidordernotice')
            ->withoutOverlapping()
            ->everyFiveMinutes();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
