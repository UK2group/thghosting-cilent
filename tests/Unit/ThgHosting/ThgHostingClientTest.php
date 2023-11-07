<?php declare(strict_types=1);

namespace Unit\ThgHosting;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ThgHosting\Exceptions\ClientException;
use ThgHosting\Request\HttpRequestInterface;
use ThgHosting\ThgHostingClient;

final class ThgHostingClientTest extends TestCase
{
    private string $apiToken = '1234567890';
    private ?ThgHostingClient $client;
    private MockObject|HttpRequestInterface $requestMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->requestMock = $this->createMock(HttpRequestInterface::class);
        $this->client = new ThgHostingClient($this->apiToken);
        $this->client->setRequest($this->requestMock);
    }

    protected function tearDown(): void
    {
        $this->client = null;
        parent::tearDown();
    }

    public function testGetTimeout()
    {
        $this->assertEquals(60, $this->client->getTimeout());
    }

    public function testSetTimeout()
    {
        $this->client->setTimeout(30);
        $this->assertEquals(30, $this->client->getTimeout());
    }

    public function testSetTimeoutThrowsException()
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionMessage("Timeout can't be lower then zero");
        $this->expectExceptionCode(400);
        $this->client->setTimeout(-1);
    }

    public function test__construct()
    {
        $ref = new \ReflectionClass('ThgHosting\ThgHostingClient');

        $xApiTokenProperty = $ref->getProperty('xApiToken');
        $xApiTokenProperty->setAccessible(true);

        $timeoutProperty = $ref->getProperty('timeout');
        $timeoutProperty->setAccessible(true);

        $hostProperty = $ref->getProperty('host');
        $hostProperty->setAccessible(true);

        $client = new ThgHostingClient(
            'xxxxx',
            30,
            'https://example.com'
        );

        $this->assertEquals('xxxxx', $xApiTokenProperty->getValue($client));
        $this->assertEquals(30, $timeoutProperty->getValue($client));
        $this->assertEquals('https://example.com', $hostProperty->getValue($client));
    }

    public function testRequest()
    {
        $result = $this->client->request(ThgHostingClient::GET, 'some/endpoint');
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('info', $result);
    }

    public function testRequestWithDisallowedMethodThrowsException()
    {
        $this->expectException(ClientException::class);
        $this->expectExceptionMessageMatches('/Not allowed method used. Allowed:.*/');
        $this->expectExceptionCode(405);
        $this->client->request('OPTIONS', 'some/endpoint');
    }

    public function testGetSsdVpsPlans()
    {
        $this->requestMock->method('execute')->will($this->returnValue(
            '{
                    "status_code": 200,
                    "message": "List of SSD VPS Service Plans",
                    "data": [
                        {
                            "id": 2698,
                            "label": "1 Core \/ 0.5GB RAM \/ 25GB SSD \/ 2TB Bandwidth",
                            "price": "5.00"
                        },
                        {
                            "id": 2699,
                            "label": "2 Cores \/ 1GB RAM \/ 50GB SSD \/ 3TB Bandwidth",
                            "price": "10.00"
                        }
                    ]
                }'
        ));
        $result = $this->client->getSsdVpsPlans();
        $this->assertEquals('2698', $result['data']['data'][0]['id']);
    }

    public function testGetSsdVpsLocations()
    {
        $this->requestMock->method('execute')->will($this->returnValue(
            '{
                    "status_code": 200,
                    "message": "List of SSD VPS Locations",
                    "data": [
                        {
                            "id": 7,
                            "name": "New York City - A, US"
                        },
                        {
                            "id": 11,
                            "name": "Salt Lake City - H, US"
                        }
                    ]
                }'
        ));
        $result = $this->client->getSsdVpsLocations();
        $this->assertEquals('New York City - A, US', $result['data']['data'][0]['name']);
    }

    public function testgetSsdVpsCustomTemplates()
    {
        $this->requestMock->method('execute')->will($this->returnValue(
            '{
                      "statusCode": 200,
                      "message": "List of Custom OSes for SSD VPS",
                      "data": [
                        {
                          "label": "Custom Ubuntu",
                          "id": 1,
                          "price": 0,
                          "price_hourly": 0,
                          "is_hourly": true,
                          "is_monthly": true,
                          "products": [
                            "Ubuntu 18"
                          ]
                        },
                        {
                          "label": "Custom Windows",
                          "id": 2,
                          "price": 8.5,
                          "price_hourly": 0.01,
                          "is_hourly": true,
                          "is_monthly": true,
                          "products": [
                            "Windows 2012",
                            "cPanel Cloud"
                          ]
                        }
                      ]
                    }'
        ));
        $result = $this->client->getSsdVpsCustomTemplates(1);
        $this->assertEquals('Custom Ubuntu', $result['data']['data'][0]['label']);
    }

    public function testCreateSsdVpsServer()
    {
        $this->requestMock->method('execute')->will($this->returnValue(
            '{
                  "statusCode": 201,
                  "message": "Created SSD Virtual Machine",
                  "data": []
                }'
        ));
        $result = $this->client->createSsdVpsServer(
            1,
            'Test-VM',
            'test.example.com',
            'mySecretPassword',
            2698,
            'SSDVPSDEBIAN9',
            true,
            true
        );
        $this->assertEquals('Created SSD Virtual Machine', $result['data']['message']);
    }

    public function testGetSsdVpsOses()
    {
        $this->requestMock->method('execute')->will($this->returnValue(
            '{
                      "statusCode": 200,
                      "message": "Web Saleable List of Operating System in DataCenter: Staging London",
                      "data": [
                        {
                          "name": "Debian 9.0",
                          "component_code": "SSDVPSDEBIAN9",
                          "price": 0,
                          "price_hourly": 0,
                          "currency": "USD",
                          "min_disk_size": "30",
                          "operating_system_arch": "x64",
                          "min_memory_size": "1024"
                        },
                        {
                          "name": "Windows Server 2019",
                          "component_code": "SSDVPSWINDOWSSERV2019",
                          "price": 7.5,
                          "price_hourly": 0.01,
                          "currency": "USD",
                          "min_disk_size": "30",
                          "operating_system_arch": "x64",
                          "min_memory_size": "1024"
                        }
                      ]
                    }'
        ));
        $result = $this->client->getSsdVpsOses(1);
        $this->assertEquals('SSDVPSDEBIAN9', $result['data']['data'][0]['component_code']);
    }

    public function testGetSsdVpsServers()
    {
        $this->requestMock->method('execute')->will($this->returnValue(
            '{
                      "statusCode": 200,
                      "message": "List of Virtual Machines",
                      "data": [
                        {
                          "label": "test label",
                          "hostname": "examplehostname.com",
                          "ip_address": "1.9.1.11",
                          "backups": 0,
                          "hourly_billing": 0,
                          "location": {
                            "id": 20,
                            "name": "Staging London"
                          },
                          "id": 78,
                          "plan_id": 2699,
                          "service_id": 910
                        },
                        {
                          "label": "label test",
                          "hostname": "test.com",
                          "ip_address": "119.115.116.5",
                          "backups": 0,
                          "hourly_billing": 1,
                          "location": {
                            "id": 21,
                            "name": "Staging"
                          },
                          "id": 74,
                          "plan_id": 2698,
                          "service_id": 1034
                        }
                      ]
                    }'
        ));
        $result = $this->client->getSsdVpsServers(1);
        $this->assertEquals('examplehostname.com', $result['data']['data'][0]['hostname']);
    }

    public function testGetSsdVpsServerDetails()
    {
        $this->requestMock->method('execute')->will($this->returnValue(
            '{
                      "statusCode": 200,
                      "message": "Virtual Machine Details for Virtual Machine: 715 in Location: 21",
                      "data": {
                        "id": 509,
                        "hostname": "test.localdomain",
                        "memory": 512,
                        "cpus": 1,
                        "created_at": "2020-01-21T14:00:08.000+00:00",
                        "built": true,
                        "booted": false,
                        "label": "test label",
                        "operating_system": "linux",
                        "operating_system_distro": "ubuntu",
                        "note": null,
                        "suspended": false,
                        "domain": "localdomain",
                        "ip_addresses": [
                          {
                            "ip_address": {
                              "address": "1.9.8.5",
                              "broadcast": "1.9.8.255",
                              "network_address": "1.9.8.0",
                              "gateway": "1.9.8.1",
                              "created_at": "2020-01-21T14:00:08.000+00:00",
                              "rdnsEntry": ""
                            }
                          }
                        ],
                        "total_disk_size": 25,
                        "initial_root_password": "newerwer@!!@12343114",
                        "hourly_billing": 0,
                        "backups": 0
                      }
                    }'
        ));
        $result = $this->client->getSsdVpsServerDetails(1, 509);
        $this->assertEquals('test.localdomain', $result['data']['data']['hostname']);
    }

    public function testDeleteSsdVpsServer()
    {
        $this->requestMock->method('execute')->will($this->returnValue(
            '{
                      "statusCode": 200,
                      "message": "Virtual Machine Deleted",
                      "data": []
                    }'
        ));
        $result = $this->client->deleteSsdVpsServer(1, 509);
        $this->assertEquals(200, $result['data']['statusCode']);
    }

    public function testGetSsdVpsServerStatus()
    {
        $this->requestMock->method('execute')->will($this->returnValue(
            '{
                      "statusCode": 200,
                      "message": "Powered On",
                      "data": {
                        "status": 1
                      }
                   }'
        ));
        $result = $this->client->getSsdVpsServerStatus(1, 509);
        $this->assertEquals(1, $result['data']['data']['status']);
    }

    public function testCreateSshKey()
    {
        $this->requestMock->method('execute')->will($this->returnValue(
            '{
                "status_code": 200,
                "message": "SSH Key created for customer: 1",
                "data": {
                    "id": 1
                }
            }'
        ));
        $result = $this->client->createSshKey(
            'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQC7d0ZIxXqrxqoPIgN0B6yGByH6q0VW8RDrxI4yBR2iLr93xQ2z9usIH3hZhhSHjxOME8YB78HOkYtFXtPyz603eWFsO/jQAHn8A9GSlfDIEBpMjU0xhV5IVRP+IVcMqUN1ZjPDKnD5Rx2krgdDfcpWiaeL5VGLXO3wwQp8Y+nZNw== test@example.com',
            'My SSH Key'
        );
        $this->assertStringContainsString('SSH Key created for customer', $result['data']['message']);
    }

    public function testListSshKeys()
    {
        $this->requestMock->method('execute')->will($this->returnValue(
            '{
                      "statusCode": 200,
                      "message": "List of SSH Keys for the customer: 1",
                      "data": [
                        {
                            "id": 1,
                            "label": "My SSH Key",
                            "key": "ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQC7d0ZIxXqrxqoPIgN0B6yGByH6q0VW8RDrxI4yBR2iLr93xQ2z9usIH3hZhhSHjxOME8YB78HOkYtFXtPyz603eWFsO/jQAHn8A9GSlfDIEBpMjU0xhV5IVRP+IVcMqUN1ZjPDKnD5Rx2krgdDfcpWiaeL5VGLXO3wwQp8Y+nZNw== test@example.com",
                            "created_at": 1698660518,
                            "updated_at": 1698660957
                        }
                      ]
                   }'
        ));
        $result = $this->client->listSshKeys();
        $this->assertEquals(1, $result['data']['data'][0]['id']);
    }

    public function testGetSshKeyById()
    {
        $this->requestMock->method('execute')->will($this->returnValue(
            '{
                "statusCode": 200,
                "message": "Ssh Key with id 1",
                "data": {
                    "id": 1,
                    "label": "My SSH Key",
                    "key": "ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAAAgQC7d0ZIxXqrxqoPIgN0B6yGByH6q0VW8RDrxI4yBR2iLr93xQ2z9usIH3hZhhSHjxOME8YB78HOkYtFXtPyz603eWFsO/jQAHn8A9GSlfDIEBpMjU0xhV5IVRP+IVcMqUN1ZjPDKnD5Rx2krgdDfcpWiaeL5VGLXO3wwQp8Y+nZNw== test@example.com",
                    "created_at": 1698660518,
                    "updated_at": 1698660957
                }
            }'
        ));
        $result = $this->client->getSshKeyById(1);
        $this->assertEquals(1, $result['data']['data']['id']);
    }

    public function testUpdateSshKeyLabel()
    {
        $this->requestMock->method('execute')->will($this->returnValue(
            '{
                "status_code": 200,
                "message": "Label for SSH key was successfully updated"
            }'
        ));
        $result = $this->client->updateSshKeyLabel(
            1,
            'My SSH Key (updated)'
        );
        $this->assertEquals('Label for SSH key was successfully updated', $result['data']['message']);
    }

    public function testDeleteSshKey()
    {
        $this->requestMock->method('execute')->will($this->returnValue(
            '{
                "status_code": 200,
                "message": "Ssh Key with label My SSH Key was successfully removed"
            }'
        ));
        $result = $this->client->deleteSshKey(
            1
        );
        $this->assertStringContainsString('successfully removed', $result['data']['message']);
    }
}
