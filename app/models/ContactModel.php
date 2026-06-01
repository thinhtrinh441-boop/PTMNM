<?php
require_once __DIR__ . '/../config/database.php';

class ContactModel
{
    public static function save(string $name, string $email, string $phone, string $message): bool
    {
        try {
            $db = (new Database())->getConnection();
            $stmt = $db->prepare(
                'INSERT INTO contact_messages (name, email, phone, message) VALUES (:name, :email, :phone, :message)'
            );
            return $stmt->execute(compact('name', 'email', 'phone', 'message'));
        } catch (PDOException $e) {
            return false;
        }
    }
}
