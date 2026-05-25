<?php
// Sửa lỗi nạp file: Lùi 1 cấp từ controllers ra app, sau đó vào models
require_once __DIR__ . '/../models/ProductModel.php';

class CartController {
    public function __construct() {
        // Đảm bảo session luôn chạy để lưu giỏ hàng
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // 1. Thêm sản phẩm vào giỏ hàng
    public function add($id) {
        $product = ProductModel::getById($id);
        if ($product) {
            $productId = $product->getID();
            
            // Nếu đã có sản phẩm này thì tăng số lượng
            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity'] += 1;
            } else {
                // Nếu chưa có thì thêm mới vào mảng session
                $_SESSION['cart'][$productId] = [
                    'id' => $productId,
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'image' => $product->getImage(),
                    'quantity' => 1
                ];
            }
        }
        // Sau khi thêm, quay về danh sách sản phẩm để mua tiếp
        header("Location: /project1/Product/list"); 
        exit();
    }

    // 2. Hiển thị trang giỏ hàng
    public function view() {
        $cart = $_SESSION['cart'] ?? [];
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        // Sửa đường dẫn include: Lùi 1 cấp ra app, rồi vào views/cart
        $viewPath = __DIR__ . '/../views/cart/index.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            die("Lỗi: Không tìm thấy file giao diện tại $viewPath");
        }
    }

    // 3. Xóa sản phẩm khỏi giỏ
    public function remove($id) {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header("Location: /project1/Cart/view");
        exit();
    }

    // 4. Trang thanh toán
    public function checkout() {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header("Location: /project1/Product/list");
            exit();
        }
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        include __DIR__ . '/../views/cart/checkout.php';
    }
    // 5. Xử lý lưu đơn hàng vào Database
    public function processCheckout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $cart = $_SESSION['cart'] ?? [];
            if (empty($cart)) {
                header("Location: /project1/Product/list");
                exit();
            }

            // Lấy dữ liệu từ form
            $name = $_POST['name'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            
            // Tính tổng tiền
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // GỌI MODEL ĐỂ LƯU (Bạn cần tạo hàm này trong OrderModel hoặc ProductModel)
            // Ví dụ: $orderId = OrderModel::saveOrder($name, $phone, $address, $total);
            
            // GIẢ LẬP LƯU THÀNH CÔNG:
            // Sau khi lưu thành công, xóa giỏ hàng
            unset($_SESSION['cart']);

            // Thông báo và chuyển hướng
            echo "<script>alert('Đặt hàng thành công! Cảm ơn bạn.'); window.location.href='/project1/Product/list';</script>";
            exit();
        }
    }
}