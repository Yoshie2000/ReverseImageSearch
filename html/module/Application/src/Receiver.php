<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'patrick.leonhardt', 'Check24.de');
$channel = $connection->channel();

$channel->queue_declare("urlQueue", false, false, false, false);

echo " Waiting for urls. To exit press CTRL+C\n";

$callback = function ($data) {
    $url = $data->body;
    echo " Received ", $url, "\n";

    libxml_use_internal_errors(true);

    $document = new DOMDocument;
    $html = utf8_encode(file_get_contents($url));
    $document->loadHTML($html);

    $links = $document->getElementsByTagName("a");
    /** @var DOMElement $link */
    foreach ($links as $link) {
        $href = $link->getAttribute("href");
        echo $link->getNodePath(), ": ", $href, "\n";
    }

    echo "---\n";

    $images = $document->getElementsByTagName("img");
    /** @var DOMElement $image */
    foreach ($images as $image) {
        $imageSrc = $image->getAttribute("src");
        echo $image->getNodePath(), ": ", $imageSrc, "\n";
    }

};

$channel->basic_consume("urlQueue", "", false, true, false, false, $callback);

while ($channel->is_consuming()) {
    $channel->wait();
}