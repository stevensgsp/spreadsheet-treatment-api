<?php

namespace App\Jobs;

use App\Models\UploadedFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateUploadedFileStatus implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var \App\Models\UploadedFile
     */
    protected $uploadedFile;

    /**
     * @var string
     */
    protected $status;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(UploadedFile $uploadedFile, ?string $status = null)
    {
        $this->uploadedFile = $uploadedFile;

        $this->status = ! empty($status) ? $status : config('statuses.successful');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->uploadedFile->update(['status' => $this->status]);

        // rollback the import if the status is failed
        if ($this->status === config('statuses.failed')) {
            $this->uploadedFile->tenders()->delete();
        }
    }
}
