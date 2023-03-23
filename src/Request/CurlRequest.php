<?php declare(strict_types=1);
/**
 * (c) The Hut Group 2001-2023, All Rights Reserved.
 *
 * This source code is the property of The Hut Group, registered address:
 *
 * 5th Floor, Voyager House, Chicago Avenue, Manchester Airport,
 * Manchester, England, M90 3DQ
 */
namespace ThgHosting\Request;

class CurlRequest implements HttpRequestInterface
{
    private $handle = null;

    public function __construct($url)
    {
        $this->handle = \curl_init($url);
    }

    public function setOption($name, $value): self
    {
        \curl_setopt($this->handle, $name, $value);
        return $this;
    }

    public function execute(): string|bool
    {
        return \curl_exec($this->handle);
    }

    public function getInfo($name): mixed
    {
        return \curl_getinfo($this->handle, $name);
    }

    public function close(): self
    {
        \curl_close($this->handle);
        return $this;
    }
}