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
namespace Thg;

use ThgException;

/**
 * THG Hosting API Client
 */
final class ThgClient
{
    private $host = 'https://api.thghosting.com/rest-api/';
    private const GET    = "GET";
    private const POST   = "POST";
    private const DELETE = "DELETE";
    private const PUT    = "PUT";
    private const PATCH  = "PATCH";
    private $timeout = 500;
    private $allowedMethods = [
        self::GET    => [CURLOPT_HTTPGET, true],
        self::POST   => [CURLOPT_POST, true],
        self::DELETE => [CURLOPT_CUSTOMREQUEST, self::DELETE],
        self::PUT    => [CURLOPT_PUT, true],
        self::PATCH  => [CURLOPT_CUSTOMREQUEST, self::PATCH]
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
     * @param  string $method    Allowed methods: GET, POST, DELETE, PUT, PATCH
     * @param  string $endpoint  Path to the chosen End Point
     * @param  array  $arguments Optional; Arguments to addtionally send
     * @return array             Result of request
     */
    public function request(string $method, string $endpoint, array $arguments = []): array
    {
        if (!$this->validateMethod($method)) {
            throw new ThgException("Not allowed method used. Allowed: " . implode(', ', array_keys($allowedMethods)), 405);
        }

        $requestParams = $this->allowedMethods[$method];

        $curl = curl_init();

        curl_setopt($curl, ...$requestParams);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);

        $url = $this->host . trim('/', $endpoint) . '/';

        if ($method === self::GET && \sizeof($arguments) > 0) {
            $query = http_build_query($arguments);
            $url .= "?" . $query;
        } else {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $arguments);
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        $result = curl_exec($curl);
        curl_close($curl);

        var_dump($result);
        return $result;
    }

}
