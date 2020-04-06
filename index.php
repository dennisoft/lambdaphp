<?php
require 'vendor/autoload.php';

use Aws\Sqs\SqsClient;
use Aws\Exception\AwsException;

$queueUrl = "https://sqs.us-west-2.amazonaws.com/393444192063/sqs-tutorial";


$client = new SqsClient([
    'profile' => 'default',
    'region' => 'us-west-2',
    'version' => '2012-11-05'
]);

try {
    $result = $client->receiveMessage(array(
        'AttributeNames' => ['SentTimestamp'],
        'MessageAttributeNames' => ['All'],
        'QueueUrl' => $queueUrl, // REQUIRED
        'WaitTimeSeconds' => 0,
    ));
    $a = $result->get('Messages');
    for ($x = 0; $x<=count($a);$x++) {
        if (!empty($result->get('Messages'))) {
            var_dump($result->get('Messages')[0]);
            $result = $client->deleteMessage([
                'QueueUrl' => $queueUrl, // REQUIRED
                'ReceiptHandle' => $result->get('Messages')[0]['ReceiptHandle'] // REQUIRED
            ]);
        } else {
            echo "No messages in queue. \n";
        }
    }
} catch (AwsException $e) {
    // output error message if fails
    error_log($e->getMessage());
    echo $e->getMessage();
}
