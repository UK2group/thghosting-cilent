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

interface HttpRequestInterface
{
    public function setOption($name, $value): self;
    public function execute(): mixed;
    public function getInfo($name): mixed;
    public function close(): self;
}