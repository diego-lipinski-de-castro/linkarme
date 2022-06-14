<?php

namespace App\Jobs;

use App\Models\Check;
use App\Models\Site;
use App\Services\CheckUrlService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckSite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $siteId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $siteId)
    {
        $this->siteId = $siteId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $site = Site::find($this->siteId);

        if(blank($site) || blank($site->url)) return;

        $result = (new CheckUrlService())->check($site->url);

        $check = new Check([
            'url' => $site->url,
            'code' => $result->code,
            'ssl' => $result->ssl,
        ]);

        $site->checks()->save($check);

        $site->update([
            'broken' => $result->code != 200,
            'ssl' => $result->ssl,
        ]);
    }
}
