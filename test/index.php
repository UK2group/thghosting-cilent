<?php
    require_once '../vendor/autoload.php';
    $_ENV = require './keys/env.php';
    try {
        $client = new ThgHosting\ThgHostingTest($_ENV['X-Api-Token-Test']);
        // $res = $client->request('GET', 'ssd-vps/locations/19/templates/custom/');
        /* BILLING */
        // $res = $client->getServiceDetails(1);
        /* DNS */
        // $res = $client->getDnsZones();
        // $res = $client->createDnsZone(domainName, ip);
        // $res = $client->getDnsZoneDetails(zoneId);
        // $res = $client->deleteDnsZone(zoneId);
        // $res = $client->addRecordToDnsZone(zoneId, type, host, content, ttl, service, protocol, port, weight, mxPriority);
        // $res = $client->updateDnsZoneRecord(zoneId, recordId, type, host, content, ttl, service, protocol, port, weight, mxPriority);
        // $res = $client->deleteDnsZoneRecord(zoneId, recordId);
        /* PRODUCTS */
        // $res = $client->getDatacenters();
        // $res = $client->getProductCategory();
        // $res = $client->getProductsInCategory(locationId, categoryId);
        // $res = $client->getProductDetails(locationId, categoryId, productId);
        // $res = $client->getCalculatedPriceWithTax(body);
        // $res = $client->getPaymentMethods();
        // $res = $client->submitOrderForProcessing(body);
        /* SERVER */
        // $res = $client->getServers();
        // $res = $client->getServerDetails(serverId);
        // $res = $client->getServerBandwidthGraph(serverId, periodStart, periodEnd);
        /* SSD VPS */
        // $res = $client->getSsdVpsPlans();
        // $res = $client->getSsdVpsLocations();
        // $res = $client->getSsdVpsCustomTemplates(locationId);
        // $res = $client->createSsdVpsServer(locationId, label, hostname, password, servicePlanId, osComponentCode, backups, billHourly, customTemplateId);
        // $res = $client->getSsdVpsOses(locationId);
        // $res = $client->getSsdVpsServers(locationId);
        // $res = $client->getSsdVpsServerDetails(locationId, serverId);
        // $res = $client->deleteSsdVpsServer(locationId, serverId);
        // $res = $client->getSsdVpsServerStatus(locationId, serverId);
        // $res = $client->powerOnSsdVpsServer(locationId, serverId);
        // $res = $client->powerOffSsdVpsServer(locationId, serverId);
        // $res = $client->rebootSsdVpsServer(locationId, serverId);
        // $res = $client->rebootSsdVpsServerInRecoveryMode(locationId, serverId);
        // $res = $client->resetSsdVpsServerPassword(locationId, serverId, newPassword);
        // $res = $client->getSsdVpsServerBackups(locationId, serverId);
        // $res = $client->addSsdVpsBackupNote(locationId, serverId, backupId, note);
        // $res = $client->deleteSsdVpsBackup(locationId, serverId, backupId);
        // $res = $client->restoreSsdVpsBackup(locationId, serverId, backupId);
        /* STATUSES */
        // $res = $client->getStatusUpdates();
        /* TICKET */
        // $res = $client->getTickets();
        // $res = $client->createTicket(body, subject, department, priority, attachments);
        // $res = $client->getTicketDepartments();
        // $res = $client->getTicketDetails(ticketId);
        // $res = $client->updateTicket(ticketId, priority, closeTicket);
        // $res = $client->addReplyToTicket(ticketId, body, attachments);
    } catch (\Throwable $e) {
        echo $e->getMessage() . " => " . $e->getFile() . " => " . $e->getLine();
    }

?>
