<?php
/**
 * (c) The Hut Group 2001-2022, All Rights Reserved.
 *
 * This source code is the property of The Hut Group, registered address:
 *
 * 5th Floor, Voyager House, Chicago Avenue, Manchester Airport,
 * Manchester, England, M90 3DQ
 * @author Nha Nguyen <Nguyen-XuanN@thg.com>
 */

namespace ThgHosting;

trait MicrosoftLicense
{
    /**
     * Create custom request to Open API
     *
     * @param string $method
     * @param string $endpoint
     * @param array $arguments
     * @param array $files
     * @param string $contentType
     * @param int|null $timeout
     * @return mixed
     */
    abstract public function request(
        string $method,
        string $endpoint,
        array  $arguments = [],
        array  $files = [],
        string $contentType = ThgHostingClient::CONTENT_JSON,
        ?int   $timeout = null
    );

    /**
     * Retrieves a list of MS licenses
     *
     * @param int $serviceId
     * @return array|string
     * @throws ThgHostingException
     */
    public function getMicrosoftLicenses(int $serviceId)
    {
        return $this->request(ThgHostingClient::GET, "/services/$serviceId/licenses");
    }

    /**
     * Retrieves an MS license details
     *
     * @param int $serviceId
     * @param int $licenseId
     * @return array|string
     * @throws ThgHostingException
     */
    public function getMicrosoftLicenseDetails(int $serviceId, int $licenseId)
    {
        return $this->request(ThgHostingClient::GET, "/services/$serviceId/licenses/$licenseId");
    }

    /**
     * Deletes an MS license
     *
     * @param int $serviceId
     * @param int $licenseId
     * @return array|string
     * @throws ThgHostingException
     */
    public function deleteMicrosoftLicense(int $serviceId, int $licenseId)
    {
        return $this->request(ThgHostingClient::DELETE, "/services/$serviceId/licenses/$licenseId");
    }
}
