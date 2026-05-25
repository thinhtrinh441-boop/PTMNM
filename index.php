<?php
session_start();
// 1. Tự động nạp Model (để không cần require từng cái)
require_once 'app/models/ProductModel.php';

// 2. Lấy URL và làm sạch
$requestUri = $_SERVER['REQUEST_URI'];
// Bỏ qua thư mục dự án (Ví dụ: /project1/)
$url = str_replace('/project1/', '', $requestUri); 
$url = trim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// 3. Xác định Controller (Mặc định là ProductController)
$controllerName = (!empty($url[0])) ? ucfirst($url[0]) . 'Controller' : 'ProductController';

// 4. Xác định Action (Mặc định là index)
$action = (!empty($url[1])) ? $url[1] : 'index';

// 5. Kiểm tra file Controller
$controllerPath = 'app/controllers/' . $controllerName . '.php';

if (!file_exists($controllerPath)) {
    die("Lỗi: Không tìm thấy file Controller tại: $controllerPath");
}

require_once $controllerPath;

// 6. Kiểm tra Class và Method
if (!class_exists($controllerName)) {
    die("Lỗi: Class $controllerName không tồn tại trong file.");
}

$controller = new $controllerName();

if (!method_exists($controller, $action)) {
    die("Lỗi: Action '$action' không tồn tại trong $controllerName.");
}

// 7. Gọi action
call_user_func_array([$controller, $action], array_slice($url, 2));
?>