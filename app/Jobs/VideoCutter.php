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

class VideoCutter implements ShouldQueue
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
        $fileProperty = $this->data['fileProperty'];
        $fileName = $fileProperty['path'] .'/'. $fileProperty['originalName'] . $fileProperty['extension'];
        $shellFile = public_path() . '/shell_scripts/ffmpeg_cut.sh';

        $process = new Process(
            [
                'bash',
                $shellFile,
                $fileName, $mediaSource->transition,
                $mediaSource->seg_start,
                $mediaSource->seg_length,
                $mediaSource->seg_gap
            ]
        );

        $process->setTimeout(3600);
        $process->run();
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
            return;
        }

        dispatch(new Uploader([
            'mediaSource' => $mediaSource,
            'fileProperty' => $fileProperty
        ]));

        Log::info("============ video cutter output: " . $process->getOutput());
    }
}
