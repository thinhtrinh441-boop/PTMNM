<?php
class ProductModel
{
    private $id;
    private $name;
    private $description;
    private $price;
    private $image;
    private $categoryId;
    private $categoryName;

    public function __construct(
        $id = null,
        $name = '',
        $description = '',
        $price = 0,
        $image = '',
        $categoryId = null,
        $categoryName = ''
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->image = $image;
        $this->categoryId = $categoryId;
        $this->categoryName = $categoryName;
    }

    public function getID() { return $this->id; }
    public function getName() { return $this->name; }
    public function getDescription() { return $this->description; }
    public function getPrice() { return $this->price; }
    public function getImage() { return $this->image; }
    public function getCategoryId() { return $this->categoryId; }
    public function getCategoryName() { return $this->categoryName; }

    public function getOldPrice(): int
    {
        return (int) round($this->price * 1.12);
    }

    public function getDiscountPercent(): int
    {
        $old = $this->getOldPrice();
        if ($old <= $this->price) {
            return 0;
        }
        return (int) round((1 - $this->price / $old) * 100);
    }

    private static ?PDO $pdo = null;

    private static function getDB(): PDO
    {
        if (self::$pdo === null) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=localhost;dbname=ecommerce_db;charset=utf8mb4',
                    'root',
                    ''
                );
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Lỗi kết nối CSDL: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    private static function mapRow(array $row): ProductModel
    {
        return new ProductModel(
            $row['id'],
            $row['name'],
            $row['description'] ?? '',
            (int) $row['price'],
            $row['image'] ?? '',
            $row['category_id'] ?? null,
            $row['category_name'] ?? ''
        );
    }

    private static string $selectWithCategory = '
        SELECT p.*, c.name AS category_name
        FROM products p
        LEFT JOIN categories c ON c.id = p.category_id
    ';

    public static function getAll(): array
    {
        return self::search([], 1, 999)['items'];
    }

    public static function search(array $filters, int $page = 1, int $perPage = 8): array
    {
        $db = self::getDB();
        $where = ['1=1'];
        $params = [];

        if (!empty($filters['q'])) {
            $where[] = '(p.name LIKE :q OR p.description LIKE :q)';
            $params['q'] = '%' . $filters['q'] . '%';
        }
        if (!empty($filters['category_id'])) {
            $where[] = 'p.category_id = :cat';
            $params['cat'] = (int) $filters['category_id'];
        }

        $order = 'p.id DESC';
        $sort = $filters['sort'] ?? 'newest';
        if ($sort === 'price_asc') {
            $order = 'p.price ASC';
        } elseif ($sort === 'price_desc') {
            $order = 'p.price DESC';
        } elseif ($sort === 'name') {
            $order = 'p.name ASC';
        }

        $sqlBase = self::$selectWithCategory . ' WHERE ' . implode(' AND ', $where);
        $countStmt = $db->prepare('SELECT COUNT(*) FROM products p WHERE ' . implode(' AND ', $where));
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = max(1, min($page, $totalPages));
        $offset = ($page - 1) * $perPage;

        $stmt = $db->prepare($sqlBase . " ORDER BY $order LIMIT $perPage OFFSET $offset");
        $stmt->execute($params);
        $items = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $items[] = self::mapRow($row);
        }

        return [
            'items' => $items,
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
        ];
    }

    public static function getById($id): ?ProductModel
    {
        $db = self::getDB();
        $stmt = $db->prepare(self::$selectWithCategory . ' WHERE p.id = :id');
        $stmt->execute(['id' => (int) $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? self::mapRow($row) : null;
    }

    public static function getRelated(int $id, int $limit = 4): array
    {
        $product = self::getById($id);
        if (!$product) {
            return [];
        }

        $db = self::getDB();
        $sql = self::$selectWithCategory . ' WHERE p.id != :id';
        $params = ['id' => $id];

        if ($product->getCategoryId()) {
            $sql .= ' AND (p.category_id = :cat OR p.category_id IS NULL)';
            $params['cat'] = $product->getCategoryId();
        }

        $sql .= ' ORDER BY p.id DESC LIMIT ' . (int) $limit;
        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        $products = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = self::mapRow($row);
        }
        return $products;
    }

    public static function getCategories(): array
    {
        $db = self::getDB();
        return $db->query('SELECT id, name FROM categories ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert(): bool
    {
        try {
            $db = self::getDB();
            $stmt = $db->prepare(
                'INSERT INTO products (name, price, description, image, category_id)
                 VALUES (:name, :price, :description, :image, :category_id)'
            );
            return $stmt->execute([
                'name' => $this->name,
                'price' => $this->price,
                'description' => $this->description,
                'image' => $this->image ?? '',
                'category_id' => $this->categoryId ?: null,
            ]);
        } catch (PDOException $e) {
            die('Lỗi SQL khi thêm sản phẩm: ' . $e->getMessage());
        }
    }

    public function update(): bool
    {
        try {
            $db = self::getDB();
            $stmt = $db->prepare(
                'UPDATE products SET name = :name, price = :price, description = :description,
                 image = :image, category_id = :category_id WHERE id = :id'
            );
            return $stmt->execute([
                'name' => $this->name,
                'price' => $this->price,
                'description' => $this->description,
                'image' => $this->image,
                'category_id' => $this->categoryId ?: null,
                'id' => (int) $this->id,
            ]);
        } catch (PDOException $e) {
            die('Lỗi SQL khi cập nhật sản phẩm: ' . $e->getMessage());
        }
    }

    public static function delete($id): bool
    {
        $db = self::getDB();
        $stmt = $db->prepare('DELETE FROM products WHERE id = :id');
        return $stmt->execute(['id' => (int) $id]);
    }
}
