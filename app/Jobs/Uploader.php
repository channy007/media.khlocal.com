<?php

namespace App\Jobs;

use App\Models\MediaProject;
use App\Utils\Enums\MediaSourceStatus;
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
        $fileStorage = $this->data['fileStorage'];
        $fileName = $fileStorage->path . '/' . $fileStorage->name_cutted . '.' . $fileStorage->extension;
        $mediaProject = MediaProject::whereId($mediaSource->project_id)->first();

        if (!$mediaProject) {
            $mediaSource->update(['status' => MediaSourceStatus::UPLOAD_ERROR, 'error' => 'Media Project not found!']);
            return;
        }

        if (!file_exists($fileName)) {
            $mediaSource->update(['status' => MediaSourceStatus::UPLOAD_ERROR, 'error' => 'File cut not found!']);
            return;
        }

        $shellFile = public_path() . '/shell_scripts/resumable_upload_fb.sh';
        $thumb = $mediaSource->thumb ? public_path('storage') . '/' . $mediaSource->thumb : "";
        $mediaSource->update(['status' => MediaSourceStatus::UPLOADING]);

        $process = new Process(
            [
                'bash',
                $shellFile,
                $mediaProject->page_id,
                $mediaProject->long_page_access_token,
                $fileName,
                $mediaSource->source_name ?? "",
                $mediaSource->source_text ?? "",
                $thumb
            ]
        );

        $process->setTimeout(10800);
        $process->run();

        $this->updateMediaSource($mediaSource, $process);
        Log::info("============ upload output: " . $process->getOutput());
    }

    private function updateMediaSource($mediaSource, $process)
    {
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            $mediaSource->update(['status' => MediaSourceStatus::UPLOAD_ERROR, 'error' => 'Error while uploading!']);

            throw new ProcessFailedException($process);
            return;
        }

        $mediaSource->update(['status' => MediaSourceStatus::UPLOADED]);
    }
}
