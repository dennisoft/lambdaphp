<?php

require 'vendor/autoload.php';

class SQSQS {
    const awsConfig = [
        'region'  => 'us-west-2',
        'version' => 'latest'
      ];

     /**
      * Initialize SQS.
      */
      public static function init() {
        if (!self::$sqs) {
          $aws = new \Aws\Sdk(self::awsConfig);
          self::$sqs = $aws->createSQS();
        }
      }

     /**
      * Delete message after the operation has completed.
      */
      public static function delete($queue, $handle) {
        self::init();
        $result = self::$sqs->getQueueUrl(['QueueName' => $queue]);
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

