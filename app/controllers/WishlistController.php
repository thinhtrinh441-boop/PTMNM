<?php
require_once __DIR__ . '/../config/config.php';

class WishlistController
{
    public function toggle($id)
    {
        $id = (int) $id;
        if (!isset($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = [];
        }
        $key = array_search($id, $_SESSION['wishlist'], true);
        if ($key !== false) {
            unset($_SESSION['wishlist'][$key]);
            $_SESSION['wishlist'] = array_values($_SESSION['wishlist']);
            flash_set('info', 'Đã bỏ khỏi danh sách yêu thích.');
        } else {
            $_SESSION['wishlist'][] = $id;
            flash_set('success', 'Đã thêm vào yêu thích!');
        }
        $back = $_SERVER['HTTP_REFERER'] ?? url('Product/list');
        header('Location: ' . $back);
        exit();
    }

    public function index()
    {
        require_once __DIR__ . '/../models/ProductModel.php';
        $ids = $_SESSION['wishlist'] ?? [];
        $products = [];
        foreach ($ids as $pid) {
            $p = ProductModel::getById($pid);
            if ($p) {
                $products[] = $p;
            }
        }
        include __DIR__ . '/../views/wishlist/index.php';
    }
}
