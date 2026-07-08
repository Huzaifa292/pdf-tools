<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanupTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete temporary files older than 1 hour to ensure user privacy.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directories = [
            storage_path('app/temp'),
            public_path('temp'),
        ];

        $now = time();
        $oneHourAgo = $now - 3600;
        $deletedCount = 0;

        foreach ($directories as $directory) {
            if (!File::exists($directory)) {
                continue;
            }

            $files = File::allFiles($directory);

            foreach ($files as $file) {
                if ($file->getMTime() < $oneHourAgo) {
                    File::delete($file->getPathname());
                    $deletedCount++;
                }
            }
        }

        $this->info("Cleaned up {$deletedCount} old temporary file(s).");
    }
}
