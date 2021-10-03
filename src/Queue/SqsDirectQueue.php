<?php


namespace LuckySeven\SqsDirectQueue\Queue;


use Illuminate\Queue\Jobs\SqsJob;
use Illuminate\Queue\SqsQueue;
use LuckySeven\SqsDirectQueue\Messages\SqsMessage;

class SqsDirectQueue extends SqsQueue
{
    /**
     * Pop the next job off of the queue.
     *
     * @param  string|null  $queue
     * @return \Illuminate\Contracts\Queue\Job|null
     */
    public function pop($queue = null)
    {

        $response = $this->sqs->receiveMessage([
            'QueueUrl' => $queue = $this->getQueue($queue),
            'AttributeNames' => ['ApproximateReceiveCount'],
        ]);
        if (! is_null($response['Messages']) && count($response['Messages']) > 0) {
            return new SqsJob(
                $this->container, $this->sqs, $this->createJobFromMessage($response['Messages'][0]),
                $this->connectionName, $queue
            );
        }
    }

    protected function createJobFromMessage(array $message) {
        if (self::isJob($message)) {
            return $message;
        }

        $jobClass = config('queue.connections.sqs_direct.job');

        $job = [
            "uuid" => $message['MessageId'],
            "displayName" => "App\\Jobs\\SqsMessageParser",
            "job" => "Illuminate\\Queue\\CallQueuedHandler@call",
            "maxTries" => null,
            "maxExceptions" => null,
            "failOnTimeout" => false,
            "backoff" => null,
            "timeout" => null,
            "retryUntil" => null,
            "data" => [
                "commandName" => "App\\Jobs\\SqsMessageParser",
                "command" => serialize(new $jobClass(new SqsMessage($message)))
            ]
        ];

        $message['Body'] = json_encode($job);

        return $message;
    }

    protected static function isJob(array $message) {
        $body = json_decode($message['Body'], true);
        if (!empty($body)
            && !empty($body['job'])
            && !empty($body['data'])
            && !empty($body['data']['commandName'])
            && !empty($body['data']['command'])
        ) {
            return true;
        }
        return false;
    }

}
