<?php
    require_once '../vendor/autoload.php';
    $_ENV = require './keys/env.php';
    try {
        $client = new ThgHosting\ThgHostingClient($_ENV['X-Api-Token']);
        $res = $client->request('GET', 'ssd-vps/locations/19/templates/custom/');
        var_dump($res);
    } catch (\Throwable $e) {
        echo $e->getMessage() . " => " . $e->getFile() . " => " . $e->getLine();
    }

?>
