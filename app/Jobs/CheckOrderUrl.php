<?php

namespace App\Jobs;

use App\Models\Check;
use App\Models\Order;
use App\Services\CheckUrlService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckOrderUrl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = Order::find($this->orderId);

        if(blank($order) || blank($order->url)) return;

        $result = (new CheckUrlService())->check($order->url);

        Check::updateOrCreate([
            'url' => $order->url,
        ],[
            'code' => $result->code,
            'ssl' => $result->ssl,
        ]);

        $order->update([
            'broken' => $result->code != 200,
            'ssl' => $result->ssl,
        ]);

        // $order->site->update([
        //     'broken' => $result->code == 200,
        //     'ssl' => $result->ssl,
        // ]);
    }
}
