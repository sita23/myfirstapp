<?php
date_default_timezone_set('Europe/Istanbul');

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Model\Resultset;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Sevo\Model\Category;
use Sevo\Model\Sales;
use Sevo\Model\Sgk;
use Sevo\Model\Token;
use Sevo\Model\User;
use Sevo\Model\Product;
use Sevo\Model\Patient;
use Sevo\Model\Company;
use Sevo\Model\CompanyProduct;
use Sevo\Helper\Response as HttpResponse;
use Sevo\Helper\Paginator;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;

$loader = new Loader();

$loader->registerNamespaces(
    [
        'Sevo\Model' => __DIR__ . '/models/',
        'Sevo\Validation' => __DIR__ . '/validations/',
        'Sevo\Helper' => __DIR__ . '/helpers',
    ]
);

$loader->register();

$di = new FactoryDefault();

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

$app = new Micro($di);

$app->notFound(
    function () use ($app) {
        $app->response->setStatusCode(404, 'Not Found');
        $app->response->sendHeaders();

        $message = 'Nothing to see here. Move along....';
        $app->response->setContent($message);
        $app->response->send();
    }
);

$app->before(
    function () use ($app) {
        $app->authorizedToken = null;
        $httpHeaders = $app->request->getHeaders();
        if (isset($httpHeaders['Authorization'])) {
            if (strpos($httpHeaders['Authorization'], 'Bearer') !== false || strpos($httpHeaders['Authorization'], 'bearer') !== false) {
                $tokenTmp = explode(' ', $httpHeaders['Authorization']);
                $tokenType = $tokenTmp[0];
                if (!isset($tokenTmp[1])) {
                    $app->response->setStatusCode(401, "");
                    $app->response->send();
                    exit;
                }
                $token = $tokenTmp[1];

                /** @var Token $tokenModel */
                $tokenModel = Token::findFirst([
                    'conditions' => 'token = ?1 AND expiration_date > ?2 AND status = 1',
                    'bind' => [
                        1 => $token,
                        2 => date("Y-m-d H:i:s"),
                    ]
                ]);
                if (!$tokenModel) {
                    $app->response->setStatusCode(401, "");
                    $app->response->send();
                    exit;
                }
                $app->authorizedToken = $tokenModel;
            }
        }
        return true;
    }
);

$app->after(
    function () use ($app) {
        $returnedValue = $app->getReturnedValue();
        if (is_object($returnedValue) || is_array($returnedValue)) {
            if ($returnedValue instanceof \Sevo\Helper\Response) {
                /** @var \Sevo\Helper\Response $responseObject */
                $responseObject = $returnedValue;
                $app->response->setStatusCode($returnedValue->getStatusCode(), $returnedValue->getStatusText());
                $app->response->sendHeaders($returnedValue->getHeaders());

                if (is_array($returnedValue->getContent()) || is_object($returnedValue->getContent())) {
                    $app->response->setContent(json_encode($returnedValue->getContent()));
                } else {
                    $app->response->setContent($returnedValue->getContent());
                }
                $app->response->send();
            } else {
                echo json_encode($returnedValue);
            }
        } else {
            echo $returnedValue;
        }
    }
);

$app->error(
    function ($exception) {
        echo json_encode(
            [
                'code' => $exception->getCode(),
                'status' => 'error',
                'message' => $exception->getMessage(),
            ]
        );
    }
);
$app->post(
    '/api/models/validation',
    function () use ($app) {
        $testValidation = new User();

        if ($testValidation->save() === false) {
            $validationMsg = [];
            $messages = $testValidation->getMessages();

            foreach ($messages as $message) {
                $validationMsg[] = [
                    'message' => $message->getMessage(),
                    'field' => $message->getField(),
                    'type' => $message->getType(),
                ];

            }
            echo json_encode($validationMsg);
        }
    }
);

$app->get(
    '/',
    function () use ($app) {
        $gezegenler = array('Mars', 'Neptün', 'Jüpiter', 'Satürn', 'Dünya');

        $diziSayisi = count($gezegenler);

        echo "- " . $gezegenler[0] . "<br>";
        echo "- " . $gezegenler[1] . "<br>";
        echo "- " . $gezegenler[2] . "<br>";
        echo "- " . $gezegenler[3] . "<br>";
        echo "- " . $gezegenler[4] . "<br>";
        echo "Dizi içerisinde bulunan eleman sayısı " . $diziSayisi;
    }
);
$app->post(
    '/api/login',
    function () use ($app) {
        $LoginRequestObject = $app->request->getJsonRawBody();
        $LoginValidation = new \Sevo\Validation\LoginValidation();
        $messages = $LoginValidation->validate($LoginRequestObject);

        if (count($messages)) {
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }

        $user = User::findFirst(
            [
                'conditions' => 'email = ?1 AND password = ?2',
                'bind' => [
                    1 => $LoginRequestObject->email,
                    2 => $LoginRequestObject->password,
                ]
            ]
        );
        if (!$user) {
            return new HttpResponse('', HttpResponse::HTTP_UNAUTHORIZED, HttpResponse::$statusTexts[HttpResponse::HTTP_UNAUTHORIZED]);
        }

        $token = new Token();

        $tokenText = md5(time() . uniqid(md5($LoginRequestObject->email . rand(1000, 9999))));

        $expiredMinute = 60;

        $expirationDate = new DateTime();
        $expirationDate->add(new DateInterval('PT' . $expiredMinute . 'M'));

        $token->setToken($tokenText);
        $token->setExpirationDate($expirationDate->format('Y-m-d H:i:s'));
        $token->setStatus(1);
        $token->setUserId($user->getId());

        if ($token->save() === false) {
            $messages = $token->getMessages();
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }

        return new HttpResponse([
            'token' => $tokenText,
            'expired' => $expirationDate->format('Y-m-dTH:i:s'),
        ]);

    }
);


$app->get(
    '/api/user',
    function () use ($app) {
        $paginator = new Paginator($app->request->get('page', 'int', 1), 5, 30);

        $user = User::find([
            'offset' => $paginator->getOffset(),
            'limit' => $paginator->getLimit(),
            'order' => 'created_at DESC',
        ]);
        return new HttpResponse($user, HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
    }
);

$app->get(
    '/api/user/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) {
        $user = User::findFirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );
        if (!$user) {
            return new HttpResponse('', HttpResponse::HTTP_NOT_FOUND, HttpResponse::$statusTexts[HttpResponse::HTTP_NOT_FOUND]);
        }
        return new HttpResponse($user, HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
    }
);

$app->post(
    '/api/user',
    function () use ($app) {
        // $data['rel_patient'] = $sales->getPatient()->toArray();

        // Getting a request instance
        $userObject = $app->request->getJsonRawBody();

        $user = new User();
        $user->setUserName($userObject->user_name);
        $user->setEmail($userObject->email);
        $user->setPassword($userObject->password);

        if ($user->save() === false) {
            $messages = $user->getMessages();
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }
        return new HttpResponse($user, HttpResponse::HTTP_CREATED, HttpResponse::$statusTexts[HttpResponse::HTTP_CREATED]);
    }
);

$app->put(
    '/api/user/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) use ($app) {
        $userObject = $app->request->getJsonRawBody();

        /** @var User $user */
        $user = Company::findfirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );

        $user->setUserName($userObject->user_name);
        $user->setEmail($userObject->email);
        $user->setPassword($userObject->password);


        if ($user->update() === false) {
            $messages = $user->getMessages();
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }
        return new HttpResponse($user, HttpResponse::HTTP_CREATED, HttpResponse::$statusTexts[HttpResponse::HTTP_CREATED]);
    }
);


$app->delete(
    '/api/user/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) {
        $user = User::findFirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );

        if (empty($user)) {
            return new HttpResponse($user, HttpResponse::HTTP_NOT_FOUND, HttpResponse::$statusTexts[HttpResponse::HTTP_NOT_FOUND]);
        }
        if ($user->delete()) {
            return new HttpResponse('', HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
        }
    }
);

$app->get(
    '/api/test',
    function () use ($app) {
        $app->response->setStatusCode(201, 'created');
        $app->response->sendHeaders();

        $message = 'great';
        $app->response->setContent($message);
        $app->response->send();
    }
);

$app->get(
    '/api/sgk',
    function () use ($app) {
        $currentPage = $app->request->get('page', 'int', 1);
        $sgk = Sgk::find([
            'conditions' => 'name = ?1',
            'bind' => [
                1 => 'sgk',
            ]
        ]);

        $paginator = new PaginatorModel(
            [
                'data' => $sgk,
                'limit' => 1,
                'page' => $currentPage,
            ]
        );
        $page = $paginator->getPaginate();

        return new HttpResponse($page, HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
    }
);

$app->get(
    '/api/sgk/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) {
        $sgk = Sgk::findFirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );
        if (!$sgk) {
            return new HttpResponse('', HttpResponse::HTTP_NOT_FOUND, HttpResponse::$statusTexts[HttpResponse::HTTP_NOT_FOUND]);
        }
        return new HttpResponse($sgk, HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
    }
);

$app->post(
    '/api/sgk',
    function () use ($app) {
        // Getting a request instance
        $sgkObject = $app->request->getJsonRawBody();

        $sgk = new Sgk();
        $sgk->setName($sgkObject->name);

        if ($sgk->save() === false) {
            $messages = $sgk->getMessages();
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }
        return new HttpResponse($sgk, HttpResponse::HTTP_CREATED, HttpResponse::$statusTexts[HttpResponse::HTTP_CREATED]);
    }
);

$app->put(
    '/api/sgk/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) use ($app) {
        $sgkObject = $app->request->getJsonRawBody();

        /** @var Sgk $sgk */
        $sgk = Sgk::findFirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );
        $sgk->setName($sgkObject->name);

        if ($sgk->update() === false) {
            $messages = $sgk->getMessages();
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }
        return new HttpResponse($sgk, HttpResponse::HTTP_CREATED, HttpResponse::$statusTexts[HttpResponse::HTTP_CREATED]);
    }
);

$app->delete(
    '/api/sgk/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) {
        $sgk = Sgk::findfirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );
        if (empty($sgk)) {
            return new HttpResponse($sgk, HttpResponse::HTTP_NOT_FOUND, HttpResponse::$statusTexts[HttpResponse::HTTP_NOT_FOUND]);
        }

        if ($sgk->delete()) {
            return new HttpResponse('', HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
        }
    }
);

$app->post(
    '/api/sales',
    function () use ($app) {
        $salesObject = $app->request->getJsonRawBody();
        $sales = new Sales();

        $sales->setTotal($salesObject->total);
        if ($sales->save() === false) {
            $messages = $sales->getMessages();
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }
        return new HttpResponse($sales, HttpResponse::HTTP_CREATED, HttpResponse::$statusTexts[HttpResponse::HTTP_CREATED]);
    }
);

$app->get(
    '/api/sales',
    function () use ($app) {
        $paginator = new Paginator($app->request->get('page', 'int', 1), 5, 30);

        $sales = Sales::find([
            'offset' => $paginator->getOffset(),
            'limit' => $paginator->getLimit(),
            'order' => 'id ASC',
        ]);
        return new HttpResponse($sales, HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
    }
);

$app->get(
    '/api/sales/{text:[a-zA-z0-9]+}',
    function ($text) {
        echo $text;
    }
);

$app->get(
    '/api/sales/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) {
        /** @var Sales $sales */
        $sales = Sales::findFirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );
        if (!$sales) {
            return new HttpResponse('', HttpResponse::HTTP_NOT_FOUND, HttpResponse::$statusTexts[HttpResponse::HTTP_NOT_FOUND]);
        }
        $data = $sales->toArray();

        $data['rel_patient'] = [];
        if ($sales->getPatient() instanceof Patient) {
            $data['rel_patient'] = $sales->getPatient()->toArray();
            if ($sales->getPatient()->getSgk() instanceof Sgk) {
                $data['rel_patient']['rel_sgk'] = $sales->getPatient()->getSgk()->toArray();
            }
        }
        return new HttpResponse($data, HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
    }
);

$app->delete(
    '/api/sales/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) {
        $sales = Sales::findfirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );
        if (empty($sales)) {
            return new HttpResponse($sales, HttpResponse::HTTP_NOT_FOUND, HttpResponse::$statusTexts[HttpResponse::HTTP_NOT_FOUND]);
        }
        if ($sales->delete()) {
            return new HttpResponse('', HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
        }
    }
);

$app->put(
    '/api/sales/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) use ($app) {
        $salesObject = $app->request->getJsonRawBody();

        /** @var Sales $sales */
        $sales = Sales::findFirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );
        $sales->setTotal($salesObject->total);

        if ($sales->update() === false) {
            $messages = $sales->getMessages();
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }
        return new HttpResponse($sales, HttpResponse::HTTP_CREATED, HttpResponse::$statusTexts[HttpResponse::HTTP_CREATED]);
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

        if ($product->save() === false) {
            $messages = $product->getMessages();
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }
        return new HttpResponse($product, HttpResponse::HTTP_CREATED, HttpResponse::$statusTexts[HttpResponse::HTTP_CREATED]);
    }

);

$app->get(
    '/api/product',
    function () use ($app) {
        $paginator = new Paginator($app->request->get('page', 'int', 1), 5, 30);

        $product = Product::find([
            'offset' => $paginator->getOffset(),
            'limit' => $paginator->getLimit(),
            'order' => 'id ASC',
        ]);
        return new HttpResponse($product, HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
    }
);

$app->get(
    '/api/product/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) {
        /** @var Product $product */
        $product = Product::findFirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );
        if (!$product) {
            return new HttpResponse($product, HttpResponse::HTTP_NOT_FOUND, HttpResponse::$statusTexts[HttpResponse::HTTP_NOT_FOUND]);
        }
        $data = $product->toArray();
        $data['price2'] = $data["price"] . "TL";
        $data['price3'] = $data["price"] . "try";
        $data['rel_category'] = [];

        if ($product->getCategory() instanceof Category) {
            $data['rel_category'] = $product->getCategory()->toArray();
        }
        return new HttpResponse($data, HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
    }
);

$app->put(
    '/api/product/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) use ($app) {
        $productObject = $app->request->getJsonRawBody();

        /** @var Product $product */
        $product = Product::findfirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );

        $product->setName($productObject->name);
        $product->setStock($productObject->stock);
        $product->setConsumptionDate($productObject->consumption_date);
        $product->setProductionDate($productObject->production_date);
        $product->setPrice($productObject->price);

        if ($product->update() === false) {
            $messages = $product->getMessages();
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }
        return new HttpResponse($product, HttpResponse::HTTP_CREATED, HttpResponse::$statusTexts[HttpResponse::HTTP_CREATED]);
    }
);

$app->delete(
    '/api/product/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) {
        $product = Product::findFirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );
        if (empty($product)) {
            return new HttpResponse($product, HttpResponse::HTTP_NOT_FOUND, HttpResponse::$statusTexts[HttpResponse::HTTP_NOT_FOUND]);
        }
        if ($product->delete()) {
            return new HttpResponse('', HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
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

        if ($patient->save() === false) {
            $messages = $patient->getMessages();
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }
        return new HttpResponse($patient, HttpResponse::HTTP_CREATED, HttpResponse::$statusTexts[HttpResponse::HTTP_CREATED]);
    }
);

$app->get(
    '/api/patient',
    function () use ($app) {
        $paginator = new Paginator($app->request->get('page', 'int', 1), 5, 30);

        $patient = Patient::find([
            'offset' => $paginator->getOffset(),
            'limit' => $paginator->getLimit(),
            'order' => 'id ASC',
        ]);
        return new HttpResponse($patient, HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
    }
);

$app->get(
    '/api/patient/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) {
        /** @var Patient $patient */
        $patient = Patient::findFirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );
        if (!$patient) {
            return new HttpResponse($patient, HttpResponse::HTTP_NOT_FOUND, HttpResponse::$statusTexts[HttpResponse::HTTP_NOT_FOUND]);
        }
        $data = $patient->toArray();

        $data['rel_sgk'] = [];
        if ($patient->getSgk() instanceof Sgk) {
            $data['rel_sgk'] = $patient->getSgk()->toArray();
        }
        return new HttpResponse($data, HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
    }
);

$app->put(
    '/api/patient/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) use ($app) {
        $patientObject = $app->request->getJsonRawBody();

        /** @var Patient $patient */
        $patient = Patient::findfirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );

        $patient->setTc($patientObject->tc);
        $patient->setSurName($patientObject->surname);
        $patient->setName($patientObject->name);
        $patient->setAddress($patientObject->address);

        if ($patient->update() === false) {
            $messages = $patient->getMessages();
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }
        return new HttpResponse($patient, HttpResponse::HTTP_CREATED, HttpResponse::$statusTexts[HttpResponse::HTTP_CREATED]);
    }
);

$app->delete(
    '/api/patient/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) {
        $patient = Patient::findFirst($id);
        if (empty($patient)) {
            return new HttpResponse($patient, HttpResponse::HTTP_NOT_FOUND, HttpResponse::$statusTexts[HttpResponse::HTTP_NOT_FOUND]);
        }
        if ($patient->delete()) {
            return new HttpResponse('', HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
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

        if ($company->save() === false) {
            $messages = $company->getMessages();
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }
        return new HttpResponse($company, HttpResponse::HTTP_CREATED, HttpResponse::$statusTexts[HttpResponse::HTTP_CREATED]);
    }
);

$app->get(
    '/api/company',
    function () use ($app) {
        $paginator = new Paginator($app->request->get('page', 'int', 1), 5, 30);

        $company = Company::find([
            'offset' => $paginator->getOffset(),
            'limit' => $paginator->getLimit(),
            'order' => 'id ASC',
        ]);
        return new HttpResponse($company, HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
    }
);

$app->get(
    '/api/company/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) {
        $company = Company::findFirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );
        if (!$company) {
            return new HttpResponse('', HttpResponse::HTTP_NOT_FOUND, HttpResponse::$statusTexts[HttpResponse::HTTP_NOT_FOUND]);
        }
        return new HttpResponse($company, HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
    }

);

$app->put(
    '/api/company/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) use ($app) {
        $companyObject = $app->request->getJsonRawBody();

        /** @var Company $company */
        $company = Company::findfirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );

        $company->setName($companyObject->name);
        $company->setPhoneNumber($companyObject->phone_number);


        if ($company->update() === false) {
            $messages = $company->getMessages();
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }
        return new HttpResponse($company, HttpResponse::HTTP_CREATED, HttpResponse::$statusTexts[HttpResponse::HTTP_CREATED]);
    }
);

$app->delete(
    '/api/company/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) {
        $company = Company::findFirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );
        if (empty($company)) {
            return new HttpResponse($company, HttpResponse::HTTP_NOT_FOUND, HttpResponse::$statusTexts[HttpResponse::HTTP_NOT_FOUND]);
        }

        if ($company->delete()) {
            return new HttpResponse('', HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
        }
    }
);

$app->post(
    '/api/category',
    function () use ($app) {
        $categoryObject = $app->request->getJsonRawBody();
        $category = new Category();

        $category->setName($categoryObject->name);

        if ($category->save() === false) {
            $messages = $category->getMessages();
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }
        return new HttpResponse($category, HttpResponse::HTTP_CREATED, HttpResponse::$statusTexts[HttpResponse::HTTP_CREATED]);
    }
);

$app->get(
    '/api/category',
    function () use ($app) {
        $paginator = new Paginator($app->request->get('page', 'int', 1), 5, 30);

        $category = Category::find([
            'offset' => $paginator->getOffset(),
            'limit' => $paginator->getLimit(),
            'order' => 'id ASC',
        ]);
        return new HttpResponse($category, HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
    }
);

$app->get(
    '/api/category/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) {
        $category = Category::findFirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );
        if (!$category) {
            return new HttpResponse('', HttpResponse::HTTP_NOT_FOUND, HttpResponse::$statusTexts[HttpResponse::HTTP_NOT_FOUND]);
        }
        return new HttpResponse($category, HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
    }
);

$app->put(
    '/api/category/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) use ($app) {
        $categoryObject = $app->request->getJsonRawBody();

        /** @var Category $category */
        $category = Category::findfirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );

        $category->setName($categoryObject->name);

        if ($category->update() === false) {
            $messages = $category->getMessages();
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }
        return new HttpResponse($category, HttpResponse::HTTP_CREATED, HttpResponse::$statusTexts[HttpResponse::HTTP_CREATED]);
    }
);

$app->delete(
    '/api/category/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) {
        $category = Category::findFirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );
        if (empty($category)) {
            return new HttpResponse($category, HttpResponse::HTTP_NOT_FOUND, HttpResponse::$statusTexts[HttpResponse::HTTP_NOT_FOUND]);
        }

        if ($category->delete()) {
            return new HttpResponse('', HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
        }
    }
);

$app->post(
    '/api/company-product',
    function () use ($app) {
        $company_productObject = $app->request->getJsonRawBody();
        $company_product = new CompanyProduct();

        $company_product->setTotal($company_productObject->total);

        if ($company_product->save() === false) {
            $messages = $company_product->getMessages();
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }
        return new HttpResponse($company_product, HttpResponse::HTTP_CREATED, HttpResponse::$statusTexts[HttpResponse::HTTP_CREATED]);
    }
);

$app->get(
    '/api/company-product/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) {
        /** @var CompanyProduct $company_product */
        $company_product = CompanyProduct::findFirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );
        if (!$company_product) {
            return new HttpResponse($company_product, HttpResponse::HTTP_NOT_FOUND, HttpResponse::$statusTexts[HttpResponse::HTTP_NOT_FOUND]);
        }
        $data = $company_product->toArray();

        $data['rel_company'] = [];
        if ($company_product->getCompany() instanceof Company) {
            $data['rel_company'] = $company_product->getCompany()->toArray();
        }
        $data['rel_product'] = [];
        if ($company_product->getProduct() instanceof Product) {
            $data['rel_product'] = $company_product->getProduct()->toArray();
        }
        return new HttpResponse($data, HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
    }
);

$app->get(
    '/api/company-product',
    function () use ($app) {
        $paginator = new Paginator($app->request->get('page', 'int', 1), 5, 30);

        $company_product = CompanyProduct::find([
            'offset' => $paginator->getOffset(),
            'limit' => $paginator->getLimit(),
            'order' => 'id ASC',
        ]);
        return new HttpResponse($company_product, HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
    }
);

$app->put(
    '/api/company-product/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) use ($app) {
        $company_productObject = $app->request->getJsonRawBody();

        /** @var CompanyProduct $company_product */
        $company_product = CompanyProduct::findfirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );

        $company_product->setTotal($company_productObject->total);

        if ($company_product->update() === false) {
            $messages = $company_product->getMessages();
            return new HttpResponse($messages, HttpResponse::HTTP_BAD_REQUEST, HttpResponse::$statusTexts[HttpResponse::HTTP_BAD_REQUEST]);
        }
        return new HttpResponse($company_product, HttpResponse::HTTP_CREATED, HttpResponse::$statusTexts[HttpResponse::HTTP_CREATED]);
    }
);

$app->delete(
    '/api/company-product/{id:[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}}',
    function ($id) {
        $company_product = Category::findFirst(
            [
                'conditions' => 'uuid = ?1',
                'bind' => [
                    1 => $id,
                ]
            ]
        );
        if (empty($company_product)) {
            return new HttpResponse($company_product, HttpResponse::HTTP_NOT_FOUND, HttpResponse::$statusTexts[HttpResponse::HTTP_NOT_FOUND]);
        }

        if ($company_product->delete()) {
            return new HttpResponse('', HttpResponse::HTTP_OK, HttpResponse::$statusTexts[HttpResponse::HTTP_OK]);
        }
    }
);
$app->handle();