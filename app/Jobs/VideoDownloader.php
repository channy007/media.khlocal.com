<?php

namespace App\Jobs;

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
        $process = new Process(['bash', $shellFile, $mediaSource->source_url, $fileName]);
        $process->setTimeout(3600);
        $process->run();
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        Log::info("============ download output: " . $process->getOutput());

        dispatch(new VideoCutter(
            [
                'mediaSource' => $mediaSource,
                'fileProperty' => $fileProperty
            ]
        ));
    }

    private function prepareFileProperties($mediaSource)
    {
        $fileProperty = [
            'path' => '/var/www/share',
            'originalName' => Str::slug($mediaSource->source_name),
            'extension' => '.mp4',
            'cuttedFileName' => Str::slug($mediaSource->source_name) . '_cut'
        ];

        return $fileProperty;
    }
}
