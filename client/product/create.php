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
        $response = $client->post("/api/product/", [
            'json' => [
                'name' => $_POST['name'],
                'stock' => $_POST['stock'],
                'consumption_date' => $_POST['consumption_date'],
                'production_date' => $_POST['production_date'],
                'create_date' => $_POST['create_date'],
                'last_modified_date' => $_POST['last_modified_date'],
                'price' => $_POST['price'],
                'category_id' => $_POST['category_id'],
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
                <label for="exampleInputPassword1">İlaç Adı:</label>
                <input type="text" name="name" class="form-control" id="exampleInputPassword1"
                       placeholder="İlaç Adı" value="">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Stok:</label>
                <input type="text" name="stock" class="form-control" id="exampleInputPassword1"
                       placeholder="Stok" value="">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Tüketim Tarihi:</label>
                <input type="datetime-local" name="consumption_date" class="form-control" id="exampleInputPassword1"
                       placeholder="Tüketim Tarihi" value="">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Üretim Tarihi:</label>
                <input type="datetime-local" name="production_date" class="form-control" id="exampleInputPassword1"
                       placeholder="Üretim Tarihi" value="">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Fiyat:</label>
                <input type="text" name="price" class="form-control" id="exampleInputPassword1"
                       placeholder="Fiyat" value="   tl">
            </div>
            <button type="submit" name="gonder" class="btn btn-primary">Ekle</button>
        </div>
    </div>
</form>
</body>
</html>
