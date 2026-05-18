<?php
class ProductModel
{
    private $id;
    private $name;
    private $description;
    private $price;
    private $image;

    // Constructor nhận đầy đủ các thuộc tính của sản phẩm
    public function __construct($id = null, $name = "", $description = "", $price = 0, $image = "")
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->image = $image;
    }

    // --- Các Getters & Setters ---
    public function getID() { return $this->id; }
    public function getName() { return $this->name; }
    public function getDescription() { return $this->description; }
    public function getPrice() { return $this->price; }
    public function getImage() { return $this->image; }
    
    // Setter cho các thuộc tính cần thay đổi linh hoạt
    public function setName($name) { $this->name = $name; }
    public function setPrice($price) { $this->price = $price; }
    public function setDescription($description) { $this->description = $description; }
    public function setImage($image) { $this->image = $image; }

    // Kết nối CSDL sử dụng Singleton pattern nhẹ để tối ưu hiệu suất
    private static $pdo = null;
    private static function getDB()
    {
        if (self::$pdo === null) {
            $host = 'localhost';
            $dbname = 'ecommerce_db'; // Đảm bảo tên DB này trùng với DB trong Laragon của bạn
            $username = 'root';
            $password = '';

            try {
                self::$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Lỗi kết nối CSDL: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    // Lấy tất cả sản phẩm
    public static function getAll()
    {
        $db = self::getDB();
        $stmt = $db->query("SELECT * FROM products ORDER BY id DESC");
        
        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = new ProductModel(
                $row['id'], 
                $row['name'], 
                $row['description'] ?? '', 
                $row['price'], 
                $row['image'] ?? '' // Tránh lỗi nếu cột image bị trống
            );
        }
        return $products;
    }

    // Lấy 1 sản phẩm theo ID
    public static function getById($id)
    {
        $db = self::getDB();
        $stmt = $db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => (int)$id]);
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new ProductModel(
                $row['id'], 
                $row['name'], 
                $row['description'] ?? '', 
                $row['price'], 
                $row['image'] ?? ''
            );
        }
        return null;
    }

    // THÊM: Insert sản phẩm mới
    // LƯU Ý: Phải đảm bảo bạn đã chạy câu lệnh SQL: 
    // ALTER TABLE products ADD COLUMN image VARCHAR(255) DEFAULT NULL;
    public function insert()
    {
        try {
            $db = self::getDB();
            $query = "INSERT INTO products (name, price, description, image) VALUES (:name, :price, :description, :image)";
            $stmt = $db->prepare($query);
            
            return $stmt->execute([
                'name'        => $this->name,
                'price'       => $this->price,
                'description' => $this->description,
                'image'       => $this->image ?? '' // Đảm bảo không truyền null vào DB
            ]);
        } catch (PDOException $e) {
            // In ra lỗi cụ thể nếu câu lệnh SQL thất bại
            die("Lỗi SQL khi thêm sản phẩm: " . $e->getMessage());
        }
    }

    // SỬA: Cập nhật thông tin sản phẩm
    public function update()
    {
        try {
            $db = self::getDB();
            $query = "UPDATE products SET name = :name, price = :price, description = :description, image = :image WHERE id = :id";
            $stmt = $db->prepare($query);
            
            return $stmt->execute([
                'name'        => $this->name,
                'price'       => $this->price,
                'description' => $this->description,
                'image'       => $this->image,
                'id'          => (int)$this->id
            ]);
        } catch (PDOException $e) {
            die("Lỗi SQL khi cập nhật sản phẩm: " . $e->getMessage());
        }
    }

    // XÓA: Delete sản phẩm theo ID
    public static function delete($id)
    {
        $db = self::getDB();
        $stmt = $db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute(['id' => (int)$id]);
    }
}