<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/OrderModel.php';

class CartController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function add($id, $quantity = 1, $redirect = 'list')
    {
        AuthHelper::checkLogin();
        $qty = max(1, (int) $quantity);
        $product = ProductModel::getById($id);
        if ($product) {
            $productId = $product->getID();

            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity'] += $qty;
            } else {
                $_SESSION['cart'][$productId] = [
                    'id' => $productId,
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'image' => $product->getImage(),
                    'quantity' => $qty,
                ];
            }
        }

        if ($product) {
            flash_set('success', 'Đã thêm "' . $product->getName() . '" vào giỏ hàng!');
        }

        if ($redirect === 'detail') {
            header('Location: ' . url('Product/detail/' . $id));
        } elseif ($redirect === 'cart') {
            header('Location: ' . url('Cart/view'));
        } else {
            header('Location: ' . url('Product/list'));
        }
        exit();
    }

    public function view()
    {
        $cart = $_SESSION['cart'] ?? [];
        $total = $this->calculateTotal($cart);

        include __DIR__ . '/../views/cart/index.php';
    }

    public function update($id, $quantity)
    {
        $id = (int) $id;
        $quantity = max(1, (int) $quantity);

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] = $quantity;
        }

        header('Location: ' . url('Cart/view'));
        exit();
    }

    public function remove($id)
    {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        header('Location: ' . url('Cart/view'));
        exit();
    }

    public function checkout()
    {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: ' . url('Product/list'));
            exit();
        }
        $total = $this->calculateTotal($cart);
        include __DIR__ . '/../views/cart/checkout.php';
    }

    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . url('Cart/checkout'));
            exit();
        }

        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            header('Location: ' . url('Product/list'));
            exit();
        }

        $customer = [
            'name' => trim($_POST['name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
        ];
        $paymentMethod = $_POST['payment_method'] ?? 'cod';

        if ($customer['name'] === '' || $customer['phone'] === '' || $customer['address'] === '') {
            echo "<script>alert('Vui lòng điền đầy đủ thông tin!'); history.back();</script>";
            exit();
        }

        $orderId = OrderModel::create($customer, array_values($cart), $paymentMethod);

        if (!$orderId) {
            echo "<script>alert('Đặt hàng thất bại. Kiểm tra CSDL và thử lại.'); history.back();</script>";
            exit();
        }

        unset($_SESSION['cart']);
        header('Location: ' . url('Order/success/' . $orderId));
        exit();
    }

    private function calculateTotal(array $cart): int
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += (int) $item['price'] * (int) $item['quantity'];
        }
        return $total;
    }
}
