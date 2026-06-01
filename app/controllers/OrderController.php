<?php
require_once __DIR__ . '/../models/OrderModel.php';

class OrderController
{
    public function list()
    {
        $orders = OrderModel::getAll();
        include __DIR__ . '/../views/order/list.php';
    }

    public function detail($id)
    {
        $order = OrderModel::getById((int) $id);
        if (!$order) {
            die('Không tìm thấy đơn hàng.');
        }
        include __DIR__ . '/../views/order/detail.php';
    }

    public function success($id)
    {
        $order = OrderModel::getById((int) $id);
        if (!$order) {
            header('Location: ' . url('Product/list'));
            exit();
        }
        include __DIR__ . '/../views/order/success.php';
    }
    public function checkout()
{
    AuthHelper::checkLogin();

    // thanh toán
}
}
