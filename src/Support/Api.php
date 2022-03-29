<?php

namespace Wearepixel\YotpoPHP\Support;

use Exception;
use Wearepixel\YotpoPHP\Exceptions;
use GuzzleHttp\Client;

class Api
{
    private const BASE_API_URL = 'https://api.yotpo.com/';

    private ?string $appKey;
    private ?string $appSecret;
    private ?string $uToken = null;

    private Client $client;

    public function __construct(string $appKey, string $appSecret)
    {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;

        $this->client = new Client([
            'base_uri' => self::BASE_API_URL,
        ]);
    }

    public function makeRequest(string $method, string $url, ?array $body = [], ?array $options = [])
    {
        // format the url to include the appKey
        $url = sprintf('%s%s', "/core/v3/stores/{$this->appKey}", $url);

        // request a new utoken
        $this->getUToken();

        if (!$this->uToken) {
            throw new Exceptions\UnableToGenerateUToken('Unable to generate uToken for request');
            return false;
        }

        $options['headers'] = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'X-Yotpo-Token' => $this->uToken
        ];

        if ($body) {
            $options['json'] = $body;
        }

        $response = $this->client->request($method, $url, $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    private function getUToken()
    {
        try {
            $response = $this->client->post("/core/v3/stores/{$this->appKey}/access_tokens", [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'secret' => $this->appSecret,
                ],
            ]);

            $response = json_decode($response->getBody()->getContents());

            if (isset($response->access_token)) {
                $this->uToken = $response->access_token;
                return;
            }
        } catch (Exception $e) {
            if (\Str::contains($e->getMessage(), 'Couldn\'t find account')) {
                throw new Exceptions\AccountNotFound('Account not found');
            }
        }
    }
}
