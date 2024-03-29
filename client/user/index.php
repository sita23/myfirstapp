<?php
include_once '../vendor/autoload.php';

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException as HttpRequestException;

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

?>
<html>
 <head>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
     <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
 </head>
    <body>
    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">

       a <a class="navbr-brand" href="https://www.istanbuleczaciodasi.org.tr/nobetci-eczane/mobile.php#nobet-select-method">Pharmacy</a>


        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="http://localhost:8092/user/index.php">User</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="http://localhost:8092/sgk/index.php">Sgk</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="http://localhost:8092/company/index.php">Company</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="http://localhost:8092/company/index.php">Product</a>
            </li>
        </ul>
    </nav>
    <pre>

</pre>

    <div class="row justify-content-md-center">
        <div class="col-md-8">

            <a class="btn btn-success float-right" href="create.php" role="button">Ekle</a>
            <table class="table table-dark">
                <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Kullanıcı Adı</th>
                    <th scope="col">Şifre</th>
                    <th scope="col">E-posta</th>
                    <th scope="col">Oluşturulma Tarihi</th>
                    <th scope="col">Son Güncelleştirilme Tarihi</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($response as $item):?>
                <tr>
                    <th scope="row"><?=$item['id']?></th>
                    <td><?=$item['user_name']?></td>
                    <td><?=$item['password']?></td>
                    <td><?=$item['email']?></td>
                    <td><?=$item['created_at']?></td>
                    <td><?=$item['last_modified_at']?></td>
                    <td><a class="btn btn-primary" href="update.php?id=<?=$item['id']?>" role="button">Güncelle</a></td>
                    <td><a class="btn btn-danger" href="delete.php?id=<?=$item['id']?>" role="button">Sil</a></td>
                </tr>
                <? endforeach;?>
                </tbody>
            </table>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item enabled">
                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                    </li>
                    <li class="page-item"><a class="page-link" href="index.php?page=1">1</a></li>
                    <li class="page-item"><a class="page-link" href="index.php?page=2">2</a></li>
                    <li class="page-item"><a class="page-link" href="index.php?page=3">3</a></li>
                    <li class="page-item"><a class="page-link" href="index.php?page=4">4</a></li>
                    <li class="page-item"><a class="page-link" href="index.php?page=5">5</a></li>
                    <li class="page-item"><a class="page-link" href="index.php?page=6">6</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    </body>
</html>
