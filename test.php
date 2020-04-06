<?php
$queueUrl = "https://sqs.us-west-2.amazonaws.com/393444192063/sqs-tutorial";

require_once 'vendor/autoload.php';
$sdk = new \Aws\Sdk();
$sqsClient = $sdk->createSQS(['region' => 'us-west-2', 'version' => '2012-11-05']);

 $result = $sqsClient->getQueueUrl(['QueueName' => "sqs-tutorial"]);
 $qurl = $result->get('QueueUrl');

echo "Sending message\n";
$sqsClient->sendMessage(array(
    'QueueUrl' => $qurl,
    'MessageBody' => 'Hello Home!',
));

echo "Receiving messages\n";
$result = $sqsClient->receiveMessage([
    'AttributeNames' => ['All'],
    'MaxNumberOfMessages' => 10,
    'QueueUrl' => $qurl,
]);
foreach ($result->search('Messages[]') as $message) {
    echo "- Message: {$message['Body']} (Id: {$message['MessageId']})\n";
}

echo "Deleting messages\n";
foreach ($result->search('Messages[]') as $message) {
    $sqsClient->deleteMessage([
        'QueueUrl' => $qurl,
        'ReceiptHandle' => $message['ReceiptHandle']
    ]);
    echo "- Deleted: {$message['MessageId']})\n";
}
