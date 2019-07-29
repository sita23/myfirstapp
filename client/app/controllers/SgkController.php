<?php

use Phalcon\Mvc\Controller;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException as HttpRequestException;
class SgkController extends Controller
{
    public function indexAction()
    {

        $host = "http://firstapp";
        $token = "";
        $headers = [
            'Content-Type' => 'application/json',
        ];
        if (!empty($token)) {
            $headers['Authorization'] = sprintf('Bearer %s', $this->token);
        }
        $client = new HttpClient([
            'base_uri' => $host,
            'timeout' => 2.0,
            'headers' => $headers,
        ]);

        $page = 1;
        if (!empty($_GET['page'])) {
            $page = $_GET['page'];
        }
        $request = $client->request("GET", "/api/sgk?page=". $page);
        $response = json_decode($request->getBody()->getContents(), true);

        $this->view->response = $response;

    }

    public function createAction()
    {

    }

    public function deleteAction()
    {

    }

    public function updateAction()
    {

    }
}