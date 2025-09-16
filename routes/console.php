  <?php
  
  use Illuminate\Foundation\Inspiring;
  use Illuminate\Support\Facades\Artisan;
  use Illuminate\Support\Facades\Schedule;
  
  Artisan::command('inspire', function () {
      $this->comment(Inspiring::quote());
  })->purpose('Display an inspiring quote');
  
  Schedule::command('queue:work --stop-when-empty --sleep=3 --tries=3')
      ->everyMinute()
      ->withoutOverlapping()    // prevent overlapping runs
      ->runInBackground()       // run in background when possible
      ->name('scheduled-queue-work');