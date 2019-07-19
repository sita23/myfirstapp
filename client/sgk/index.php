<?php
include_once '../vendor/autoload.php';

use GuzzleHttp\Client as HttpClient;

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


?>

<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
    <pre>



    </pre>
    <div class="row justify-content-md-center">
        <div class="col-md-4">
            <td><a class="btn btn-success float-right" href="create.php" role="button">Ekle</a></td>

            <table class="table table-dark">
                <thead>
                <tr>

                    <th scope="col">ID</th>
                    <th scope="col">Sgk Adı</th>

                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($response as $item):?>
                    <tr>
                        <th scope="row"><?=$item['id']?></th>
                        <td><?=$item['name']?></td>

                        <td><a class="btn btn-primary" href="update.php?id=<?=$item['id']?>" role="button">Güncelle</a></td>
                        <td><a class="btn btn-danger" href="delete.php?id=<?=$item['id']?>" role="button">Sil</a></td>
                    </tr>
                <? endforeach;?>
                </tbody>
            </table>
            <pre>

            </pre>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item enabled">
                        <a class="page-link" href="#" tabindex="-1"Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="index.php?page=1">1</a></li>
                    <li class="page-item"><a class="page-link" href="index.php?page=2">2</a></li>

                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</body>
</html>
