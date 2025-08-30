<?php

namespace Modules\Sales\Jobs\V1;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;

class ResetProductQuantity 
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Collection $orderDetails) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->orderDetails->each(function ($details) {
            $details->product()->increment('quantity', $details->quantity);
        });
    }
}
