<?php
require_once __DIR__ . '/../config/database.php';

class ReviewModel
{
    private static ?PDO $pdo = null;

    private static function db(): PDO
    {
        if (self::$pdo === null) {
            self::$pdo = (new Database())->getConnection();
        }
        return self::$pdo;
    }

    public static function getByProduct(int $productId): array
    {
        try {
            $stmt = self::db()->prepare(
                'SELECT * FROM product_reviews WHERE product_id = :id ORDER BY created_at DESC LIMIT 20'
            );
            $stmt->execute(['id' => $productId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function getAverage(int $productId): float
    {
        try {
            $stmt = self::db()->prepare(
                'SELECT AVG(rating) AS avg_rating, COUNT(*) AS cnt FROM product_reviews WHERE product_id = :id'
            );
            $stmt->execute(['id' => $productId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['cnt'] > 0 ? round((float) $row['avg_rating'], 1) : 4.5;
        } catch (PDOException $e) {
            return 4.5;
        }
    }

    public static function getCount(int $productId): int
    {
        try {
            $stmt = self::db()->prepare('SELECT COUNT(*) FROM product_reviews WHERE product_id = :id');
            $stmt->execute(['id' => $productId]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public static function add(int $productId, string $name, int $rating, string $comment): bool
    {
        try {
            $stmt = self::db()->prepare(
                'INSERT INTO product_reviews (product_id, user_name, rating, comment) VALUES (:pid, :name, :rating, :comment)'
            );
            return $stmt->execute([
                'pid' => $productId,
                'name' => $name,
                'rating' => max(1, min(5, $rating)),
                'comment' => $comment,
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
