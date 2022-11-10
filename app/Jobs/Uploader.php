<?php

namespace App\Jobs;

use App\Models\MediaProject;
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

        $project = MediaProject::whereId($mediaSource->project_id)->first();

        if(!$project){
            return;
        }

        $shellFile = public_path() . '/shell_scripts/facebook_upload.sh';
        $process = new Process(
            [
                'bash',
                $shellFile,
                $project->page_id,
                $project->access_token,
                $fileProperty['cuttedFileName'].$fileProperty['extension'],
                $mediaSource->source_text,
                $mediaSource->source_name
            ]
        );

        $process->setTimeout(3600);
        $process->run();
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        Log::info("============ upload output: " . $process->getOutput());
    }
}
