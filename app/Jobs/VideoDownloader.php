<?php

namespace App\Jobs;

use App\Utils\enums\MediaSourceStatus;
use App\Utils\enums\QueueName;
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
        $mediaSource = $this->data['mediaSource'];
        $fileProperty = $this->prepareFileProperties($mediaSource);

        $shellFile = public_path() . '/shell_scripts/youtube_download.sh';

        $fileName = $fileProperty['path'] .'/'. $fileProperty['originalName'] . $fileProperty['extension'];
        $mediaSource->update(['status' => MediaSourceStatus::DOWNLOADING]);

        $process = new Process(['bash', $shellFile, $mediaSource->source_url, $fileName]);
        $process->setTimeout(7200);
        $process->run();
        
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            $mediaSource->update(['status' => MediaSourceStatus::DOWNLOAD_ERROR]);
            throw new ProcessFailedException($process);
            return;
        }
        Log::info("============ download output: " . $process->getOutput());
        $mediaSource->update(['status' => MediaSourceStatus::DOWNLOADED]);
        dispatch(new VideoCutter(
            [
                'mediaSource' => $mediaSource,
                'fileProperty' => $fileProperty
            ]
        ))->onQueue(QueueName::VIDEO_CUTTER);
    }

    private function prepareFileProperties($mediaSource)
    {
        $fileProperty = [
            'path' => public_path('storage').'/videos',
            'originalName' => Str::slug($mediaSource->source_name),
            'extension' => '.mp4',
            'cuttedFileName' => Str::slug($mediaSource->source_name) . '_cut'
        ];

        return $fileProperty;
    }
}
