<?php /** @noinspection PhpUnused */
/** @noinspection PhpFullyQualifiedNameUsageInspection */
declare(strict_types=1);
/**
 * (c) The Hut Group 2001-2023, All Rights Reserved.
 *
 * This source code is the property of The Hut Group, registered address:
 *
 * 5th Floor, Voyager House, Chicago Avenue, Manchester Airport,
 * Manchester, England, M90 3DQ
 */
namespace ThgHosting;

use ThgHosting\Exceptions\ClientException;
use ThgHosting\Request\CurlRequest;
use ThgHosting\Request\HttpRequestInterface;

/**
 * THG Hosting API Client
 */
class ThgHostingClient
{
    const GET              = 'GET';
    const POST             = 'POST';
    const DELETE           = 'DELETE';
    const PUT              = 'PUT';
    const PATCH            = 'PATCH';
    const CONTENT_JSON     = 'application/json';
    const TICKETS_ENDPOINT = 'tickets/';

    private int   $timeout        = 60;
    private array $allowedMethods = [
        self::GET    => [CURLOPT_CUSTOMREQUEST, self::GET],
        self::POST   => [CURLOPT_CUSTOMREQUEST, self::POST],
        self::DELETE => [CURLOPT_CUSTOMREQUEST, self::DELETE],
        self::PUT    => [CURLOPT_CUSTOMREQUEST, self::PUT],
        self::PATCH  => [CURLOPT_CUSTOMREQUEST, self::PATCH],
    ];

    private string $xApiToken;

    protected string $host = 'https://api.ingenuitycloudservices.com/rest-api/';

    protected HttpRequestInterface $request;

    /**
     * @param string      $xApiToken X-Api-Token required for any requests to THG Hosting Open API
     * @param int|null    $timeout
     * @param string|null $apiUrl
     * @throws ClientException
     */
    public function __construct(string $xApiToken, ?int $timeout = null, ?string $apiUrl = null)
    {
        $this->xApiToken = $xApiToken;

        if (!is_null($timeout)) {
            $this->setTimeout($timeout);
        }
        if (!empty($apiUrl)) {
            $this->host = $apiUrl;
        }
        $this->setRequest();
    }

    private function validateMethod(string $method): bool
    {
        return (bool) ($this->allowedMethods[ $method ] ?? false);
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @throws ClientException
     */
    public function setTimeout(int $timeout): self
    {
        if ($timeout < 0) {
            throw new ClientException('Timeout can\'t be lower then zero', 400);
        }
        $this->timeout = $timeout;
        return $this;
    }

    public function setRequest(?HttpRequestInterface $request = null): self
    {
        if (!$request) {
            $this->request = new CurlRequest($this->host);
        } else {
            $this->request = $request;
        }
        return $this;
    }

    /**
     * Create custom request to Open API
     *
     * @param string $method    Allowed methods: GET, POST, DELETE, PUT, PATCH
     * @param string $endpoint  Path to the chosen End Point
     * @param array  $arguments Optional; Arguments to addtionally send
     * @param array  $files
     * @param string $contentType
     * @return array Result of request
     * @throws ClientException
     */
    public function request(
        string $method,
        string $endpoint,
        array  $arguments = [],
        array  $files = [],
        string $contentType = self::CONTENT_JSON
    ): array {
        if (!$this->validateMethod($method)) {
            throw new ClientException(
                'Not allowed method used. Allowed: '
                . implode(', ', array_keys($this->allowedMethods)),
                405
            );
        }

        $requestParams = $this->allowedMethods[ $method ];
        $headers       = [
            'X-Api-Token: ' . $this->xApiToken,
            'Content-Type: ' . $contentType,
            'Accept: application/json',
        ];

        $this->request->setOption(CURLOPT_RETURNTRANSFER, true)
                      ->setOption(CURLOPT_TIMEOUT, $this->timeout)
                      ->setOption(CURLOPT_FOLLOWLOCATION, true)
                      ->setOption(...$requestParams);

        $url = $this->host . trim($endpoint, '/') . '/';

        if (!empty($files)) {
            $arguments['attachments'] = [];
            foreach ($files as $file) {
                // Path to the file
                if (\is_string($file) && \is_file($file)) {
                    $stream                     = \file_get_contents($file);
                    $arguments['attachments'][] = [
                        'file' => \base64_encode($stream),
                        'mime' => \mime_content_type($file),
                        'name' => \basename($file),
                    ];
                    continue;
                }
                // File encoded in base64 with all required information
                if (\is_array($file)) {
                    if (!isset($file['file'])) {
                        throw new ClientException('File encoded into base64 was not found', 404);
                    }

                    if (!isset($file['name'])) {
                        throw new ClientException('Name of the file was not found', 404);
                    }

                    if (!isset($file['mime'])) {
                        throw new ClientException('Mime type of file was not found', 404);
                    }

                    $arguments['attachments'][] = [
                        'file' => $file['file'],
                        'mime' => $file['mime'],
                        'name' => $file['name'],
                    ];
                    continue;
                }

                throw new ClientException(
                    'Passed file wasn\'t a path or a stream, couldn\'t be send - cancelling request.',
                    400
                );
            }
        }

        if ($method === self::GET && !empty($arguments)) {
            $url .= '?' . \http_build_query($arguments);
        } else {
            $arguments = \json_encode($arguments);
            $this->request->setOption(CURLOPT_POSTFIELDS, $arguments);
            $headers[] = 'Content-Length: ' . \strlen($arguments);
        }
        $this->request->setOption(CURLOPT_HTTPHEADER, $headers)
                      ->setOption(CURLOPT_URL, $url);
        $result   = $this->request->execute();
        $curlInfo = $this->request->getInfo(null);
        $this->request->close();

        return [
            'data' => \gettype($result) == 'string' ? (\json_decode($result, true) ?? $result) : $result,
            'info' => $curlInfo,
        ];
    }

    /**
     * @throws ClientException
     */
    public function getSsdVpsPlans(): array
    {
        return $this->request(self::GET, 'ssd-vps/plans');
    }

    /**
     * @throws ClientException
     */
    public function getSsdVpsLocations(): array
    {
        return $this->request(self::GET, 'ssd-vps/locations');
    }

    /**
     * @throws ClientException
     */
    public function getSsdVpsCustomTemplates(int $locationId): array
    {
        return $this->request(self::GET, "ssd-vps/locations/$locationId/templates/custom");
    }

    /**
     * @throws ClientException
     */
    public function createSsdVpsServer(
        int     $locationId,
        string  $label,
        string  $hostname,
        string  $password,
        int     $servicePlanId,
        ?string $osComponentCode = null,
        ?bool   $backups = null,
        ?bool   $billHourly = null,
        ?int    $customTemplateId = null
    ): array {
        $params = [
            'label'           => $label,
            'hostname'        => $hostname,
            'password'        => $password,
            'service_plan_id' => $servicePlanId,
        ];

        if (!\is_null($customTemplateId)) {
            $params['custom_template_id'] = $customTemplateId;
        } elseif (!\is_null($osComponentCode)) {
            $params['os_component_code'] = $osComponentCode;
        }

        if (!\is_null($billHourly)) {
            $params['bill_hourly'] = $billHourly ? 1 : 0;
        }

        if (!\is_null($backups)) {
            $params['backups'] = $backups ? 1 : 0;
        }

        return $this->request(
            self::POST,
            "ssd-vps/locations/$locationId/servers",
            $params
        );
    }

    /**
     * @throws ClientException
     */
    public function getSsdVpsOses(int $locationId): array
    {
        return $this->request(self::GET, "ssd-vps/locations/$locationId/operating-systems");
    }

    /**
     * @throws ClientException
     */
    public function getSsdVpsServers(?int $locationId = null): array
    {
        $params = [];
        if (!\is_null($locationId)) {
            $params['location_id'] = $locationId;
        }
        return $this->request(self::GET, 'ssd-vps/servers', $params);
    }

    /**
     * @throws ClientException
     */
    public function getSsdVpsServerDetails(int $locationId, int $serverId): array
    {
        return $this->request(self::GET, "ssd-vps/locations/$locationId/servers/$serverId");
    }

    /**
     * @throws ClientException
     */
    public function deleteSsdVpsServer(int $locationId, int $serverId): array
    {
        return $this->request(self::DELETE, "ssd-vps/locations/$locationId/servers/$serverId");
    }

    /**
     * @throws ClientException
     */
    public function getSsdVpsServerStatus(int $locationId, int $serverId): array
    {
        return $this->request(self::GET, "ssd-vps/locations/$locationId/servers/$serverId/status");
    }

    /**
     * @throws ClientException
     */
    public function powerOnSsdVpsServer(int $locationId, int $serverId): array
    {
        return $this->request(self::POST, "ssd-vps/locations/$locationId/servers/$serverId/power/on");
    }

    /**
     * @throws ClientException
     */
    public function powerOffSsdVpsServer(int $locationId, int $serverId): array
    {
        return $this->request(self::POST, "ssd-vps/locations/$locationId/servers/$serverId/power/off");
    }

    /**
     * @throws ClientException
     */
    public function rebootSsdVpsServer(int $locationId, int $serverId): array
    {
        return $this->request(self::POST, "ssd-vps/locations/$locationId/servers/$serverId/power/reboot");
    }

    /**
     * @throws ClientException
     */
    public function rebootSsdVpsServerInRecoveryMode(int $locationId, int $serverId): array
    {
        return $this->request(self::POST, "ssd-vps/locations/$locationId/servers/$serverId/power/recovery-reboot");
    }

    /**
     * @throws ClientException
     */
    public function resetSsdVpsServerPassword(int $locationId, int $serverId, ?string $newPassword = null): array
    {
        $params = [];
        if (!is_null($newPassword)) {
            $params['new_password'] = $newPassword;
        }

        return $this->request(
            self::POST,
            "ssd-vps/locations/$locationId/servers/$serverId/password-reset",
            $params
        );
    }

    /**
     * @throws ClientException
     */
    public function getSsdVpsServerBackups(int $locationId, int $serverId): array
    {
        return $this->request(self::GET, "ssd-vps/locations/$locationId/servers/$serverId/backups");
    }

    /**
     * @throws ClientException
     */
    public function addSsdVpsBackupNote(
        int    $locationId,
        int    $serverId,
        int    $backupId,
        string $note
    ): array {
        return $this->request(
            self::POST,
            "ssd-vps/locations/$locationId/servers/$serverId/backups/$backupId/note",
            [
                'note' => $note,
            ]
        );
    }

    /**
     * @throws ClientException
     */
    public function deleteSsdVpsBackup(int $locationId, int $serverId, int $backupId): array
    {
        return $this->request(self::DELETE, "ssd-vps/locations/$locationId/servers/$serverId/backups/$backupId");
    }

    /**
     * @throws ClientException
     */
    public function restoreSsdVpsBackup(int $locationId, int $serverId, int $backupId): array
    {
        return $this->request(self::POST, "ssd-vps/locations/$locationId/servers/$serverId/backups/$backupId/restore");
    }

    /**
     * @throws ClientException
     */
    public function getServiceDetails(int $serviceId): array
    {
        return $this->request(self::GET, "billing/services/$serviceId");
    }

    /**
     * @throws ClientException
     */
    public function getDnsZones(): array
    {
        return $this->request(self::GET, 'dns-zones');
    }

    /**
     * @throws ClientException
     */
    public function createDnsZone(string $domainName, string $ip): array
    {
        $params = [
            'domain_name' => $domainName,
            'ip'          => $ip,
        ];
        return $this->request(self::POST, 'dns-zones', $params);
    }

    /**
     * @throws ClientException
     */
    public function getDnsZoneDetails(int $zoneId): array
    {
        return $this->request(self::GET, 'dns-zones/' . $zoneId);
    }

    /**
     * @throws ClientException
     */
    public function deleteDnsZone(int $zoneId): array
    {
        return $this->request(self::DELETE, 'dns-zones/' . $zoneId);
    }

    /**
     * @throws ClientException
     */
    public function addRecordToDnsZone(
        int     $zoneId,
        string  $type,
        string  $host,
        string  $content,
        int     $ttl,
        ?string $service = null,
        ?string $protocol = null,
        ?int    $port = null,
        ?int    $weight = null,
        ?int    $mxPriority = null
    ): array {
        $params = [
            'type'    => $type,
            'host'    => $host,
            'content' => $content,
            'ttl'     => $ttl,
        ];

        if (!\is_null($service)) {
            $params['service'] = $service;
        }

        if (!\is_null($protocol)) {
            $params['protocol'] = strtolower($protocol);
        }

        if (!\is_null($port)) {
            $params['port'] = $port;
        }

        if (!\is_null($weight)) {
            $params['weight'] = $weight;
        }

        if (!\is_null($mxPriority)) {
            $params['mx_priority'] = $mxPriority;
        }

        return $this->request(
            self::POST, "dns-zones/$zoneId/records", $params
        );
    }

    /**
     * @throws ClientException
     */
    public function updateDnsZoneRecord(
        int     $zoneId,
        int     $recordId,
        string  $host,
        string  $content,
        int     $ttl,
        ?string $service = null,
        ?string $protocol = null,
        ?int    $port = null,
        ?int    $weight = null,
        ?int    $mxPriority = null
    ): array {
        $params = [
            'host'    => $host,
            'content' => $content,
            'ttl'     => $ttl,
        ];

        if (!\is_null($service)) {
            $params['service'] = $service;
        }

        if (!\is_null($protocol)) {
            $params['protocol'] = $protocol;
        }

        if (!\is_null($port)) {
            $params['port'] = $port;
        }

        if (!\is_null($weight)) {
            $params['weight'] = $weight;
        }

        if (!\is_null($mxPriority)) {
            $params['mx_priority'] = $mxPriority;
        }

        return $this->request(
            self::PUT, "dns-zones/$zoneId/records/$recordId", $params
        );
    }

    /**
     * @throws ClientException
     */
    public function deleteDnsZoneRecord(
        int $zoneId,
        int $recordId
    ): array {
        return $this->request(self::DELETE, "dns-zones/$zoneId/records/$recordId");
    }

    /**
     * @throws ClientException
     */
    public function getServers(): array
    {
        return $this->request(self::GET, 'servers/');
    }

    /**
     * @throws ClientException
     */
    public function getServerDetails(string $serverId): array
    {
        return $this->request(self::GET, "servers/$serverId");
    }

    /**
     * @throws ClientException
     */
    public function getServerBandwidthGraph(int $serverId, string $periodStart = null, string $periodEnd = null): array
    {
        $params = [];

        if (!\is_null($periodStart)) {
            $params['period_start'] = $periodStart;
        }

        if (!\is_null($periodEnd)) {
            $params['period_end'] = $periodEnd;
        }

        return $this->request(self::GET, "servers/$serverId/bandwidth-graph", $params);
    }

    /**
     * @throws ClientException
     */
    public function getTickets(): array
    {
        return $this->request(self::GET, self::TICKETS_ENDPOINT);
    }

    /**
     * @throws ClientException
     */
    public function createTicket(
        string $body,
        string $subject,
        int    $department = 0,
        int    $priority = 0,
        array  $attachments = []
    ): array {
        $params = [
            'body'       => $body,
            'subject'    => $subject,
            'department' => $department,
            'priority'   => $priority,
        ];
        return $this->request(self::POST, self::TICKETS_ENDPOINT, $params, $attachments);
    }

    /**
     * @throws ClientException
     */
    public function getTicketDepartments(): array
    {
        return $this->request(self::GET, self::TICKETS_ENDPOINT . 'queues');
    }

    /**
     * @throws ClientException
     */
    public function getTicketDetails(int $ticketId): array
    {
        return $this->request(self::GET, self::TICKETS_ENDPOINT . $ticketId);
    }

    /**
     * @throws ClientException
     */
    public function updateTicket(int $ticketId, int $priority = 0, bool $closeTicket = false): array
    {
        $params = [
            'priority' => $priority,
        ];

        if ($closeTicket) {
            $params['status'] = 'close';
        }

        return $this->request(self::PUT, self::TICKETS_ENDPOINT . $ticketId, $params);
    }

    /**
     * @throws ClientException
     */
    public function addReplyToTicket(int $ticketId, string $body, array $attachments = []): array
    {
        return $this->request(
            self::POST,
            self::TICKETS_ENDPOINT . $ticketId . '/comments',
            [
                'body' => $body,
            ],
            $attachments
        );
    }

    /**
     * @throws ClientException
     */
    public function getStatusUpdates(): array
    {
        return $this->request(self::GET, 'status-updates');
    }

    /**
     * @throws ClientException
     */
    public function getDatacenters(): array
    {
        return $this->request(self::GET, 'orders/locations');
    }

    /**
     * @throws ClientException
     */
    public function getProductCategory(): array
    {
        return $this->request(self::GET, 'orders/categories');
    }

    /**
     * @throws ClientException
     */
    public function getProductsInCategory(int $locationId, int $categoryId): array
    {
        return $this->request(self::GET, "orders/locations/$locationId/categories/$categoryId/products");
    }

    /**
     * @throws ClientException
     */
    public function getProductDetails(int $locationId, int $categoryId, int $productId): array
    {
        return $this->request(self::GET, "orders/locations/$locationId/categories/$categoryId/products/$productId");
    }

    /**
     * @throws ClientException
     */
    public function getCalculatedPriceWithTax(array $body): array
    {
        return $this->request(self::POST, 'orders/tax', $body);
    }

    /**
     * @throws ClientException
     */
    public function getPaymentMethods(): array
    {
        return $this->request(self::GET, 'orders/payment-methods');
    }

    /**
     * @throws ClientException
     */
    public function submitOrderForProcessing(array $body): array
    {
        return $this->request(self::POST, 'orders', $body);
    }

    /**
     * @throws ClientException
     */
    public function getServerIPMIDetails(string $serverId): array
    {
        return $this->request(self::GET, "servers/$serverId/ipmi");
    }

    /**
     * @throws ClientException
     */
    public function createServerIPMICredentials(string $serverId): array
    {
        return $this->request(self::POST, "servers/$serverId/ipmi");
    }

    /**
     * @throws ClientException
     */
    public function deleteServerIPMICredentials(string $serverId): array
    {
        return $this->request(self::DELETE, "servers/$serverId/ipmi");
    }

    /**
     * @throws ClientException
     */
    public function getServerPowerStatus(string $serverId): array
    {
        return $this->request(self::GET, "servers/$serverId/power/status");
    }

    /**
     * @throws ClientException
     */
    public function powerOnServer(string $serverId): array
    {
        return $this->request(self::POST, "servers/$serverId/power/on");
    }

    /**
     * @throws ClientException
     */
    public function powerOffServer(string $serverId): array
    {
        return $this->request(self::POST, "servers/$serverId/power/off");
    }

    /**
     * @throws ClientException
     */
    public function rebootServer(string $serverId): array
    {
        return $this->request(self::POST, "servers/$serverId/power/reboot");
    }

    /**
     * @throws ClientException
     */
    public function changeServerFriendlyName(string $serverId, array $body): array
    {
        return $this->request(self::PUT, "servers/$serverId/friendly-name", $body);
    }

    /**
     * @throws ClientException
     */
    public function setRDNSentryForIpAddress(string $serverId, string $ipAddress, array $body): array
    {
        return $this->request(self::PUT, "servers/$serverId/ip-addresses/$ipAddress/rdns", $body);
    }

    /**
     * @throws ClientException
     */
    public function getBillingServices(
        ?bool   $showAddOns,
        ?string $sortBy,
        ?string $direction,
        ?int    $offset,
        ?int    $limit
    ): array {
        $params = [];

        if (!is_null($showAddOns)) {
            $params['show_add_ons'] = (int) $showAddOns;
        }

        if (!is_null($sortBy)) {
            $params['sort_by'] = $sortBy;
        }

        if (!is_null($direction)) {
            $params['direction'] = $direction;
        }

        if (!is_null($offset)) {
            $params['offset'] = $offset;
        }

        if (!is_null($limit)) {
            $params['limit'] = $limit;
        }

        return $this->request(self::GET, "billing/services", $params);
    }

    /**
     * @throws ClientException
     */
    public function getBillingInvoices(?int $offset, ?int $limit): array
    {
        $params = [];

        if (!is_null($offset)) {
            $params['offset'] = $offset;
        }

        if (!is_null($limit)) {
            $params['limit'] = $limit;
        }

        return $this->request(self::GET, 'billing/invoices', $params);
    }

    /**
     * @throws ClientException
     */
    public function getBillingServiceUpgrades(int $serviceId): array
    {
        return $this->request(self::GET, "billing/services/$serviceId/upgrades");
    }

    /**
     * @throws ClientException
     */
    public function upgradesService(
        int    $serviceId,
        string $addonCode,
        string $optionCode,
        string $details = '',
        int    $quantity = 1,
        ?array $ipCount = null
    ): array {
        $params = [
            'service'     => $serviceId,
            'addon_code'  => $addonCode,
            'option_code' => $optionCode,
            'details'     => $details,
            'quantity'    => $quantity,
        ];

        if (!\is_null($ipCount)) {
            $params['ip_count'] = $ipCount;
        }

        return $this->request(self::POST, 'billing/services/upgrade/', $params);
    }

    /**
     * @throws ClientException
     */
    public function getUserList(): array
    {
        return $this->request(self::GET, 'user');
    }

    /**
     * @throws ClientException
     */
    public function getUserRoles(): array
    {
        return $this->request(self::GET, 'user/roles');
    }

    /**
     * @throws ClientException
     */
    public function getUser(): array
    {
        return $this->request(self::GET, 'user/details');
    }

    /**
     * @throws ClientException
     */
    public function addUser(
        string $email,
        string $firstName,
        string $lastName,
        bool   $twoFaRequired,
        array  $roles
    ): array {
        $body = [
            'email'           => $email,
            'first_name'      => $firstName,
            'last_name'       => $lastName,
            'roles'           => $roles,
            'two_fa_required' => $twoFaRequired ? '1' : '0',
        ];

        return $this->request(self::POST, 'user', $body);
    }

    /**
     * @throws ClientException
     */
    public function getSSLCertificates(?int $offset, ?int $limit, bool $collected = false): array
    {
        $params = [];
        if (!is_null($offset)) {
            $params['offset'] = $offset;
        }
        if (!is_null($limit)) {
            $params['limit'] = $limit;
        }
        if ($collected) {
            $params['collected'] = 'true';
        }
        return $this->request(self::GET, 'ssl', $params);
    }

    /**
     * @throws ClientException
     */
    public function createSSLCertificate(string $domain, string $csr): array
    {
        $params = [];

        $params['domain'] = $domain;
        $params['csr']    = $csr;

        return $this->request(self::POST, 'ssl', $params);
    }

    /**
     * @throws ClientException
     */
    public function applySSLCertificate(string $domain, string $csr, string $email, int $serverSoftware): array
    {
        $params = [];

        $params['domain']         = $domain;
        $params['csr']            = $csr;
        $params['email']          = $email;
        $params['serverSoftware'] = $serverSoftware;

        return $this->request(self::POST, 'ssl/apply', $params);
    }

    /**
     * @throws ClientException
     */
    public function downloadSSLCertificate(int $certificateId): array
    {
        return $this->request(self::GET, 'ssl/' . $certificateId . '/download');
    }

    /**
     * Retrieves a list of MS licenses
     *
     * @param int $serviceId
     * @return array
     * @throws ClientException
     */
    public function getMicrosoftLicenses(int $serviceId): array
    {
        return $this->request(ThgHostingClient::GET, "/services/$serviceId/licenses");
    }

    /**
     * Retrieves an MS license details
     *
     * @param int $serviceId
     * @param int $licenseId
     * @return array
     * @throws ClientException
     */
    public function getMicrosoftLicenseDetails(int $serviceId, int $licenseId): array
    {
        return $this->request(ThgHostingClient::GET, "/services/$serviceId/licenses/$licenseId");
    }

    /**
     * Deletes a MS license
     *
     * @param int $serviceId
     * @param int $licenseId
     * @return array
     * @throws ClientException
     */
    public function deleteMicrosoftLicense(int $serviceId, int $licenseId): array
    {
        return $this->request(ThgHostingClient::DELETE, "/services/$serviceId/licenses/$licenseId");
    }

    /**
     * Retrieves a list of available microsoft license products
     *
     * @param int $serviceId
     * @return array
     * @throws ClientException
     */
    public function getMicrosoftLicenseProducts(int $serviceId): array
    {
        return $this->request(ThgHostingClient::GET, "/services/$serviceId/ms-license-products");
    }

    /**
     * Get SSH keys data
     *
     * @return array
     * @throws ClientException
     */
    public function listSshKeys(): array
    {
        return $this->request(ThgHostingClient::GET, "/ssh-keys");
    }

    /**
     * Add an SSH key
     *
     * @param string $key
     * @param string $label
     * @return array
     * @throws ClientException
     */
    public function createSshKey(string $key, string $label): array
    {
        $params = [
            'public_key'       => $key,
            'label'    => $label,
        ];
        return $this->request(ThgHostingClient::POST, "/ssh-keys", $params);
    }

    /**
     * Update an SSH key label
     *
     * @param int    $sshId
     * @param string $label
     * @return array
     * @throws ClientException
     */
    public function updateSshKeyLabel(int $sshId, string $label): array
    {
        $params = [
            'label'    => $label,
        ];
        return $this->request(ThgHostingClient::PUT, "/ssh-keys/$sshId", $params);
    }

    /**
     * Delete an SSH key
     *
     * @param int $sshId
     * @return array
     * @throws ClientException
     */
    public function deleteSshKey(int $sshId): array
    {
        return $this->request(ThgHostingClient::DELETE, "/ssh-keys/$sshId");
    }

    /**
     * Get an SSH key with id
     *
     * @param int $sshId
     * @return array
     * @throws ClientException
     */
    public function getSshKeyById(int $sshId): array
    {
        return $this->request(ThgHostingClient::GET, "/ssh-keys/$sshId");
    }


    /**
     * Get server inventory list
     *
     * @return array
     * @throws ClientException
     */
    public function getServerInventory(): array
    {
       return $this->request(ThgHostingClient::GET, "server-orders/inventory");
    }
}
