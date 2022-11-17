<?php

namespace App\Jobs;

use App\Models\MediaProject;
use App\Utils\enums\MediaSourceStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class Uploader implements ShouldQueue
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
        $fileName = $fileProperty['path'] . '/' . $fileProperty['cuttedFileName'] . $fileProperty['extension'];
        $mediaProject = MediaProject::whereId($mediaSource->project_id)->first();

        if (!$mediaProject) {
            return;
        }

        $shellFile = public_path() . '/shell_scripts/facebook_upload.sh';
        $thumb = $mediaSource->thumb ? public_path('storage') . '/' . $mediaSource->thumb : "";
        $mediaSource->update(['status' => MediaSourceStatus::UPLOADING]);

        $process = new Process(
            [
                'bash',
                $shellFile,
                $mediaProject->page_id,
                $mediaProject->long_access_token,
                $fileName,
                $mediaSource->source_text ?? "",
                $mediaSource->source_name ?? "",
                $thumb
            ]
        );

        $process->setTimeout(7200);
        $process->run();
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            $mediaSource->update(['status' => MediaSourceStatus::UPLOAD_ERROR]);

            throw new ProcessFailedException($process);
            return;
        }

        $mediaSource->update(['status' => MediaSourceStatus::UPLOADED]);
        Log::info("============ upload output: " . $process->getOutput());
    }
}
