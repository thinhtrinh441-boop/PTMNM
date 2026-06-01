<?php

class UserModel
{
    private static ?PDO $pdo = null;

    private static function db(): PDO
    {
        if (self::$pdo === null) {
            self::$pdo = new PDO(
                "mysql:host=localhost;dbname=ecommerce_db;charset=utf8mb4",
                "root",
                ""
            );
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$pdo;
    }

    // ============================================================
    // Đăng ký — kích hoạt tài khoản ngay (is_active = 1)
    // ============================================================
    public static function register(
        string $fullname,
        string $username,
        string $email,
        string $password
    ): bool {
        $stmt = self::db()->prepare("
            INSERT INTO users (fullname, username, email, password, role, is_active)
            VALUES (?, ?, ?, ?, 'User', 1)
        ");
        return $stmt->execute([
            $fullname,
            $username,
            $email,
            password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    // ============================================================
    // Tìm theo Email
    // ============================================================
    public static function findByEmail(string $email): array|false
    {
        $stmt = self::db()->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ============================================================
    // Tìm theo ID
    // ============================================================
    public static function findById(int $id): array|false
    {
        $stmt = self::db()->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ============================================================
    // Tìm theo Remember Token (trả về false nếu cột chưa tồn tại)
    // ============================================================
    public static function findByRememberToken(string $token): array|false
    {
        try {
            $stmt = self::db()->prepare(
                "SELECT * FROM users WHERE remember_token = ? AND is_active = 1"
            );
            $stmt->execute([$token]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false; // cột chưa tồn tại
        }
    }

    // ============================================================
    // Tìm theo Verification Token
    // ============================================================
    public static function findByVerificationToken(string $token): array|false
    {
        try {
            $stmt = self::db()->prepare(
                "SELECT * FROM users WHERE verification_token = ?"
            );
            $stmt->execute([$token]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }

    // ============================================================
    // Xác thực Email
    // ============================================================
    public static function verifyEmail(int $id): bool
    {
        try {
            $stmt = self::db()->prepare("
                UPDATE users
                SET is_active = 1, email_verified_at = NOW(), verification_token = NULL
                WHERE id = ?
            ");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            // Nếu thiếu cột, chỉ kích hoạt is_active
            $stmt = self::db()->prepare("UPDATE users SET is_active = 1 WHERE id = ?");
            return $stmt->execute([$id]);
        }
    }

    // ============================================================
    // Lấy tất cả người dùng (Admin)
    // ============================================================
    public static function getAll(): array
    {
        try {
            return self::db()
                ->query("SELECT id, fullname, username, email, role, is_active,
                                avatar, phone, created_at, email_verified_at
                         FROM users ORDER BY id DESC")
                ->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Fallback nếu một số cột chưa tồn tại
            return self::db()
                ->query("SELECT id, fullname, username, email, role, is_active, created_at
                         FROM users ORDER BY id DESC")
                ->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    // ============================================================
    // Cập nhật trạng thái (khóa / mở khóa)
    // ============================================================
    public static function updateStatus(int $id, int $status): bool
    {
        $stmt = self::db()->prepare("UPDATE users SET is_active = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    // ============================================================
    // Cập nhật vai trò (Admin / User)
    // ============================================================
    public static function updateRole(int $id, string $role): bool
    {
        $stmt = self::db()->prepare("UPDATE users SET role = ? WHERE id = ?");
        return $stmt->execute([$role, $id]);
    }

    // ============================================================
    // Cập nhật hồ sơ cá nhân
    // ============================================================
    public static function updateProfile(
        int    $id,
        string $fullname,
        string $phone,
        string $address
    ): bool {
        // Thử cập nhật đầy đủ; nếu thiếu cột thì chỉ cập nhật fullname
        try {
            $stmt = self::db()->prepare("
                UPDATE users SET fullname = ?, phone = ?, address = ? WHERE id = ?
            ");
            return $stmt->execute([$fullname, $phone, $address, $id]);
        } catch (PDOException $e) {
            $stmt = self::db()->prepare("UPDATE users SET fullname = ? WHERE id = ?");
            return $stmt->execute([$fullname, $id]);
        }
    }

    // ============================================================
    // Cập nhật ảnh đại diện
    // ============================================================
    public static function updateAvatar(int $id, string $avatar): bool
    {
        try {
            $stmt = self::db()->prepare("UPDATE users SET avatar = ? WHERE id = ?");
            return $stmt->execute([$avatar, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // ============================================================
    // Đổi mật khẩu
    // ============================================================
    public static function updatePassword(int $id, string $newPassword): bool
    {
        $stmt = self::db()->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([password_hash($newPassword, PASSWORD_DEFAULT), $id]);
    }

    // ============================================================
    // Lưu Remember Token
    // ============================================================
    public static function saveRememberToken(int $id, string $token): bool
    {
        try {
            $stmt = self::db()->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
            return $stmt->execute([$token, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // ============================================================
    // Xóa Remember Token
    // ============================================================
    public static function clearRememberToken(int $id): bool
    {
        try {
            $stmt = self::db()->prepare("UPDATE users SET remember_token = NULL WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // ============================================================
    // Xóa người dùng (Admin)
    // ============================================================
    public static function delete(int $id): bool
    {
        $stmt = self::db()->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // ============================================================
    // Đếm tổng người dùng
    // ============================================================
    public static function count(): int
    {
        return (int) self::db()->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }
}