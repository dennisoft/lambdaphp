<?php
$queueUrl = "https://sqs.us-west-2.amazonaws.com/393444192063/sqs-tutorial";

require_once 'vendor/autoload.php';
$sdk = new \Aws\Sdk();
$sqsClient = $sdk->createSqs(['region' => 'us-west-2', 'version' => '2012-11-05']);



echo "Sending message\n";
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
        'QueueUrl' => $queueUrl,
        'ReceiptHandle' => $message['ReceiptHandle']
    ]);
    echo "- Deleted: {$message['MessageId']})\n";
}
