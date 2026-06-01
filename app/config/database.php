<?php
class Database
{
    private string $host = 'localhost';
    private string $db_name = 'ecommerce_db';
    private string $username = 'root';
    private string $password = '';
    public ?PDO $conn = null;

    public function getConnection(): ?PDO
    {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name . ';charset=utf8mb4',
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            die('Lỗi kết nối CSDL: ' . $exception->getMessage());
        }
        return $this->conn;
    }
}
