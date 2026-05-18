<?php
require_once 'app/models/ProductModel.php';

class ProductController
{
    // Mặc định chuyển hướng đến danh sách
    public function index()
    {
        $this->list();
    }

    // Chức năng HIỂN THỊ
    public function list()
    {
        // Gọi Model để lấy dữ liệu từ Database
        $products = ProductModel::getAll();
        include 'app/views/product/list.php';
    }

    // Chức năng THÊM
    public function add() {
        // Kiểm tra nếu người dùng nhấn nút "LƯU SẢN PHẨM" (phương thức POST)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            // 1. Lấy dữ liệu từ các ô nhập liệu (khớp với thuộc tính 'name' trong HTML)
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $image = $_POST['image'] ?? ''; // Link URL từ internet
            $description = $_POST['description'] ?? '';

            // 2. Khởi tạo đối tượng Model
            // Lưu ý thứ tự: id (null), tên, mô tả, giá, hình ảnh
            $product = new ProductModel(null, $name, $description, $price, $image);

            // 3. Gọi hàm insert để lưu vào Database
            if ($product->insert()) {
                // Nếu thành công, quay về danh sách sản phẩm
                header("Location: /project1/Product/list");
                exit();
            } else {
                // Hiển thị thông báo nếu có lỗi SQL
                echo "<script>alert('Lỗi: Không thể thêm sản phẩm!'); window.history.back();</script>";
            }
        }

        // Hiển thị giao diện Form thêm sản phẩm mà bạn đã gửi
        include 'app/views/product/add.php'; 
    }

    // Chức năng SỬA
    public function edit($id)
    {
        // Lấy sản phẩm hiện tại từ Database
        $product = ProductModel::getById($id);
        
        if (!$product) {
            die('Không tìm thấy sản phẩm!');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy dữ liệu mới từ Form
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $price = trim($_POST['price']);

            // Cập nhật thông tin (bỏ qua bước validate lỗi để code ngắn gọn)
            $productToUpdate = new ProductModel($id, $name, $description, $price);
            $productToUpdate->update();

            header('Location: /project1/Product/list');
            exit();
        }

        include 'app/views/product/edit.php';
    }

    // Chức năng XÓA
    public function delete($id)
    {
        if (isset($id)) {
            ProductModel::delete($id);
        }
        header('Location: /project1/Product/list');
        exit();
    }
    
}
?>