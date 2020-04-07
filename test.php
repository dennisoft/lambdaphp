<?php

require_once 'vendor/autoload.php';
$sdk = new \Aws\Sdk();
$sqsClient = $sdk->createSQS(['region' => 'us-west-2', 'version' => 'latest']);

$result = $sqsClient->getQueueUrl(['QueueName' => "sqs-tutorial"]);
$queueUrl = $result->get('QueueUrl');

echo $queueUrl;

echo "\nSending messages\n";
$sqsClient->sendMessage(array(
    'QueueUrl' => $queueUrl,
    'MessageBody' => 'Hello Home!',
));

$sqsClient->sendMessage(array(
    'QueueUrl' => $queueUrl,
    'MessageBody' => 'Hello World!',
));

echo "Receiving messages\n";
$result = $sqsClient->receiveMessage([
    'AttributeNames' => ['All'],
    'MaxNumberOfMessages' => 10,
    'QueueUrl' => $queueUrl,
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
