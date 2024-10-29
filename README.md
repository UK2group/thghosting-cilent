# THG Hosting Open API Client
PHP Client for easier use of Open API

```php
    $thgHostingClient = new ThgHosting\ThgHostingClient($_ENV['X-Api-Token']);
```

# Install

```bash
composer require thg/thg-client
```

# Methods

## Request
*Creates custom request to chosen EP.*

```php
    $thgHostingClient->request(
        string $method,         // Allowed methods: GET, POST, DELETE, PUT, PATCH
        string $endpoint,       // Path to chosen EP for example: "ssd-vps/plans"
        array $arguments = [],  // Body of request
        array $files     = [    // Files to send with request
            [
                // Mime type of file
                "mime" => "image/png",
                // Name of file
                "name" => "file.png",
                // Base64 encoded file
                "file" => "iVBORw0KGgoAAAANSUhEUgAAAIIAAABzCAYAAABUzdpBAAAIj0lEQVR4Xu2deVRVVRTGP0IN0QRRc8JQ0yy1XI7ZynRlTmmmYCUSKFm4HFFzKUY4g6VMag6..."
            ],
            "/path/to/file.pdf" // Absolute path to file
        ]
    );
```

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
        string  $productName,             // name of the chosen SSD VPS plan (see “Get SSD VPS Plans”)
        ?string $osComponentCode  = null, // Optional; Component code of the SSD VPS operating system (see “Get SSD VPS Operating Systems”) (you have to pass either os_component_code or custom_template_id)
        ?bool   $backups          = null, // Optional; If set to true, server will be created with Backup Protection
        ?bool   $billHourly       = null, // Optional; If set to true, billing will be set hourly, otherwise monthly billing will be used
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
    $thgHostingClient->deleteDnsZoneRecord(
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
        string $serverId // ID of the Server (see “Get Servers”)
    );
```

## Get Server Power Status
*Retrieve the current power state of the given dedicated server.*

```php
    $thgHostingClient->getServerPowerStatus(
        string $serverId // ID of the Server (see “Get Servers”)
    );
```

## Power On Server
*Request the specified dedicated server to be turned on.*

```php
    $thgHostingClient->powerOnServer(
        string $serverId // ID of the Server (see “Get Servers”)
    );
```

## Power Off Server
*Request the specified dedicated server to be turned off.*

```php
    $thgHostingClient->powerOffServer(
        string $serverId // ID of the Server (see “Get Servers”)
    );
```

## Reboot Server
*Request the specified dedicated server to be rebooted.*

```php
    $thgHostingClient->rebootServer(
        string $serverId // ID of the Server (see “Get Servers”)
    );
```

## Change Server Friendly Name
*Request friendly name change of specified dedicated server.*

```php
    $thgHostingClient->changeServerFriendlyName(
        string $serverId, // ID of the Server (see “Get Servers”)
        $body
    );
```

## Set rDNS entry for IP address
*Set rDNS entry for IP address of specified dedicated server.*

```php
    $thgHostingClient->changeServerFriendlyName(
        string $serverId, // ID of the Server (see “Get Servers”)
	string $ipAddress, //IP address the domain name should be associated with
        $body
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

## Get Server OS List
*Get a list of available operating systems for provisioning a specific server.*

```php
    $thgHostingClient->getServerOsList(
        int $serverId // ID of the Server (see “Get Servers”)
    );
```

## Reimage Server
*Reimage a server with a new operating system.*

```php
    $thgHostingClient->reimageServer(
        int    $serverId,       // ID of the Server (see “Get Servers”)
        string $osCode,         // Code of the OS to reimage the server with (see “Get Server OS List”)
        string $reason = null   // Optional. Reason for reimage
    );
```

## Create Remote Access via SoL
*Create Remote Access via SoL. You wil get a link to be opened on your browser.*

```php
    $thgHostingClient->createServerRemoteAccessSol(
        int $serverId // ID of the Server (see “Get Servers”)
    );
```

## Create Remote Access via iKVM
*Create Remote Access via iKVM. You wil get a link to be opened on your browser.*

```php
    $thgHostingClient->createServerRemoteAccessIkvm(
        int $serverId // ID of the Server (see “Get Servers”)
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
        array  $attachments = [] // How to attach files you can find described in `request` method
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
        int  $priority    = 0,    // Priority of a Ticket (Default: 0 = Low, 1= Normal, 2 = High, 3 = Urgent)
        bool $closeTicket = false // Set to true will close the ticket
    );
```

## Add Reply to Ticket
*Add reply to a specific support ticket.*

```php
    $thgHostingClient->addReplyToTicket(
        int    $ticketId,        // ID of the ticket (see “Get Tickets”)
        string $body,            // Ticket body
        array  $attachments = [] // How to attach files you can find described in `request` method
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
            "product_id"    => 265,
            "quantity"      => 1,
            "price"         => 354.9,
            "datacenter_id" => 12,
            "duration_id"   => 2,
            "addons"        => [
                [
                    "addon_id"        => 4,
                    "selected_option" => 2,
                    "price"           => 10
                ]
            ]
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

## Get Billing Services
*Returns customer services.*

```php
    $thgHostingClient->getBillingServices(
        ?bool $showAddOns,  //Show the add-ons belonging to each service
        ?string $sortBy,    //Sort by column (service_id/createdon)
        ?string $direction, //Sort direction (default asc)
        ?int $offset,       //If set, returns records starting from this offset
        ?int $limit         //If set, limits the number of records
    );
```

## Get Billing Invoices
*Returns customer invoices.*

```php
    $thgHostingClient->getBillingInvoices(
        ?int $offset,       //If set, returns records starting from this offset
        ?int $limit         //If set, limits the number of records
    );
```

## Submit Order for Processing
*Submit order for processing.*

```php
    $body = [
        "order" => [
            [
                "category_id" => 2,
                "product_id" => 265,
                "quantity" => 1,
                "price" => 354.9,
                "datacenter_id" => 12,
                "duration_id" => 2,
                "addons" => [],
                "sales_tax" => 0
            ]
        ],
        "paymentMethodId" => 21,
        "contact_data" => [
        	"address" => "816 Address",
        	"city"  => "city",
        	"company" => "Company",
        	"country" => "US",
        	"county" => "County 1",
        	"email" => "mail@mail.com",
        	"first_name" => "John",
        	"last_name" => "Doe",
        	"phone" => "+44 11 2222 3333",
        	"postcode" => "12345"
        ]
    ];
    $thgHostingClient->submitOrderForProcessing(
        array $body
    );
```

## Get Service Upgrades
*Returns a list of available upgrades for chosen service.*

```php
    $thgHostingClient->getBillingServiceUpgrades(
        int $service_id  // ID of the Service (see “Get Services”)
    );
```

## Upgrade Service
*Upgrades service.*

```php
    // Example of IP Count with all possible options, you can send only those that have value over 0
    $ipCount = [
        "vpn"         => 0,
        "sqldb"       => 0,
        "ssl_cert"    => 0,
        "terminal"    => 0,
        "application" => 0,
        "voice"       => 0,
        "media"       => 0,
        "mailing"     => 0,
        "other"       => 0,
    ];

    $thgHostingClient->upgradesService(
        int    $serviceId,      // To which server add chosen Upgrade (see “Get Services”)
        string $addonCode,      // Upgrade category code (see “Get Upgrades”)
        string $optionCode,     // Upgrade Code (see “Get Upgrades”)
        string $details = '',   // Reason explaining the need for upgrade (Required for IP Request)
        int    $quantity = 1    // The amount of Upgrades (Ignore when sending requuest for more IPs)
        ?array $ipCount = null  // Required only when sending request for new IP. Describes the amount and type of needed IPs.
    );
```

## Get Microsoft Licenses
*Returns Microsoft Licenses*
```php
    $thgHostingClient->getMicrosoftLicenses(
        int $serviceId,      // Server service ID to get a list of licenses
    );
```
## Get Microsoft License Details
*Returns Microsoft License Details*
```php
    $thgHostingClient->getMicrosoftLicenseDetails(
        int $serviceId,      // Server service ID for retrieving of an MS license details which belongs to
        int $licenseId,      // License ID to get an MS license details
    );
```
## Delete Microsoft License
*Deletes a Microsoft License*
```php
    $thgHostingClient->deleteMicrosoftLicense(
        int $serviceId,      // Server service ID for deleting of an MS license which belongs to
        int $licenseId,      // License ID to delete
    );
```
## Get Available Microsoft License Products
*Returns A List Of Microsoft License Products*
```php
    $thgHostingClient->getMicrosoftLicenseProducts(
        int $serviceId,      // Server service ID to get a list of license products
    );
```

## List SSH keys Data
*Returns A List Of SSH Keys*
```php
    $thgHostingClient->listSshKeys();
```

## Add An SSH Key
*Creates New SSH Key Record*
```php
    $thgHostingClient->createSshKey(
        string $key,        // Public RSA Key to be stored
        string $label,      // Label for SSH Key
    );
```

## Update Label For An Existing SSH Key
*Updates Label For SSH Key Given Id*
```php
    $thgHostingClient->updateSshKeyLabel(
        int $sshId,         // The ID for the SSH Key to be updated
        string $label,      // The new label for the SSH Key
    );
```

## Delete An SSH Key
*Delete An SSH Key Of The Given Id*
```php
    $thgHostingClient->deleteSshKey(
        int $sshId,         // The ID of the SSH Key to be deleted
    );
    );
```

## Get An SSH Key With Id
*Returns An SSH Key Given Id*
```php
    $thgHostingClient->getSshKeyById(
        int $sshId,      // The ID of the SSH Key to be fetched
    );
```

## Get list of server inventory
*Returns server inventory list*
```php
    $thgHostingClient->getServerInventory(
        ?int $datacenterId = null,
        ?string $cpuBrand = null,
        ?int $minCpuCores = null,
        ?int $maxCpuCores = null,
        ?float $minCpuSpeed = null,
        ?float $maxCpuSpeed = null,
        ?int $minRam = null,
        ?int $maxRam = null,
        ?string $storageType = null,
        ?int $minStorage = null,
        ?int $maxStorage = null,
        ?int $minNic = null,
        ?int $maxNic = null,
        ?float $minPrice = null,
        ?float $maxPrice = null,
        ?bool $raidEnabled = null,
        ?string $sortBy = null,
        ?string $direction = null
    );
```

# Create new bare metal server order
*Creates new bare metal server order*
```php
    $thgHostingClient->createBareMetalServerOrder(
        string $skuProductName,
        int $quantity,
        string $locationCode,
        string $operatingSystemProductCode,     //Find available OS product codes by calling the list addons endpoint
        ?string $licenseProductCode = null,     //Find available license product codes by calling the list addons endpoint
        ?int $additionalBandwidthTb = null, 
        ?string $supportLevelProductCode = null //Find available managed support product codes by calling the list addons endpoint
    );
```
# List Available addons for bare metal server order
*Returns list of all available addons for a bare metal server*
```php
    $thgHostingClient->listAvailableBareMetalAddons(
        string $skuProductName, 
        string $locationCode
    );
```