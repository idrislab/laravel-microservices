<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Exception;

class GatewayController extends Controller
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;
    /**
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * Create a new controller instance.
     *
     * @param \Illuminate\Http\Request $request
     * @param \GuzzleHttp\Client $client
     */
    public function __construct(Request $request, Client $client)
    {
        $this->request = $request;
        $this->client = $client;
    }

    /**
     * Proxy our request
     *
     * @return \Illuminate\Http\Response
     */
    public function proxyRequest()
    {
        $endpoint = $this->request->path();
        $microservice = $this->getMicroservice($endpoint);

        if (in_array(strtolower($this->request->method()), ['head', 'get'])) {
            $var = strtoupper($microservice) . '_QUERY_SERVICE';
        } else {
            $var = strtoupper($microservice) . '_COMMAND_SERVICE';
        }

        $url = getenv($var);
        $response = $this->makeRequest($url . '/' . $endpoint);

        return $response;
    }

    /**
     * Make a request
     *
     * @param string $url
     * @return \Illuminate\Http\Response
     */
    private function makeRequest($url)
    {
        $options = [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->request->bearerToken(),
                'User' => json_encode($this->request->user()),
            ]
        ];

        if (!empty($this->request->input())) {
            $options['form_params'] = $this->request->input();
        }

        try {
            $method = $this->request->method();
            $response = $this->client->{$method}($url, $options);
        } catch (RequestException $exception) {

            if($exception->getResponse() !== null) {
                return response(
                    json_decode((string)$exception->getResponse()->getBody(), true),
                    $exception->getResponse()->getStatusCode(),
                    $exception->getResponse()->getHeaders()
                );
            }

            throw new Exception($exception->getMessage());
        }

        return response(
            json_decode((string)$response->getBody(), true),
            $response->getStatusCode(),
            $response->getHeaders()
        );
    }

    /**
     * Get microservice name
     *
     * @param string $endpoint
     *
     * @return string
     */
    private function getMicroservice($endpoint)
    {
        $apiVersion = getenv('API_VERSION');

        preg_match('/api\/' . $apiVersion . '\/([a-zA-Z0-9_\-]+)\/?/', $endpoint, $matches);

        $microservice = $matches[1];

        return $microservice;
    }
}
