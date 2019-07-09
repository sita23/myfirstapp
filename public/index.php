<?php

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Sevo\Model\Category;
use Sevo\Model\Sales;
use Sevo\Model\Sgk;
use Sevo\Model\User;
use Sevo\Model\Product;
use Sevo\Model\Patient;
use Sevo\Model\Company;


// Use Loader() to autoload our model
$loader = new Loader();

$loader->registerNamespaces(
    [
        'Sevo\Model' => __DIR__ . '/models/',
    ]
);

$loader->register();

$di = new FactoryDefault();

// Set up the database service
$di->set(
    'db',
    function () {
        return new PdoMysql(
            [
                'host' => 'db',
                'username' => 'root',
                'password' => '123456',
                'dbname' => 'sevo',
            ]
        );
    }
);

// Create and bind the DI to the application
$app = new Micro($di);

$app->get(
    '/',
    function () use ($app) {
        echo "Anasayfa";
    }
);

// bu tüm kullanıcıları listeler.
$app->get(
    '/api/users',
    function () use ($app) {
        $users = User::find();
        echo json_encode($users);
    }
);


$app->get(
    '/api/users/{id}',
    function ($id) {
        $user = User::findFirst($id);
        echo json_encode($user);
    }
);

$app->post(
    '/api/users',
    function () use ($app) {
        // Getting a request instance
        $userObject = $app->request->getJsonRawBody();

        $user = new User();
        $user->setUserName($userObject->user_name);
        $user->setEmail($userObject->email);
        $user->setPassword($userObject->password);
        $user->setCreatedAt(date('Y-m-d H:i:s'));

        $user->save();

        //echo "Merhaba, ".$user->getUserName();
        //echo sprintf("Merhaba, %s", $user->getPassword());
    }
);

$app->put(
    '/api/users/{id}',
    function ($id) use ($app) {
        $userObject = $app->request->getJsonRawBody();

        /** @var User $user */
        $user = User::findFirst($id);
        $user->setUserName($userObject->user_name);
        $user->setEmail($userObject->email);
        $user->setPassword($userObject->password);
        $user->setLastModifiedAt(date('Y-m-d H:i:s'));

        $user->update();
    }
);

$app->delete(
    '/api/users/{id}',
    function ($id) {
        $user = User::findFirst($id);

        if (empty($user)) {
            echo json_encode(["message" => "böyle bir kullanıcı yok"]);
        }

        if ($user->delete()) {
            echo json_encode(["message" => "silindi"]);
        }
    }
);


// Searches for robots with $name in their name
$app->get(
    '/api/robots/search/{name}',
    function ($name) {
        // Operation to fetch robot with name $name
    }
);

// Retrieves robots based on primary key
$app->get(
    '/api/robots/{id:[0-9]+}',
    function ($id) {
        // Operation to fetch robot with id $id
    }
);

// Adds a new robot
$app->post(
    '/api/robots',
    function () {
        // Operation to create a fresh robot
    }
);

// Updates robots based on primary key
$app->put(
    '/api/robots/{id:[0-9]+}',
    function ($id) {
        // Operation to update a robot with id $id
    }
);

// Deletes robots based on primary key
$app->delete(
    '/api/robots/{id:[0-9]+}',
    function ($id) {
        // Operation to delete the robot with id $id
    }
);


$app->get(
    '/api/sgk',
    function () use ($app) {
        $sgk = Sgk::find();
        echo json_encode($sgk);
    }
);

$app->post(
    '/api/sgk',
    function () use ($app) {
        // Getting a request instance
        $sgkObject = $app->request->getJsonRawBody();

        $sgk = new Sgk();
        $sgk->setName($sgkObject->name);

        $sgk->save();
    }

);

$app->put(
    '/api/sgk/{id}',
    function ($id) use ($app) {
        $sgkObject = $app->request->getJsonRawBody();

        /** @var Sgk $sgk */
        $sgk = Sgk::findFirst($id);
        $sgk->setName($sgkObject->name);

        $sgk->update();
    }
);

$app->delete(
    '/api/sgk/{id}',
    function ($id) {
        $sgk = Sgk::findfirst($id);
        if (empty($sgk)) {
            echo json_encode(["message" => "böyle bir kullanıcı yok"]);
        }

        if ($sgk->delete()) {
            echo json_encode(["message" => "silindi"]);
        }
    }
);

$app->post(
    '/api/sales',
    function () use ($app) {
        $salesObject = $app->request->getJsonRawBody();
        $sales = new Sales();

        $sales->setCreatedAt(date('Y-m-d H:i:s'));
        $sales->setTotal($salesObject->total);

        $sales->save();
    }
);
//tüm sales kayıtlarını listeler.
$app->get(
    '/api/sales',
    function () use ($app) {
        $sales = Sales::find();
        echo json_encode($sales);
    }
);

$app->get(
    '/api/sales/{id}',
    function ($id) {
        $sales = Sales::findfirst($id);
        echo json_encode($sales);
    }
);

$app->delete(
    '/api/sales/{id}',
    function ($id) {
        $sales = Sales::findfirst($id);
        if (empty($sales)) {
            echo json_encode(["message" => "böyle bir kullanıcı yok"]);
        }

        if ($sales->delete()) {
            echo json_encode(["message" => "silindi"]);
        }
    }
);

$app->put(
    '/api/sales/{id}',
    function ($id) use ($app) {
        $salesObject = $app->request->getJsonRawBody();

        /** @var Sales $sales */
        $sales = Sales::findFirst($id);
        $sales->setTotal($salesObject->total);

        $sales->update();
    }
);

$app->post(
    '/api/product',
    function () use ($app) {
        $productObject = $app->request->getJsonRawBody();
        $product = new Product();

        $product->setName($productObject->name);
        $product->setStock($productObject->stock);
        $product->setConsumptionDate($productObject->consumption_date);
        $product->setProductionDate($productObject->production_date);
        $product->setPrice($productObject->price);
        $product->setCreatedAt(date('Y-m-d H:i:s'));

        $product->save();
    }
);

$app->get(
    '/api/product',
    function () use ($app) {
        $product = Product::find();
        echo json_encode($product);
    }
);

$app->get(
    '/api/product/{id}',
    function ($id) {
        $product = Product::findFirst($id);
        echo json_encode($product);
    }
);

$app->put(
    '/api/product/{id}',
    function ($id) use ($app) {
        $productObject = $app->request->getJsonRawBody();

        /** @var Product $product */
        $product = Product::findfirst($id);

        $product->setStock($productObject->stock);
        $product->setConsumptionDate($productObject->consumption_date);
        $product->setProductionDate($productObject->production_date);
        $product->setLastModifiedAt(date('Y-m-d H:i:s'));
        $product->setPrice($productObject->price);

        $product->update();
    }
);

$app->delete(
    '/api/product/{$id}',
    function ($id) {
        $product = Product::findFirst($id);
        if (empty($product)) {
            echo json_encode(["message" => "böyle bir kullanıcı yok"]);
        }

        if ($product->delete()) {
            echo json_encode(["message" => "silindi"]);
        }
    }
);

$app->post(
    '/api/patient',
    function () use ($app) {
        $patientObject = $app->request->getJsonRawBody();
        $patient = new Patient();

        $patient->setTc($patientObject->tc);
        $patient->setSurName($patientObject->surname);
        $patient->setName($patientObject->name);
        $patient->setAddress($patientObject->address);

        $patient->save();
    }
);

$app->get(
    '/api/patient',
    function () use ($app) {
        $patient = Patient::find();
        echo json_encode($patient);
    }
);

$app->get(
    '/api/patient/{id}',
    function ($id) {
        $patient = Patient::findFirst($id);
        echo json_encode($patient);
    }
);

$app->put(
    '/api/patient/{id}',
    function ($id) use ($app) {
        $patientObject = $app->request->getJsonRawBody();

        /** @var Patient $patient */
        $patient = Patient::findfirst($id);

        $patient->setTc($patientObject->tc);
        $patient->setSurName($patientObject->surname);
        $patient->setName($patientObject->name);
        $patient->setAddress($patientObject->address);

        $patient->update();
    }
);

$app->delete(
    '/api/patient/{id}',
    function ($id) {
        $patient = Patient::findFirst($id);
        if (empty($patient)) {
            echo json_encode(["message" => "böyle bir kullanıcı yok"]);
        }

        if ($patient->delete()) {
            echo json_encode(["message" => "silindi"]);
        }
    }
);

$app->post(
    '/api/company',
    function () use ($app) {
        $companyObject = $app->request->getJsonRawBody();
        $company = new Company();

        $company->setName($companyObject->name);
        $company->setPhoneNumber($companyObject->phone_number);
        $company->setCreatedAt(date('Y-m-d H:i:s'));

        $company->save();
    }
);

$app->get(
    '/api/company',
    function () use ($app) {
        $company = Company::find();
        echo json_encode($company);
    }
);

$app->get(
    '/api/company/{id}',
    function ($id) {
        $company = Company::findFirst($id);
        echo json_encode($company);
    }

);

$app->put(
    '/api/company/{id}',
    function ($id) use ($app) {
        $companyObject = $app->request->getJsonRawBody();

        /** @var Company $company */
        $company = Company::findfirst($id);

        $company->setName($companyObject->name);
        $company->setPhoneNumber($companyObject->phone_number);
        $company->setCreatedAt(date('Y-m-d H:i:s'));

        $company->update();
    }
);

$app->delete(
    '/api/company/{id}',
    function ($id) {
        $company = Company::findFirst($id);
        if (empty($company)) {
            echo json_encode(["message" => "böyle bir kullanıcı yok"]);
        }

        if ($company->delete()) {
            echo json_encode(["message" => "silindi"]);
        }
    }
);

$app->post(
    '/api/category',
    function () use ($app) {
        $categoryObject = $app->request->getJsonRawBody();
        $category = new Category();

        $category->setName($categoryObject->name);

        $category->save();
    }
);

$app->get(
    '/api/category',
    function () use ($app) {
        $category = Category::find();
        echo json_encode($category);
    }
);

$app->get(
    '/api/category/{id}',
    function ($id) {
        $category = Category::findFirst($id);
        echo json_encode($category);
    }
);

$app->put(
    '/api/category/{id}',
    function ($id) use ($app) {
        $categoryObject = $app->request->getJsonRawBody();

        /** @var Category $category */
        $category = Category::findfirst($id);

        $category->setName($categoryObject->name);

        $category->update();
    }
);

$app->delete(
    '/api/category/{id}',
    function ($id) {
        $category = Category::findFirst($id);
        if (empty($category)) {
            echo json_encode(["message" => "böyle bir kullanıcı yok"]);
        }

        if ($category->delete()) {
            echo json_encode(["message" => "silindi"]);
        }
    }
);
$app->handle();