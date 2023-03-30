<?php

namespace ZapMeSdk\Traits;

use Exception;

trait PerformRequest
{
    /**
     * The default request method.
     *
     * @var string
     */
    private string $method = 'POST';

    /**
     * Handles the request method.
     *
     * @param  string  $method
     * @return void
     */
    private function method(string $method = 'POST'): void
    {
        $this->method = $method;
    }

    /**
     * Send the request to the ZapMe.
     */
    private function request(string $path, array $data = []): array
    {
        $data += ['api'=> $this->api, 'secret' => $this->secret];

        $curl = curl_init($this->url . $path);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->method === 'DELETE' ? sprintf('api=%s&secret=%s', $this->api, $this->secret) : $data);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $this->method);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($curl);

        return json_decode($result, true);
    }
}
