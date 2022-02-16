<?php
    require_once '../vendor/autoload.php';
    try {
        $client = new ThgHosting\ThgHostingClient('vLkSowsBe2IsF0UkGTyneKzBjEO8zssnuUkG2MeliD8ufIuhkkazPPbvSb076u2r');
        $res = $client->request('GET', 'ssd-vps/locations/19/templates/custom/');
        var_dump($res);
    } catch (\Throwable $e) {
        echo $e->getMessage() . " => " . $e->getFile() . " => " . $e->getLine();
    }

?>
