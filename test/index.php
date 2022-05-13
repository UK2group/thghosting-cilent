<?php
    declare(strict_types=1);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    require_once '../vendor/autoload.php';
    use ThgHosting\ThgHostingTest as ThgHostingTest;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Test</title>
        <style media="screen">
            * {
                padding: 0;
                margin: 0;
            }

            .preview {
                background-color: #2d3436;
                padding: 20px;
                color: #dfe6e9;
                height: calc(100vh - 40px);
                overflow: auto;
                width: calc(100% - 40px);
            }

            .preview ul, .preview ol {
                padding: 10px 30px;
                padding-right: 0;
                list-style-position: inside;
            }

            .preview ul li .info_key, .preview ol li .info_key {
                min-width: 200px;
                display: inline-block;
                color: #b2bec3;
            }

            .preview ul li .info_type, .preview ol li .info_type {
                min-width: 60px;
                display: inline-block;
                text-align: center;
                color: #55efc4;
            }

            .preview ul li > ul li, .preview ol li > ol li {
                border-left: 2px solid rgba(178, 190, 195, .25);
                padding-left: 5px;
                background-color: rgba(178, 190, 195, .05);
            }

            .preview ul li > ul li:first-child, .preview ol li > ol li:first-child {
                padding-top: 5px;
            }

            .preview ul li > ul li:last-of-type, .preview ol li > ol li:last-of-type {
                padding-bottom: 5px;
            }
        </style>
    </head>
    <body>
        <pre class="preview"><?php
            try {
                $client = new ThgHostingTest(ThgHostingTest::LOCAL_ENV);
                /* Test Preview */
                // echo $client->generatePreview([
                //     "test1" => [
                //         "test2" => [
                //             "test3" => [
                //                 "test4" => "a",
                //                 "test5" => "b",
                //                 "test6" => "c",
                //             ]
                //         ],
                //         "test7" => "g",
                //         "test8" => [
                //             "test3" => [
                //                 "test4" => "a",
                //                 "test5" => "b",
                //                 "test6" => "c",
                //             ]
                //         ]
                //     ]
                // ]);

                // $res = $client->request('GET', 'ssd-vps/locations/19/templates/custom/');
                /* BILLING */
                // Using client 2,1734
                // $res = $client->testMethod('getServiceDetails', [782]);
                /* DNS */
                // $res = $client->testMethod('getDnsZones');

                // $res = $client->testMethod('createDnsZone', ['domaintesthostingclient3.com', '10.0.0.1']);
                // $res = $client->testMethod('getDnsZoneDetails', [197]);
                // $res = $client->testMethod('deleteDnsZone', [196]);
                // $res = $client->testMethod('addRecordToDnsZone', [195, 'SRV', 'host.com', 'content', 61, 'symbolicName', 'UDP', 12, 2, true]); // Doesn't work for now
                // $res = $client->updateDnsZoneRecord(zoneId, recordId, type, host, content, ttl, service, protocol, port, weight, mxPriority); // Doesn't work for now
                // $res = $client->deleteDnsZoneRecord(zoneId, recordId); // Doesn't work for now
                /* PRODUCTS */
                // $res = $client->testMethod('getDatacenters');
                // $res = $client->testMethod('getProductCategory');
                // $res = $client->testMethod('getProductsInCategory',[8, 2]);
                // $res = $client->testMethod('getProductDetails', [8, 2, 40]);
                // $body = [
                //     [
                //         "product_id" => 34,
                //         "quantity" => 1,
                //         "price" => 354.9,
                //         "datacenter_id" => 6,
                //         "duration_id" => 2,
                //         "addons" => [
                //             [
                //                 "addon_id"=> 4,
                //                 "selected_option"=> 2,
                //                 "price"=> 10
                //             ]
                //         ]
                //     ]
                // ];
                // $res = $client->testMethod('getCalculatedPriceWithTax', [$body]);
                // $res = $client->testMethod('getPaymentMethods');
                // $body = [
                //     "order"=> [
                //         [
                //           "category_id"=> 2,
                //           "product_id" => 34,
                //           "quantity" => 1,
                //           "price" => 354.9,
                //           "datacenter_id" => 6,
                //           "duration_id" => 2,
                //           "addons" => [],
                //           "sales_tax"=> 0
                //         ]
                //     ],
                //     "contact_data" => [
                //     	"address" => "816 Kings Road",
                //     	"city"  => "ware",
                //     	"company" => "Staging Test Company",
                //     	"country" => "GB",
                //     	"county" => "City of London",
                //     	"email" => "testnha@tasdasest.de",
                //     	"first_name" => "John",
                //     	"last_name" => "Doe",
                //     	"phone" => "+44 20 7234 3456",
                //     	"postcode" => "WC76 8RJ"
                //     ],
                //     "paymentMethodId" => 1089
                // ];
                // $res = $client->testMethod('submitOrderForProcessing', [$body]);
                /* SERVER */
                // $res = $client->testMethod('getServers');
                // $res = $client->testMethod('getServerDetails', [25]);
                // $res = $client->testMethod('getServerBandwidthGraph', [13140, '2022-01-04', '2022-02-04']);
                /* SSD VPS */
                // $res = $client->testMethod('getSsdVpsPlans');
                // $res = $client->testMethod('getSsdVpsLocations');
                // $res = $client->testMethod('getSsdVpsCustomTemplates', [21]);
                // $res = $client->testMethod('createSsdVpsServer',
                //     [
                //         20,
                //         'label',
                //         'hostname.pl',
                //         'password',
                //         2698,
                //         null,
                //         true,
                //         false,
                //         211
                //     ]
                // );
                // $res = $client->testMethod('getSsdVpsOses', [20]);
                // $res = $client->testMethod('getSsdVpsServers', [20]);
                // $res = $client->testMethod('getSsdVpsServerDetails', [20, 509]); // Test on staging
                // $res = $client->testMethod('deleteSsdVpsServer', [20, 509]); // Staging
                // $res = $client->testMethod('getSsdVpsServerStatus', [20, 509]); // Staging
                // $res = $client->testMethod('powerOnSsdVpsServer', [20, 509]);
                // $res = $client->testMethod('powerOffSsdVpsServer', [20, 509]);
                // $res = $client->testMethod('rebootSsdVpsServer', [20, 509]);
                // $res = $client->testMethod('rebootSsdVpsServerInRecoveryMode', [20, 509]);
                // $res = $client->testMethod('resetSsdVpsServerPassword', [20, 509]);
                // $res = $client->testMethod('getSsdVpsServerBackups', [20, 509]);
                // $res = $client->testMethod('addSsdVpsBackupNote', [20, 509, 2329, 'Test note']);
                // $res = $client->testMethod('deleteSsdVpsBackup', [20, 509, 2353]);
                // $res = $client->testMethod('restoreSsdVpsBackup', [20, 509, 2544]);
                /* STATUSES */
                // $res = $client->testMethod('getStatusUpdates');
                /* TICKET */
                // $res = $client->testMethod('getTickets');
                // $res = $client->testMethod('getTicketDepartments');
                // $file1 = '/home/michal/Desktop/current7.png';
                // $file2 = '/home/michal/Desktop/current6.png';
                // $res = $client->testMethod('createTicket', [
                //     'Body Open API client test',
                //     'Open API client test',
                //     1,
                //     3,
                //     [
                //         [
                //             "mime" => mime_content_type($file1),
                //             "file" => base64_encode(file_get_contents($file1)),
                //             "name" => basename($file1)
                //         ],
                //         [
                //             "mime" => mime_content_type($file2),
                //             "file" => base64_encode(file_get_contents($file2)),
                //             "name" => basename($file2)
                //         ],
                //
                //     ]
                // ]); // 3765
                // $res = $client->testMethod('getTicketDetails', [3765]);
                // $res = $client->testMethod('updateTicket', [3765, 2]);
                // $res = $client->testMethod('addReplyToTicket', [
                //     3765,
                //     'Reply to Open API ticket',
                //     ['/home/michal/Desktop/current7.png', '/home/michal/Desktop/current6.png']
                // ]);
                // $res = $client->testMethod('getBillingServiceUpgrades', [
                //     7807,
                //     'M1-18'
                // ]);
                // $res = $client->testMethod('upgradesService', [
                //     7807,
                //     'M1-18',
                //     'BAND',
                //     'BAND-100TB',
                //     'Details',
                //     2
                // ]);
                // $res = $client->testMethod('upgradesService', [
                //     7807,
                //     'M1-18',
                //     'IP-REQ',
                //     'IP-REQUEST',
                //     'Details',
                //     1,
                //     [
                //         "terminal" => 1
                //     ]
                // ]);
                // $res = $client->testMethod('getUserList');
                // $res = $client->testMethod('getUserPermissions');
                $res = $client->testMethod('getUser', ['asd@asd.pl']);
                if (isset($res)) {
                    echo $res;
                } else {
                    echo "No method tested.";
                }
            } catch (\Throwable $e) {
                echo $e->getMessage() . " => " . $e->getFile() . " => " . $e->getLine() . PHP_EOL;
            }
        ?>
        </pre>
    </body>
</html>
