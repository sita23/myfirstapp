<?php
include_once '../vendor/autoload.php';

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;


if (isset($_GET['id'])) {
    $sgkId = $_GET['id'];

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

    try {
        $request = $client->request("GET", "/api/sgk/" . $sgkId);
        $sgk = json_decode($request->getBody()->getContents(), true);

        //echo json_encode($response);
    } catch (ClientException $e) {
        if ($e->getCode() === 404) {
            header("Location: index.php");
        }
    }

    if (isset($_POST['update'])) {
        try {
            $response = $client->put("/api/sgk/" . $sgkId, [
                'json' => [
                    'name' => $_POST['name'],

                ]
            ]);


            if ($response->getStatusCode() == 201) {
                header("Refresh:0");

            }
        }
        catch (ClientException $e) {
            if ($e->getCode() == 400) {
                $content = json_decode($e->getResponse()->getBody()->getContents(), true);
                echo $content['message'];
                // header( "refresh:2; " );
                //header("Refresh:2");
                exit;
            }
        }
    }
}
?>

<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
              integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
                integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
                crossorigin="anonymous">
        </script>
    </head>
        <pre>

        </pre>
    <body>
        <form action="" method="post">
            <div class="row justify-content-md-center">
                <div class="col-md-4">

                    <div class="form-group">
                        <label for="exampleInputPassword1">Sgk Adı:</label>
                        <input type="text" name="name" class="form-control" id="exampleInputPassword1"
                               placeholder=" Sgk adı" value="<?=$sgk['name']?>">
                    </div>


                    <button type="submit" name="update" class="btn btn-primary">Güncelle</button>
                </div>
            </div>
        </form>
    </body>
</html>
