<?php
include_once '../vendor/autoload.php';

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;


if (isset($_GET['id'])){
    $userId = $_GET['id'];

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
        $request = $client->request("GET", "/api/user/" . $userId);
        $user = json_decode($request->getBody()->getContents(), true);

        //echo json_encode($response);
    } catch (ClientException $e) {
        if ($e->getCode() === 404) {
            header("Location: index.php");
        }
    }

    if (isset($_POST['update'])) {
        $response = $client->put("/api/user/".$userId, [
            'json' => [
                'email' => $_POST['email'],
                'user_name' =>$_POST['user_name'],
                'password' =>$_POST['password']
            ]
        ]);


        if ($response->getStatusCode() == 201) {
            header("Refresh:0");

        }
    }

?>
<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </head>
        <pre>

        </pre>
        <body>
        <form action="" method="post">
            <div class="row justify-content-md-center">
                <div class="col-md-4">

                    <div class="form-group">
                        <label for="exampleInputPassword1">Kullanıcı Adı:</label>
                        <input type="text" name="user_name" class="form-control" id="exampleInputPassword1" placeholder="kullanıcı adı" value="<?=$user['user_name']?>">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">E-posta:</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="e-posta" value="<?=$user['email']?>">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Şifre:</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="şifre" value="<?=$user['password']?>">
                    </div>

            <button type="submit" name="update" class="btn btn-primary">Güncelle</button>
            </div>
            </div>
        </form>
        </body>
</html>

<?php
} else {
    header("Location: ../");
}
?>






























