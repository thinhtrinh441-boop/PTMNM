<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/ReviewModel.php';

class ProductController
{
    public function index()
    {
        $this->list();
    }

    public function list()
    {
        $filters = [
            'q' => trim($_GET['q'] ?? ''),
            'category_id' => $_GET['category'] ?? '',
            'sort' => $_GET['sort'] ?? 'newest',
        ];
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $result = ProductModel::search($filters, $page, 8);
        $products = $result['items'];
        $pagination = $result;
        $categories = ProductModel::getCategories();
        $queryParams = array_filter([
            'q' => $filters['q'],
            'category' => $filters['category_id'],
            'sort' => $filters['sort'],
        ]);
        include __DIR__ . '/../views/product/list.php';
    }

    public function detail($id)
    {
        $product = ProductModel::getById($id);
        if (!$product) {
            http_response_code(404);
            $pageTitle = 'Không tìm thấy';
            $navActive = 'shop';
            include __DIR__ . '/../views/layout/header.php';
            echo '<div class="container py-5 text-center"><div class="card-modern p-5">';
            echo '<h4>Sản phẩm không tồn tại</h4>';
            echo '<a href="' . url('Product/list') . '" class="btn btn-brand mt-3">Về cửa hàng</a></div></div>';
            include __DIR__ . '/../views/layout/footer.php';
            return;
        }

        $relatedProducts = ProductModel::getRelated((int) $id, 4);
        $reviews = ReviewModel::getByProduct((int) $id);
        $avgRating = ReviewModel::getAverage((int) $id);
        $reviewCount = ReviewModel::getCount((int) $id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_submit'])) {
            $rName = trim($_POST['user_name'] ?? 'Khách');
            $rRating = (int) ($_POST['rating'] ?? 5);
            $rComment = trim($_POST['comment'] ?? '');
            if ($rComment !== '') {
                ReviewModel::add((int) $id, $rName, $rRating, $rComment);
                flash_set('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
            }
            header('Location: ' . url('Product/detail/' . $id) . '#reviews');
            exit();
        }

        include __DIR__ . '/../views/product/detail.php';
    }

    public function add()
    {
        
        requireAdmin();
        $categories = ProductModel::getCategories();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product = new ProductModel(
                null,
                trim($_POST['name'] ?? ''),
                trim($_POST['description'] ?? ''),
                (int) ($_POST['price'] ?? 0),
                trim($_POST['image'] ?? ''),
                !empty($_POST['category_id']) ? (int) $_POST['category_id'] : null
            );
            if ($product->insert()) {
                flash_set('success', 'Thêm sản phẩm thành công!');
                header('Location: ' . url('Product/list'));
                exit();
            }
            flash_set('danger', 'Không thể thêm sản phẩm.');
        }
        include __DIR__ . '/../views/product/add.php';
    }

    public function edit($id)
    {
        
        requireAdmin();
        $product = ProductModel::getById($id);
        $categories = ProductModel::getCategories();
        if (!$product) {
            die('Không tìm thấy sản phẩm!');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productToUpdate = new ProductModel(
                $id,
                trim($_POST['name'] ?? ''),
                trim($_POST['description'] ?? ''),
                (int) ($_POST['price'] ?? 0),
                trim($_POST['image'] ?? ''),
                !empty($_POST['category_id']) ? (int) $_POST['category_id'] : null
            );
            $productToUpdate->update();
            flash_set('success', 'Cập nhật sản phẩm thành công!');
            header('Location: ' . url('Product/detail/' . $id));
            exit();
        }
        include __DIR__ . '/../views/product/edit.php';
    }

    public function deleteConfirm($id)
    {
        $product = ProductModel::getById($id);
        if (!$product) {
            header('Location: ' . url('Product/list'));
            exit();
        }
        include __DIR__ . '/../views/product/delete_confirm.php';
    }

    public function delete($id)
    {
        if ($id) {
            ProductModel::delete($id);
            flash_set('success', 'Đã xóa sản phẩm.');
        }
        header('Location: ' . url('Product/list'));
        exit();
    }
}
