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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      // Reset demo
      $schedule->call(function () {

        if(env('APP_DEMO', false)) {
          \Artisan::call('migrate:refresh', [
            '--seed' => true,
            '--force' => true
          ]);

          $dir = storage_path('app/deals');
          if (\File::isDirectory($dir)) \File::deleteDirectory($dir);
          if (! \File::isDirectory($dir)) \File::makeDirectory($dir, 0777, true, true);

          $dir = public_path('attachments');
          if (\File::isDirectory($dir)) \File::deleteDirectory($dir);
          if (! \File::isDirectory($dir)) \File::makeDirectory($dir, 0777, true, true);

          $dir = public_path('qr');
          if (\File::isDirectory($dir)) \File::deleteDirectory($dir);
          if (! \File::isDirectory($dir)) \File::makeDirectory($dir, 0777, true, true);

          $dir = public_path('favicons');
          if (\File::isDirectory($dir)) \File::deleteDirectory($dir);
          if (! \File::isDirectory($dir)) \File::makeDirectory($dir, 0777, true, true);

          \Artisan::call('cache:clear');
          \Artisan::call('view:clear');
        }

      })->daily();
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
