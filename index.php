<?php

session_start();

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/helpers/functions.php';
require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/models/ProductModel.php';

/*
|--------------------------------------------------------------------------
| REMEMBER ME
|--------------------------------------------------------------------------
*/
if (!isLoggedIn() && isset($_COOKIE['remember_token'])) {

    require_once __DIR__ . '/app/models/UserModel.php';

    $remUser = UserModel::findByRememberToken(
        $_COOKIE['remember_token']
    );

    if ($remUser && $remUser['is_active']) {

        $_SESSION['user'] = $remUser;

    } else {

        setcookie(
            'remember_token',
            '',
            time() - 3600,
            '/'
        );
    }
}

/*
|--------------------------------------------------------------------------
| PARSE URL
|--------------------------------------------------------------------------
*/
$requestUri = parse_url(
    $_SERVER['REQUEST_URI'],
    PHP_URL_PATH
);

$path = $requestUri;

if (
    BASE_PATH !== ''
    && str_starts_with($path, BASE_PATH)
) {
    $path = substr(
        $path,
        strlen(BASE_PATH)
    );
}

$path = trim($path, '/');

$path = $path !== ''
    ? filter_var(
        $path,
        FILTER_SANITIZE_URL
    )
    : '';

$url = $path === ''
    ? []
    : explode('/', $path);

/*
|--------------------------------------------------------------------------
| API ROUTER
|--------------------------------------------------------------------------
|
| GET    /api/product
| GET    /api/product/1
| POST   /api/product
| PUT    /api/product/1
| DELETE /api/product/1
|
*/
if (
    !empty($url)
    && strtolower($url[0]) === 'api'
) {

    $resource = strtolower(
        $url[1] ?? ''
    );

    $id = $url[2] ?? null;

    switch ($resource) {

        /*
        |--------------------------------------------------------------------------
        | PRODUCT API
        |--------------------------------------------------------------------------
        */
        case 'product':

            require_once __DIR__
                . '/app/controllers/ProductApiController.php';

            $controller =
                new ProductApiController();

            switch ($_SERVER['REQUEST_METHOD']) {

                case 'GET':

                    if ($id) {
                        $controller->show($id);
                    } else {
                        $controller->index();
                    }

                    break;

                case 'POST':

                    $controller->store();

                    break;

                case 'PUT':

                    if ($id) {
                        $controller->update($id);
                    } else {
                        http_response_code(400);

                        echo json_encode([
                            'message' =>
                                'Missing product id'
                        ]);
                    }

                    break;

                case 'DELETE':

                    if ($id) {
                        $controller->destroy($id);
                    } else {
                        http_response_code(400);

                        echo json_encode([
                            'message' =>
                                'Missing product id'
                        ]);
                    }

                    break;

                default:

                    http_response_code(405);

                    echo json_encode([
                        'message' =>
                            'Method Not Allowed'
                    ]);

                    break;
            }

            exit();

        /*
        |--------------------------------------------------------------------------
        | CATEGORY API
        |--------------------------------------------------------------------------
        */
        case 'category':

            require_once __DIR__
                . '/app/controllers/CategoryApiController.php';

            $controller =
                new CategoryApiController();

            $controller->index();

            exit();

        default:

            http_response_code(404);

            echo json_encode([
                'message' => 'API not found'
            ]);

            exit();
    }
}

/*
|--------------------------------------------------------------------------
| MVC ROUTER
|--------------------------------------------------------------------------
*/
$controllerName =
    !empty($url[0])
        ? ucfirst($url[0]) . 'Controller'
        : 'ProductController';

$action =
    !empty($url[1])
        ? $url[1]
        : 'index';

$controllerPath =
    __DIR__
    . '/app/controllers/'
    . $controllerName
    . '.php';

if (!file_exists($controllerPath)) {

    http_response_code(404);

    die(
        'Không tìm thấy Controller: '
        . htmlspecialchars($controllerName)
    );
}

require_once $controllerPath;

if (!class_exists($controllerName)) {

    die(
        'Class '
        . htmlspecialchars($controllerName)
        . ' không tồn tại.'
    );
}

$controller = new $controllerName();

if (!method_exists($controller, $action)) {

    http_response_code(404);

    die(
        'Action "'
        . htmlspecialchars($action)
        . '" không tồn tại.'
    );
}

call_user_func_array(
    [$controller, $action],
    array_slice($url, 2)
);