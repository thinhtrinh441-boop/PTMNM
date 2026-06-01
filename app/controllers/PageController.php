<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../models/ContactModel.php';

class PageController
{
    public function contact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $message = trim($_POST['message'] ?? '');
            if ($name && $email && $message) {
                ContactModel::save($name, $email, $phone, $message);
                flash_set('success', 'Gửi liên hệ thành công! Chúng tôi sẽ phản hồi sớm.');
            } else {
                flash_set('danger', 'Vui lòng điền đầy đủ thông tin bắt buộc.');
            }
            header('Location: ' . url('Page/contact'));
            exit();
        }
        include __DIR__ . '/../views/page/contact.php';
    }

    public function policy()
    {
        include __DIR__ . '/../views/page/policy.php';
    }
}
