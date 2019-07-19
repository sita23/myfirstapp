<?php
include_once '../vendor/autoload.php';

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;

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
                        <label for="exampleInputPassword1">Kullanıcı Adı:</label>
                        <input type="text" name="user_name" class="form-control" id="exampleInputPassword1"
                               placeholder="kullanıcı adı" value="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">E-posta:</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1"
                               aria-describedby="emailHelp" placeholder="e-posta" value="">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Şifre:</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1"
                               placeholder="şifre" value="">
                    </div>

                    <button type="submit" name="gonder" class="btn btn-primary">Ekle</button>
                </div>
            </div>
        </form>
    </body>
</html>
