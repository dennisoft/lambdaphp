<?php

require 'vendor/autoload.php';

class SQSQS {
    const awsConfig = [
        'region'  => 'us-west-2',
        'version' => 'latest'
      ];

     /**
      * Delete message after the operation has completed.
      */
      public static function delete($queue, $handle) {
        $aws = new \Aws\Sdk(self::awsConfig);
        $sqs = $aws->createSQS();
        $result = $sqs->getQueueUrl(['QueueName' => $queue]);
        $qurl = $result->get('QueueUrl');

        self::$sqs->deleteMessage([
          'QueueUrl'      => $qurl,
          'ReceiptHandle' => $handle
        ]);
      }
}

$response = new SQSQS();
$response->delete("sqs-tutorial","uiegvdsklvbdv");
var_dump($response);

