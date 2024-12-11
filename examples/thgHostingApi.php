<?php
/**
 * (c) The Hut Group 2001-2023, All Rights Reserved.
 *
 * This source code is the property of The Hut Group, registered address:
 *
 * 5th Floor, Voyager House, Chicago Avenue, Manchester Airport,
 * Manchester, England, M90 3DQ
 * @author Nha Nguyen <Nguyen-XuanN@thg.com>
 */

use ThgHosting\Exceptions\ClientException;

require_once __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

# Get your thg hosting API Key from THG-CP and add it into the ".env" file
$thgHostingXApiToken = $_ENV['X_API_TOKEN'] ?? null;
$apiUrl = $_ENV['API_URL'] ?? null;
$timeout = 60;
try {
    $thgHostingClient = new ThgHosting\ThgHostingClient($thgHostingXApiToken, $timeout, $apiUrl);
    $result = $thgHostingClient->getServers();
    print_r($result);
} catch (ClientException $e) {
    print_r($e);
}
