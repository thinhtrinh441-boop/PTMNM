<?php
require_once __DIR__ . '/../config/database.php';

class OrderModel
{
    private static ?PDO $pdo = null;

    private static function getDB(): PDO
    {
        if (self::$pdo === null) {
            $database = new Database();
            self::$pdo = $database->getConnection();
        }
        return self::$pdo;
    }

    public static function create(array $customer, array $cartItems, string $paymentMethod): int|false
    {
        $db = self::getDB();

        $total = 0;
        foreach ($cartItems as $item) {
            $total += (int) $item['price'] * (int) $item['quantity'];
        }

        try {
            $db->beginTransaction();

            $stmt = $db->prepare(
                'INSERT INTO orders (customer_name, phone, address, total_amount, payment_method, status)
                 VALUES (:name, :phone, :address, :total, :payment, :status)'
            );
            $stmt->execute([
                'name' => $customer['name'],
                'phone' => $customer['phone'],
                'address' => $customer['address'],
                'total' => $total,
                'payment' => $paymentMethod,
                'status' => 'pending',
            ]);

            $orderId = (int) $db->lastInsertId();

            $itemStmt = $db->prepare(
                'INSERT INTO order_items (order_id, product_id, product_name, price, quantity)
                 VALUES (:order_id, :product_id, :product_name, :price, :quantity)'
            );

            foreach ($cartItems as $item) {
                $itemStmt->execute([
                    'order_id' => $orderId,
                    'product_id' => (int) $item['id'],
                    'product_name' => $item['name'],
                    'price' => (int) $item['price'],
                    'quantity' => (int) $item['quantity'],
                ]);
            }

            $db->commit();
            return $orderId;
        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            return false;
        }
    }

    public static function getAll(): array
    {
        $db = self::getDB();
        $stmt = $db->query(
            'SELECT o.*, COUNT(oi.id) AS item_count
             FROM orders o
             LEFT JOIN order_items oi ON oi.order_id = o.id
             GROUP BY o.id
             ORDER BY o.created_at DESC'
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById(int $id): ?array
    {
        $db = self::getDB();
        $stmt = $db->prepare('SELECT * FROM orders WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$order) {
            return null;
        }

        $itemsStmt = $db->prepare('SELECT * FROM order_items WHERE order_id = :id');
        $itemsStmt->execute(['id' => $id]);
        $order['items'] = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

        return $order;
    }
}
