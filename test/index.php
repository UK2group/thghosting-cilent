<?php
    require_once '../vendor/autoload.php';
    try {
        echo "a";
        new Thg\ThgClient('a');
    } catch (\Throwable $e) {
        echo $e->getMessage() . " => " . $e->getFile() . " => " . $e->getLine();
    }

?>
