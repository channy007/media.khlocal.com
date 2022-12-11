<?php

namespace App\Jobs;

use App\Models\FileStorage;
use App\Utils\Enums\FileExtension;
use App\Utils\Enums\MediaSourceStatus;
use App\Utils\Enums\QueueName;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Str;

class VideoDownloader implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("===== START DOWNLOADING VIDEO =====");

        $shellFile = public_path() . '/shell_scripts/youtube_download.sh';

        $mediaSource = $this->data['mediaSource'];

        $fileStorage = $this->createFileStorage($mediaSource);
        $fileName = $fileStorage->path . '/' . $fileStorage->name . '.' . $fileStorage->extension;

        $mediaSource->update(['status' => MediaSourceStatus::DOWNLOADING]);

        $process = new Process(['bash', $shellFile, $mediaSource->source_url, $fileName]);
        $process->setTimeout(10800);
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            Log::info("===== ERROR DOWNLOADING VIDEO =====");
            $mediaSource->update(['status' => MediaSourceStatus::DOWNLOAD_ERROR, 'error' => 'File download error!']);
            throw new ProcessFailedException($process);
            return;
        }
        if (!file_exists($fileName)) {
            Log::info("===== ERROR DOWNLOADING VIDEO =====");
            $mediaSource->update(['status' => MediaSourceStatus::DOWNLOAD_ERROR, 'error' => 'File download error!']);
            return;
        }

        $mediaSource->update(
            [
                'status' => MediaSourceStatus::DOWNLOADED
            ]
        );

        dispatch(new VideoCutter(
            [
                'mediaSource' => $mediaSource,
                'fileStorage' => $fileStorage
            ]
        ))->onQueue(QueueName::VIDEO_CUTTER)->delay(5);

        Log::info("===== END DOWNLOADING VIDEO =====");
    }

    private function createFileStorage($mediaSource)
    {
        $fileStorage = FileStorage::whereMediaSourceId($mediaSource->id)->first();
        if (!$fileStorage) {
            $fileStorage = new FileStorage();
        }
        $fileStorage->media_source_id = $mediaSource->id;
        $fileStorage->name = strtolower(Str::random(45));
        $fileStorage->name_cutted = $fileStorage->name . '_cut';
        $fileStorage->extension = FileExtension::MP4;
        $fileStorage->path = public_path('storage') . '/videos';
        $fileStorage->save();
        return $fileStorage;
    }
}
