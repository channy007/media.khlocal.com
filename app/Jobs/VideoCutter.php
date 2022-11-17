<?php

namespace App\Jobs;

use App\Models\MediaProject;
use App\Utils\enums\MediaSourceStatus;
use App\Utils\enums\QueueName;
use App\Utils\enums\VideoFlip;
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
        $fileName = $fileProperty['path'] . '/' . $fileProperty['originalName'] . $fileProperty['extension'];
        $shellFile = public_path() . '/shell_scripts/ffmpeg_cut.sh';
        $mediaSource->update(['status' => MediaSourceStatus::CUTTING]);

        $project = MediaProject::whereId($mediaSource->project_id)->first();

        if ($project) {
            $projectName = $project->name;
        }else{
            $projectName="Media KHLocal";
        }

        $process = new Process(
            [
                'bash',
                $shellFile,
                $fileName, 
                $mediaSource->transition,
                $mediaSource->seg_start,
                $mediaSource->seg_length,
                $mediaSource->seg_gap,
                $mediaSource->flip??"",
                $projectName
            ]
        );

        $process->setTimeout(7200);
        $process->run();
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            $mediaSource->update(['status' => MediaSourceStatus::CUT_ERROR]);
            throw new ProcessFailedException($process);
            return;
        }
        $mediaSource->update(['status' => MediaSourceStatus::CUT]);

        dispatch(new Uploader([
            'mediaSource' => $mediaSource,
            'fileProperty' => $fileProperty
        ]))->onQueue(QueueName::UPLOADER);

        Log::info("============ video cutter output: " . $process->getOutput());
    }
}
