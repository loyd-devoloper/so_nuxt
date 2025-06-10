<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleBackupsCommand extends Command
{
    protected $signature = 'backups:schedule';
    protected $description = 'Schedule backup tasks';

    public function handle(Schedule $schedule)
    {
        $schedule->command('backup:run')->dailyAt('02:00');
        $schedule->command('backup:clean')->dailyAt('03:00');
        $schedule->command('backup:monitor')->dailyAt('04:00');

        $this->info('Backup tasks scheduled successfully!');
    }
}
