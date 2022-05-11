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

class ThgHostingTest extends ThgHostingClient
{
    public const PROD_ENV  = 'prod';
    public const DEV_ENV   = 'dev';
    public const LOCAL_ENV = 'local';

    public function __construct(string $env = self::DEV_ENV)
    {
        $path = '../test/keys/env.php';
        if (!\is_file($path)) {
            throw new ThgHostingException('Env file ' . __DIR__ . $path . " not found.", 404);
        }
        $_ENV = require $path;

        if ($env === self::PROD_ENV && !isset($_ENV['X-Api-Token-Test-Prod'])) {
            throw new ThgHostingException("Env variable `X-Api-Token-Test-Prod` not found.", 404);
        } elseif ($env === self::LOCAL_ENV && !isset($_ENV['X-Api-Token-Test-Local'])) {
            throw new ThgHostingException("Env variable `X-Api-Token-Test-Local` not found.", 404);
        } elseif (!isset($_ENV['X-Api-Token-Test'])) {
            throw new ThgHostingException("Env variable `X-Api-Token-Test` not found.", 404);
        }

        $envs = [
            self::PROD_ENV => [
                "token" => $_ENV['X-Api-Token-Test-Prod'],
                "url"   => 'https://api.thghosting.com/rest-api/'
            ],
            self::LOCAL_ENV => [
                "token" => $_ENV['X-Api-Token-Test-Local'],
                "url" => $_ENV['Local-Gateway-Url']
            ],
            self::DEV_ENV => [
                "token" => $_ENV['X-Api-Token-Test'],
                "url" => $_ENV['Dev-Gateway-Url']
            ],
            'default' => [
                "token" => $_ENV['X-Api-Token-Test'],
                "url" => $_ENV['Dev-Gateway-Url']
            ]
        ];

        $env = $envs[$env] ?? $envs['default'];

        parent::__construct($env["token"]);
        $this->host = $env["url"];
    }

    public function generateMethods(): string
    {
        $rf = new \ReflectionClass($this);
        $methodString = '';
        foreach ($rf->getMethods() as $method) {
            $params = $method->getParameters();
            $methodString .=  $method->getName() . ' => [';
            foreach ($params as $param) {
                $methodString .= $param->name . ', ';
            }
            $methodString .= rtrim($methodString, ', ') . '],<br>';
        }
        return $methodString;
    }

    public function generatePreview(array $array): string
    {
        $preview = "<ol start='0'>";
        if (\sizeof($array) == 0) {
            $preview .= "<li><span class=\"info_type\">[EMPTY]</span></li>";
        }
        foreach ($array as $key => $value) {
            $preview .= "<li>";
            if (\is_array($value)) {
                $preview .= "(<span class=\"info_type\">" . gettype($value) . "</span>) [" . \sizeof($value) . "] " . $key . ":" . PHP_EOL;
                $preview .= $this->generatePreview($value);
            } else {
                $preview .= "(<span class=\"info_type\">" . gettype($value) . "</span>) <span class=\"info_key\">$key</span> => `$value`";
            }
            $preview .= "</li>";
        }
        $preview .= "</ol>";
        return $preview;
    }

    public function testMethod(string $method, array $args = []): string
    {
        $successStatuses = [200, 201];
        $resString = "Method: $method". "(\n" . $this->generatePreview($args) . ")\n";
        $res = $this->$method(...$args);
        $resString .= "Results:";
        $message = ($res['data']['message'] ?? null) ?? ($res['data']['error']['description'] ?? $res['data']);
        $statusCode = $res['data']['status_code'] ?? 500;
        if (!in_array(($res['data']['status_code'] ?? 500), $successStatuses)) {
            $resString .= ' returned an error - ' . $message . PHP_EOL;
            if (isset($res['info'])) {
                $resString .= "Info:" . PHP_EOL;
                $resString .= $this->generatePreview($res['info']);
            }
            echo $resString;
            throw new \Exception(
                (string) ($message ?? gettype($message)),
                $statusCode
            );
        }
        $resString .= PHP_EOL . $this->generatePreview($res['data']);
        return $resString;
    }
}
