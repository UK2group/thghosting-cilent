<?php
    require_once '../vendor/autoload.php';
    try {
        echo "a";
        $client = new ThgHosting\ThgHostingClient('a');

    } catch (\Throwable $e) {
        echo $e->getMessage() . " => " . $e->getFile() . " => " . $e->getLine();
    }

?>
