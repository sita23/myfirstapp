<?php

use Phalcon\Mvc\Controller;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException as HttpRequestException;

class UserController extends Controller
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
        $request = $client->request("GET", "/api/user?page=". $page);
        $response = json_decode($request->getBody()->getContents(), true);

        $this->view->response = $response;

    }

    public function createAction()
    {
        $client = new HttpClient([
            'base_uri' => "http://firstapp",
            'timeout' => 2.0,
            'headers' => [
                'Content-Type' => 'application/json',
            ]
        ]);


        if (isset($_POST['gonder'])) {
            try {
                $response = $client->post("/api/user/", [
                    'json' => [
                        'user_name' => $_POST['user_name'],
                        'email' => $_POST['email'],
                        'password' => $_POST['password']
                    ]
                ]);

                if ($response->getStatusCode() == 201) {
                    header("Location: index.php");

                }
            }
            catch (ClientException $e) {
                if ($e->getCode()== 400) {
                    $content = json_decode($e->getResponse()->getBody()->getContents(), true);
                    echo $content['message'];
                    // header( "refresh:2; " );
                    header("Refresh:2");
                    exit;
                }
            }
        }
    }

    public function deleteAction()
    {

    }

    public function updateAction()
    {

    }
}