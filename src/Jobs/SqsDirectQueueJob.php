<?php

namespace LuckySeven\SqsDirectQueue\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use LuckySeven\SqsDirectQueue\Messages\SqsMessage;

class SqsDirectQueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected SqsMessage $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SqsMessage $message)
    {
        $this->message = $message;
    }
}
