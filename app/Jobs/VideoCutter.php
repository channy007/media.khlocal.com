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
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

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
        Log::info("===== START CUTTING VIDEO =====");

        $shellFile = public_path() . '/shell_scripts/ffmpeg_cut.sh';

        $mediaSource = $this->data['mediaSource'];
        $fileStorage = $this->data['fileStorage'];
        $fileName = $fileStorage->path . '/' . $fileStorage->name . '.' . $fileStorage->extension;

        if (!file_exists($fileName)) {
            $mediaSource->update(['status' => MediaSourceStatus::CUT_ERROR, 'error' => 'File download not found!']);
            return;
        }

        $mediaSource->update(['status' => MediaSourceStatus::CUTTING]);
        $projectName = $this->getProjectName($mediaSource->project_id);

        $process = new Process(
            [
                'bash',
                $shellFile,
                $fileName,
                $mediaSource->transition,
                $mediaSource->seg_start,
                $mediaSource->seg_length,
                $mediaSource->seg_gap,
                $mediaSource->flip ?? "",
                $mediaSource->resolution,
                $projectName,
                $mediaSource->cut_off ?? 0,
                $mediaSource->cut_off_side ?? 0,
                $mediaSource->custom_crop ?? ""
            ]
        );

        $process->setTimeout(10800);
        $process->run();

        $this->updateMediaSource($mediaSource, $process);

        Log::info("===== END CUTTING VIDEO OUTPUT: " . $process->getOutput());
    }

    private function updateMediaSource($mediaSource, $process)
    {
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            Log::info("===== CUTTING VIDEO ERROR =====");
            $mediaSource->update(['status' => MediaSourceStatus::CUT_ERROR, 'error' => 'Error while cutting video!']);
            throw new ProcessFailedException($process);
            return;
        }
        $mediaSource->update(
            [
                'status' => MediaSourceStatus::CUTTED
            ]
        );
    }

    private function getProjectName($projectId)
    {
        $project = MediaProject::whereId($projectId)->first();

        return $project ? $project->name : "Media KHLocal";
    }
}
