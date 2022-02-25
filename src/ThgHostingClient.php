<?php declare(strict_types=1);
/**
 * (c) The Hut Group 2001-2019, All Rights Reserved.
 *
 * This source code is the property of The Hut Group, registered address:
 *
 * 5th Floor, Voyager House, Chicago Avenue, Manchester Airport,
 * Manchester, England, M90 3DQ
 * @author Michal Dzierzbicki <michal.dzierzbicki@thg.com>
 */
namespace ThgHosting;

use ThgException;

/**
 * THG Hosting API Client
 */
class ThgHostingClient
{
    protected $host = 'https://api.thghosting.com/rest-api/';
    public const GET    = "GET";
    public const POST   = "POST";
    public const DELETE = "DELETE";
    public const PUT    = "PUT";
    public const PATCH  = "PATCH";
    PUBLIC CONST CONTENT_JSON = 'application/json';
    PUBLIC CONST CONTENT_MULTIPART = 'multipart/form-data';
    private $timeout = 60;
    private $allowedMethods = [
        self::GET    => [CURLOPT_CUSTOMREQUEST, self::GET   ],
        self::POST   => [CURLOPT_CUSTOMREQUEST, self::POST  ],
        self::DELETE => [CURLOPT_CUSTOMREQUEST, self::DELETE],
        self::PUT    => [CURLOPT_CUSTOMREQUEST, self::PUT   ],
        self::PATCH  => [CURLOPT_CUSTOMREQUEST, self::PATCH ]
    ];

    /** @var string */
    private $xApiToken;

    /**
     * @param private $xApiToken  X-Api-Token required for any requests to
     *                            THG Hosting Open API
     */
    function __construct(string $xApiToken)
    {
        $this->xApiToken = $xApiToken;
    }

    private function validateMethod(string $method): bool
    {
        return !!($this->allowedMethods[$method] ?? false);
    }

    /**
     * Create custom request to Open API
     *
     * @param  string       $method    Allowed methods: GET, POST, DELETE, PUT, PATCH
     * @param  string       $endpoint  Path to the chosen End Point
     * @param  array        $arguments Optional; Arguments to addtionally send
     * @return string|array            Result of request
     */
    public function request(
        string $method,
        string $endpoint,
        array $arguments = [],
        array $files = [],
        string $contentType = self::CONTENT_JSON
    ): array {
        if (!$this->validateMethod($method)) {
            throw new ThgException("Not allowed method used. Allowed: " . implode(', ', array_keys($allowedMethods)), 405);
        }

        $requestParams = $this->allowedMethods[$method];
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, ...$requestParams);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "X-Api-Token: " . $this->xApiToken,
            "Content-type: application/json",
            "Accept: application/json"
        ]);

        $url = $this->host . trim($endpoint, '/') . '/';

        if ($method === self::GET && \sizeof($arguments) > 0) {
            $query = http_build_query($arguments);
            $url .= "?" . $query;
        } else {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($arguments));
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        curl_close($curl);

        return [
            "data" => json_decode($result, true) ?? $result,
            "info" => curl_getinfo($curl)
        ];
    }

    public function getSsdVpsPlans(): array
    {
        return $this->request(self::GET, "ssd-vps/plans");
    }

    public function getSsdVpsLocations(): array
    {
        return $this->request(self::GET, "ssd-vps/locations");
    }

    public function getSsdVpsCustomTemplates(int $locationId): array
    {
        return $this->request(self::GET, "ssd-vps/locations/$locationId/templates/custom");
    }

    public function createSsdVpsServer(
        int $locationId,
        string $label,
        string $hostname,
        string $password,
        int $servicePlanId,
        ?string $osComponentCode = null,
        ?bool $backups = null,
        ?bool $billHourly = null,
        ?int $customTemplateId = null
    ): array {
        $params = [
            "label"              => $label,
            "hostname"           => $hostname,
            "password"           => $password,
            "service_plan_id"    => $servicePlanId
        ];

        if (!\is_null($customTemplateId)) {
            $params["custom_template_id"] = $customTemplateId;
        } elseif (!\is_null($osComponentCode)) {
            $params["os_component_code"] = $osComponentCode;
        }

        if (!\is_null($billHourly)) {
            $params["bill_hourly"] = $billHourly ? 1 : 0;
        }

        if (!\is_null($backups)) {
            $params["backups"] = $backups ? 1 : 0;
        }

        return $this->request(
            self::POST,
            "ssd-vps/locations/$locationId/servers",
            $params
        );
    }

    public function getSsdVpsOses(int $locationId): array
    {
        return $this->request(self::GET, "ssd-vps/locations/$locationId/operating-systems");
    }

    public function getSsdVpsServers(?int $locationId = null): array
    {
        $params = [];
        if (!\is_null($locationId)) {
            $params["location_id"] = $locationId;
        }
        return $this->request(self::GET, "ssd-vps/servers", $params);
    }

    public function getSsdVpsServerDetails(int $locationId, int $serverId): array
    {
        return $this->request(self::GET, "ssd-vps/locations/$locationId/servers/$serverId");
    }

    public function deleteSsdVpsServer(int $locationId, int $serverId): array
    {
        return $this->request(self::DELETE, "ssd-vps/locations/$locationId/servers/$serverId");
    }

    public function getSsdVpsServerStatus(int $locationId, int $serverId): array
    {
        return $this->request(self::GET, "ssd-vps/locations/$locationId/servers/$serverId/status");
    }

    public function powerOnSsdVpsServer(int $locationId, int $serverId): array
    {
        return $this->request(self::POST, "ssd-vps/locations/$locationId/servers/$serverId/power/on");
    }

    public function powerOffSsdVpsServer(int $locationId, int $serverId): array
    {
        return $this->request(self::POST, "ssd-vps/locations/$locationId/servers/$serverId/power/off");
    }

    public function rebootSsdVpsServer(int $locationId, int $serverId): array
    {
        return $this->request(self::POST, "ssd-vps/locations/$locationId/servers/$serverId/power/reboot");
    }

    public function rebootSsdVpsServerInRecoveryMode(int $locationId, int $serverId): array
    {
        return $this->request(self::POST, "ssd-vps/locations/$locationId/servers/$serverId/power/recovery-reboot");
    }

    public function resetSsdVpsServerPassword(int $locationId, int $serverId, ?string $newPassword = null): array
    {
        $params = [];
        if (!is_null($newPassword)) {
            $params["new_password"] = $newPassword;
        }

        return $this->request(
            self::POST,
            "ssd-vps/locations/$locationId/servers/$serverId/password-reset",
            $params
        );
    }

    public function getSsdVpsServerBackups(int $locationId, int $serverId): array
    {
        return $this->request(self::GET, "ssd-vps/locations/$locationId/servers/$serverId/backups");
    }

    public function addSsdVpsBackupNote(
        int $locationId,
        int $serverId,
        int $backupId,
        string $note
    ): array {

        return $this->request(
            self::POST,
            "ssd-vps/locations/$locationId/servers/$serverId/backups/$backupId/note",
            [
                "note" => $note
            ]
        );
    }

    public function deleteSsdVpsBackup(int $locationId, int $serverId, int $backupId): array
    {
        return $this->request(self::DELETE, "ssd-vps/locations/$locationId/servers/$serverId/backups/$backupId");
    }

    public function restoreSsdVpsBackup(int $locationId, int $serverId, int $backupId): array
    {
        return $this->request(self::GET, "ssd-vps/locations/$locationId/servers/$serverId/backups/$backupId/restore");
    }

    public function getServiceDetails(int $serviceId): array
    {
        return $this->request(self::GET, "billing/services/$serviceId");
    }

    public function getDnsZones(): array
    {
        return $this->request(self::GET, "dns-zones");
    }

    public function createDnsZone(string $domainName, string $ip): array
    {
        $params = [
            "domain_name" => $domainName,
            "ip" => $ip
        ];
        return $this->request(self::POST, "dns-zones", $params);
    }

    public function getDnsZoneDetails(int $zoneId): array
    {
        return $this->request(self::GET, "dns-zones/" . $zoneId);
    }

    public function deleteDnsZone(int $zoneId): array
    {
        return $this->request(self::DELETE, "dns-zones/" . $zoneId);
    }

    public function addRecordToDnsZone(
        int $zoneId,
        string $type,
        string $host,
        string $content,
        int $ttl,
        ?string $service = null,
        ?string $protocol = null,
        ?int $port = null,
        ?int $weight = null,
        ?bool $mxPriority = null
    ): array {
        $params = [
            "type"   => $type,
            "host"   => $host,
            "content" => $content,
            "ttl"    => $ttl
        ];

        if (!\is_null($service)) {
            $params["service"] = $service;
        }

        if (!\is_null($protocol)) {
            $params["protocol"] = strtolower($protocol);
        }

        if (!\is_null($port)) {
            $params["port"] = $port;
        }

        if (!\is_null($weight)) {
            $params["weight"] = $weight;
        }

        if (!\is_null($mxPriority)) {
            $params["mx_priority"] = $mxPriority;
        }

        return $this->request(
            self::POST, "dns-zones/$zoneId/records", $params
        );
    }

    public function updateDnsZoneRecord(
        int $zoneId,
        int $recordId,
        string $type,
        string $host,
        string $content,
        int $ttl,
        ?string $service = null,
        ?string $protocol = null,
        ?int $port = null,
        ?int $weight = null,
        ?int $mxPriority = null
    ): array {
        $params = [
            "type"   => $type,
            "host"   => $host,
            "contet" => $content,
            "ttl"    => $ttl
        ];

        if (!\is_null($service)) {
            $params["service"] = $service;
        }

        if (!\is_null($protocol)) {
            $params["protocol"] = $protocol;
        }

        if (!\is_null($port)) {
            $params["port"] = $port;
        }

        if (!\is_null($weight)) {
            $params["weight"] = $weight;
        }

        if (!\is_null($mxPriority)) {
            $params["mx_priority"] = $mxPriority;
        }

        return $this->request(
            self::PUT, "dns-zones/$zoneId/records/$recordId", $params
        );
    }

    public function deleteDnsZoneRecord(
        int $zoneId,
        int $recordId
    ): array {
        return $this->request(self::DELETE, "dns-zones/$zoneId/records/$recordId");
    }

    public function getServers(): array
    {
        return $this->request(self::GET, "servers");
    }

    public function getServerDetails(int $serverId): array
    {
        return $this->request(self::GET, "servers/$serverId");
    }

    public function getServerBandwidthGraph(int $serverId, string $periodStart = null, string $periodEnd = null): array
    {
        $params = [];

        if (!\is_null($periodStart)) {
            $params["period_start"] = $periodStart;
        }

        if (!\is_null($periodEnd)) {
            $params["period_end"] = $periodEnd;
        }

        return $this->request(self::GET, "servers/$serverId/bandwidth-graph", $params);
    }

    public function getTickets(): array
    {
        return $this->request(self::GET, "tickets");
    }

    // @TODO test attachments - how it should be uploaded and in what format
    public function createTicket(
        string $body,
        string $subject,
        int $department = 0,
        int $priority = 0,
        array $attachments = []
    ): array {
        $params = [
            "body" => $body,
            "subject" => $subject,
            "department" => $department,
            "priority" => $priority
        ];
        return $this->request(self::POST, "tickets", $params, $attachments);
    }

    public function getTicketDepartments(): array
    {
        return $this->request(self::GET, "tickets/queues");
    }

    public function getTicketDetails(int $ticketId): array
    {
        return $this->request(self::GET, "tickets/" . $ticketId);
    }

    public function updateTicket(int $ticketId, int $priority = 0, bool $closeTicket = false): array
    {
        $params = [
            "priority" => $priority
        ];

        if ($closeTicket) {
            $params["status"] = "close";
        }

        return $this->request(self::PUT, "tickets/" . $ticketId, $params);
    }

    public function addReplyToTicket(int $ticketId, string $body, array $attachments = []): array
    {
        return $this->request(
            self::POST,
            "tickets/" . $ticketId,
            [
                "body" => $body
            ],
            $attachments
        );
    }

    public function getStatusUpdates(): array
    {
        return $this->request(self::GET, "status-updates");
    }

    public function getDatacenters(): array
    {
        return $this->request(self::GET, "orders/locations");
    }

    public function getProductCategory(): array
    {
        return $this->request(self::GET, "orders/categories");
    }

    public function getProductsInCategory(int $locationId, int $categoryId): array
    {
        return $this->request(self::GET, "orders/locations/$locationId/categories/$categoryId/products");
    }

    public function getProductDetails(int $locationId, int $categoryId, int $productId): array
    {
        return $this->request(self::GET, "orders/locations/$locationId/categories/$categoryId/products/$productId");
    }

    public function getCalculatedPriceWithTax(array $body): array
    {
        return $this->request(self::POST, "orders/tax", $body);
    }

    public function getPaymentMethods(): array
    {
        return $this->request(self::GET, "orders/payment-methods");
    }

    public function submitOrderForProcessing(array $body): array
    {
        return $this->request(self::POST, "orders", $body);
    }

}
