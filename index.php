<?php
session_start();

require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/helpers/functions.php';
require_once __DIR__ . '/app/helpers/auth.php';
require_once __DIR__ . '/app/models/ProductModel.php';

// ── Remember Me: tự động đăng nhập nếu có cookie hợp lệ ──────────────
if (!isLoggedIn() && isset($_COOKIE['remember_token'])) {
    require_once __DIR__ . '/app/models/UserModel.php';
    $remUser = UserModel::findByRememberToken($_COOKIE['remember_token']);
    if ($remUser && $remUser['is_active']) {
        $_SESSION['user'] = $remUser;
    } else {
        // Token không hợp lệ, xóa cookie
        setcookie('remember_token', '', time() - 3600, '/');
    }
}

// ── Routing ───────────────────────────────────────────────────────────
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = $requestUri;

if (BASE_PATH !== '' && str_starts_with($path, BASE_PATH)) {
    $path = substr($path, strlen(BASE_PATH));
}

$path = trim($path, '/');
$path = $path !== '' ? filter_var($path, FILTER_SANITIZE_URL) : '';
$url = $path === '' ? [] : explode('/', $path);

$controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'ProductController';
$action = !empty($url[1]) ? $url[1] : 'index';

$controllerPath = __DIR__ . '/app/controllers/' . $controllerName . '.php';

if (!file_exists($controllerPath)) {
    http_response_code(404);
    die('Không tìm thấy trang: ' . htmlspecialchars($controllerName));
}

require_once $controllerPath;

if (!class_exists($controllerName)) {
    die('Class ' . htmlspecialchars($controllerName) . ' không tồn tại.');
}

$controller = new $controllerName();

if (!method_exists($controller, $action)) {
    http_response_code(404);
    die('Chức năng "' . htmlspecialchars($action) . '" không tồn tại.');
}

call_user_func_array([$controller, $action], array_slice($url, 2));
