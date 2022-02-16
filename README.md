# THG Hosting Open API Client
PHP Client for easier use of Open API

```php
    $thgHostingClient = new ThgHosting\ThgHostingClient($_ENV['X-Api-Token']);
```

# Methods

## Get SSD VPS Plans
*Returns SSD VPS Plans with net price in USD.*

```php
    $thgHostingClient->getSsdVpsPlans();
```

## Get SSD VPS Locations
*Returns SSD VPS Locations.*

```php
    $thgHostingClient->getSsdVpsLocations();
```

## Get Custom OSes for SSD VPS
*Returns Custom OSes for SSD VPS.*

```php
    $thgHostingClient->getSsdVpsCustomTemplates(
        int $locationId // ID of the SSD VPS Location (see “Get SSD VPS Locations”)
    );
```

## Create an SSD VPS Server
*Creates an SSD VPS Server.*

```php
    $thgHostingClient->createSsdVpsServer(
        int     $locationId,              // ID of the SSD VPS Location (see “Get SSD VPS Locations”)
        string  $label,                   // Label for the new SSD VPS Server
        string  $hostname,                // Hostname for the new SSD VPS Server
        string  $password,                // Password for the new SSD VPS Server
        int     $servicePlanId,           // ID of the chosen SSD VPS plan (see “Get SSD VPS Plans”)
        ?string $osComponentCode  = null, // Optional; Component code of the SSD VPS operating system (see “Get SSD VPS Operating Systems”) (you have to pass either os_component_code or custom_template_id)
        ?bool   $backups          = null, // Optional; If passed, server will be created with Backup Protection
        ?bool   $billHourly       = null, // Optional; If passed billing will be set hourly, otherwise monthly billing will be used
        ?int    $customTemplateId = null  // Optional; Pass here the Template ID of Custom OS (see “Get Custom OSes for SSD VPS”) (you have to pass either os_component_code or custom_template_id)
    );
```

## Get SSD VPS Operating Systems
*Returns SSD VPS Operating Systems.*

```php
    $thgHostingClient->getSsdVpsOses(
        int $locationId // ID of the SSD VPS Location (see “Get SSD VPS Locations”)
    );
```

## Get SSD VPS Servers
*Returns SSD VPS Servers.*

```php
    $thgHostingClient->getSsdVpsServers(
        ?int $locationId = null // ID of the SSD VPS Location (see “Get SSD VPS Locations”)
    );
```

## Get SSD VPS Server Details
*Returns an SSD VPS Server’s details.*

```php
    $thgHostingClient->getSsdVpsServerDetails(
        int $locationId, // ID of the SSD VPS Location (see “Get SSD VPS Locations”)
        int $serverId    // ID of the SSD VPS Server
    );
```

## Delete SSD VPS Server
*Deletes an SSD VPS Server.*

```php
    $thgHostingClient->deleteSsdVpsServer(
        int $locationId, // ID of the SSD VPS Location (see “Get SSD VPS Locations”)
        int $serverId    // ID of the SSD VPS Server.
    );
```

## Get SSD VPS Server Status
*Returns an SSD VPS Server’s status.*

```php
    $thgHostingClient->getSsdVpsServerStatus(
        int $locationId, // ID of the SSD VPS Location (see “Get SSD VPS Locations”)
        int $serverId    // ID of the SSD VPS Server
    );
```

## Power On an SSD VPS Server
*Startup an SSD VPS Server.*

```php
    $thgHostingClient->powerOnSsdVpsServer(
        int $locationId, // ID of the SSD VPS Location (see “Get SSD VPS Locations”)
        int $serverId    // ID of the SSD VPS Server
    );
```

## Power Off SSD VPS Server
*Shutdown an SSD VPS Server.*

```php
    $thgHostingClient->powerOffSsdVpsServer(
        int $locationId, // ID of the SSD VPS Location (see “Get SSD VPS Locations”)
        int $serverId    // ID of the SSD VPS Server
    );
```

## Reboot SSD VPS Server
*Reboot an SSD VPS Server.*

```php
    $thgHostingClient->rebootSsdVpsServer(
        int $locationId, // ID of the SSD VPS Location (see “Get SSD VPS Locations”)
        int $serverId    // ID of the SSD VPS Server
    );
```

## Reboot an SSD VPS Server in Recovery Mode
*Reboot an SSD VPS in Recovery Mode.*

```php
    $thgHostingClient->rebootSsdVpsServerInRecoveryMode(
        int $locationId, // ID of the SSD VPS Location (see “Get SSD VPS Locations”)
        int $serverId    // ID of the SSD VPS Server
    );
```

## Reset Password of an SSD VPS Server
*Change the virtual machine’s root password to regain control of a machine should root access be lost or forgotten.*

```php
    $thgHostingClient->resetSsdVpsServerPassword(
        int     $locationId,        // ID of the SSD VPS Location (see “Get SSD VPS Locations”)
        int     $serverId,          // ID of the SSD VPS Server
        ?string $newPassword = null // Root password to set. Must be between 6 and 32 characters and valid for the OS of the target virtual machine.
    );
```

## Get SSD VPS Server Backups
*Returns an SSD VPS Server’s Backups.*

```php
    $thgHostingClient->getSsdVpsServerBackups(
        int $locationId, // ID of the SSD VPS Location (see “Get SSD VPS Locations”)
        int $serverId    // ID of the SSD VPS Server
    );
```

## Add Note to an SSD VPS Server Backup
*Add note to an SSD VPS Server’s backup.*

```php
    $thgHostingClient->addSsdVpsBackupNote(
        int    $locationId, // ID of the SSD VPS Location (see “Get SSD VPS Locations”)
        int    $serverId,   // ID of the SSD VPS Server
        int    $backupId,   // ID of the SSD VPS Backup (see “Get SSD VPS Server Backups”)
        string $note        // Note
    );
```

## Delete SSD VPS Server Backup
*Deletes an SSD VPS Server’s Backup.*

```php
    $thgHostingClient->deleteSsdVpsBackup(
        int $locationId, // ID of the SSD VPS Location (see “Get SSD VPS Locations”)
        int $serverId,   // ID of the SSD VPS Server
        int $backupId    // ID of the SSD VPS Backup (see “Get SSD VPS Server Backups”)
    );
```

## Restore SSD VPS Server with Backup
*Restore an SSD VPS Backup. (Overwrites existing SSD VPS with backed up image).*

```php
    $thgHostingClient->restoreSsdVpsBackup(
        int $locationId, // ID of the SSD VPS Location (see “Get SSD VPS Locations”)
        int $serverId,   // ID of the SSD VPS Server
        int $backupId    // ID of the SSD VPS Backup (see “Get SSD VPS Server Backups”)
    );
```

## Get Service Details
*Get details for a service with net price in USD.*

```php
    $thgHostingClient->getServiceDetails(
        int $serviceId // ID of the Service
    );
```

## Get DNS Zones
*Get all DNS zones.*

```php
    $thgHostingClient->getDnsZones();
```

## Create DNS Zone
*Create a DNS zone.*

```php
    $thgHostingClient->createDnsZone(
        string $domainName, // Name of the domain
        string $ip          // IP address
    );
```

## Get DNS Zone Details
*Get DNS zone information.*

```php
    $thgHostingClient->getDnsZoneDetails(
        int $zoneId // ID of the DNS Zone (see “Get DNS Zones”)
    );
```

## Delete DNS Zone
*Remove a DNS zone.*

```php
    $thgHostingClient->deleteDnsZone(
        int $zoneId // ID of the DNS Zone (see “Get DNS Zones”)
    );
```

## Add Record to DNS Zone
*Adds a DNS record to the specified zone.*

```php
    $thgHostingClient->addRecordToDnsZone(
        int     $zoneId             // ID of the DNS Zone (see “Get DNS Zones”)
        string  $type,              // Zone type (A / AAAA etc.)
        string  $host,              // Host name or IP address
        string  $content,           // Content depending on the zone type, example: mail.hostname.com
        int     $ttl,               // Time to live - the added record or time to ping/fetch the updated records
        ?string $service    = null, // The symbolic name of the desired service. (only when record type SRV)
        ?string $protocol   = null, // The transport protocol of the desired service. (only when record type SRV)
        ?int    $port       = null, // The TCP or UDP port on which the service is to be found. (only when record type SRV)
        ?int    $weight     = null, // A relative weight for records with the same priority, higher value means higher chance of getting picked. (only when record type SRV)
        ?int    $mxPriority = null  // The priority of the target host, lower value means more preferred. (only when record type SRV or MX)
    );
```

## Update DNS Zone Record
*Update an existing DNS zone record.*

```php
    $thgHostingClient->updateDnsZoneRecord(
        int     $zoneId,            // ID of the DNS Zone (see “Get DNS Zones”)
        int     $recordId,          // ID of the DNS Zone record (see “Get DNS Zone Details”)
        string  $type,              // Zone type (A / AAAA etc.)
        string  $host,              // Host name or IP address
        string  $content,           // Content depending on the zone type, example: mail.hostname.com
        int     $ttl,               // Time to live - the added record or time to ping/fetch the updated records
        ?string $service    = null, // The symbolic name of the desired service. (only when record type SRV)
        ?string $protocol   = null, // The transport protocol of the desired service. (only when record type SRV)
        ?int    $port       = null, // The TCP or UDP port on which the service is to be found. (only when record type SRV)
        ?int    $weight     = null, // A relative weight for records with the same priority, higher value means higher chance of getting picked. (only when record type SRV)
        ?int    $mxPriority = null  // The priority of the target host, lower value means more preferred. (only when record type SRV or MX)
    );
```

## Delete DNS Zone Record
*Remove a DNS record.*

```php
    $thgHostingClient->eleteDnsZoneRecord(
        int $zoneId,  // ID of the DNS Zone (see “Get DNS Zones”)
        int $recordId // ID of the DNS Zone record
    );
```

## Get Servers
*Get a list of all servers assigned to an account.*

```php
    $thgHostingClient->getServers();
```

## Get Server Details
*Get full server profile.*

```php
    $thgHostingClient->getServerDetails(
        int $serverId // ID of the Server (see “Get Servers”)
    );
```

## Get Server Bandwidth Graph
*Get a graphical representation of the bandwidth usage for a specific server over the given period.*

```php
    $thgHostingClient->getServerBandwidthGraph(
        int    $serverId,           // ID of the Server (see “Get Servers”)
        string $periodStart = null, // Optional. And RFC3339/ISO8601 date-time string representing the start of period of the dataset. Defaults to the start of today.
        string $periodEnd   = null  // Optional. And RFC3339/ISO8601 date-time string representing the end of period of the dataset. Defaults to one month before the start of today
    );
```

## Get Tickets
*Get a list of active support tickets.*

```php
    $thgHostingClient->getTickets();
```

## Create Ticket
*Create a new support ticket.*

```php
    $thgHostingClient->createTicket(
        string $body,            // Ticket body
        string $subject,         // Subject
        int    $department  = 0, // Ticket Department - Default: 0 (General)
        int    $priority    = 0, // Priority of a Ticket (Default: 0 = Low, 1= Normal, 2 = High, 3 = Urgent)
        array  $attachments = [] // Can upload up to 2 - passed as an array, example: attachments[0] = file
    );
```

## Get Ticket Departments
*Get a list of ticket queues by department.*

```php
    $thgHostingClient->getTicketDepartments();
```

## Get Ticket Details
*Get the details of a specified support ticket.*

```php
    $thgHostingClient->getTicketDetails(
        int $ticketId // ID of the ticket (see “Get Tickets”)
    );
```

## Update Ticket
*Update status of existing support ticket.*

```php
    $thgHostingClient->updateTicket(
        int  $ticketId,           // ID of the ticket (see “Get Tickets”)
        int  $priority    = 0,    // Set to ‘close’ to close this ticket - only ‘close’ accepted
        bool $closeTicket = false // Priority of a Ticket (Default: 0 = Low, 1= Normal, 2 = High, 3 = Urgent)
    );
```

## Add Reply to Ticket
*Add reply to a specific support ticket.*

```php
    $thgHostingClient->addReplyToTicket(
        int    $ticketId,        // ID of the ticket (see “Get Tickets”)
        string $body,            // Ticket body
        array  $attachments = [] // Can upload up to 2 - passed as an array, example: attachments[0] = file
    );
```

## Get Status Updates
*Get a list of active status updates.*

```php
    $thgHostingClient->getStatusUpdates();
```

## Get Datacenters
*Returns all datacenters.*

```php
    $thgHostingClient->getDatacenters();
```

## Get Product Categories
*Returns product categories.*

```php
    $thgHostingClient->getProductCategory();
```

## Get Products
*Returns all products in category and location.*

```php
    $thgHostingClient->getProductsInCategory(
        int $locationId, // ID of the location (see “Get All Locations”)
        int $categoryId  // ID of the category (see “Get All Categories”)
    );
```

## Get Product Details
*Returns Product Details.*

```php
    $thgHostingClient->getProductDetails(
        int $locationId, // ID of the location (see “Get All Locations”)
        int $categoryId  // ID of the category (see “Get All Categories”)
        int $productId   // ID of the product (see “Get Products”)
    );
```

## Get Calculated Price with Tax
*Returns calculated price with tax for order.*

```php
    $body = [
        [
            "product_id" => 265,
            "quantity" => 1,
            "price" => 354.9,
            "datacenter_id" => 12,
            "duration_id" => 2,
            "addons" => []
        ]
    ];
    $thgHostingClient->getCalculatedPriceWithTax(
        array $body
    );
```

## Get Payment Methods
*Returns payment methods.*

```php
    $thgHostingClient->getPaymentMethods();
```

## Submit Order for Processing
*Submit order for processing.*

```php
    $body = [
        "order": [
            [
              "product_id" => 265,
              "quantity" => 1,
              "price" => 354.9,
              "datacenter_id" => 12,
              "duration_id" => 2,
              "addons" => []
            ]
        ],
        "paymentMethodId" => 21
    ];
    $thgHostingClient->submitOrderForProcessing(
        array $body
    );
```
