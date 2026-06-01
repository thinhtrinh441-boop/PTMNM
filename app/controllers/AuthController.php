<?php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../helpers/auth.php';

class AuthController
{
    // ============================================================
    // Đăng nhập
    // ============================================================
    public function login(): void
    {
        // Nếu đã đăng nhập rồi thì redirect
        if (isLoggedIn()) {
            header("Location: " . url('Auth/profile'));
            exit;
        }

        // Kiểm tra Remember Me cookie
        if (!isLoggedIn() && isset($_COOKIE['remember_token'])) {
            $user = UserModel::findByRememberToken($_COOKIE['remember_token']);
            if ($user) {
                $_SESSION['user'] = $user;
                header("Location: " . url('Product/list'));
                exit;
            }
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = UserModel::findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                if (!$user['is_active']) {
                    // Kiểm tra xem đã xác thực email chưa
                    if (empty($user['email_verified_at'])) {
                        $error = 'Tài khoản chưa được xác thực. Vui lòng kiểm tra email.';
                    } else {
                        $error = 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.';
                    }
                } else {
                    $_SESSION['user'] = $user;

                    // Remember Me
                    if (!empty($_POST['remember'])) {
                        $token = bin2hex(random_bytes(32));
                        UserModel::saveRememberToken((int)$user['id'], $token);
                        setcookie('remember_token', $token, time() + 86400 * 30, '/', '', false, true);
                    }

                    flash_set('success', 'Chào mừng quay trở lại, ' . htmlspecialchars($user['fullname']) . '!');
                    header("Location: " . url('Product/list'));
                    exit;
                }
            } else {
                $error = 'Email hoặc mật khẩu không đúng.';
            }
        }

        include __DIR__ . '/../views/auth/login.php';
    }

    // ============================================================
    // Đăng ký
    // ============================================================
    public function register(): void
    {
        if (isLoggedIn()) {
            header("Location: " . url('Auth/profile'));
            exit;
        }

        $error   = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = trim($_POST['fullname'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm  = $_POST['confirm_password'] ?? '';

            // Validate
            if ($password !== $confirm) {
                $error = 'Mật khẩu xác nhận không khớp.';
            } elseif (strlen($password) < 6) {
                $error = 'Mật khẩu phải có ít nhất 6 ký tự.';
            } elseif (UserModel::findByEmail($email)) {
                $error = 'Email này đã được sử dụng.';
            } else {
                if (UserModel::register($fullname, $username, $email, $password)) {
                    // Lấy token để gửi email xác thực
                    $newUser = UserModel::findByEmail($email);
                    $verifyLink = url('Auth/verify') . '?token=' . $newUser['verification_token'];

                    // Mô phỏng gửi email (trong thực tế dùng PHPMailer/SMTP)
                    $this->sendVerificationEmail($email, $fullname, $verifyLink);

                    $success = 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản.';
                } else {
                    $error = 'Đăng ký thất bại. Vui lòng thử lại.';
                }
            }
        }

        include __DIR__ . '/../views/auth/register.php';
    }

    // ============================================================
    // Xác thực Email
    // ============================================================
    public function verify(): void
    {
        $token = $_GET['token'] ?? '';
        $message = '';
        $type = 'danger';

        if ($token) {
            $user = UserModel::findByVerificationToken($token);
            if ($user) {
                UserModel::verifyEmail((int)$user['id']);
                $message = 'Xác thực email thành công! Bạn có thể đăng nhập ngay bây giờ.';
                $type = 'success';
            } else {
                $message = 'Liên kết xác thực không hợp lệ hoặc đã được sử dụng.';
            }
        } else {
            $message = 'Không tìm thấy token xác thực.';
        }

        include __DIR__ . '/../views/auth/verify.php';
    }

    // ============================================================
    // Đăng xuất
    // ============================================================
    public function logout(): void
    {
        // Xóa remember token
        if (isset($_SESSION['user']['id'])) {
            UserModel::clearRememberToken((int)$_SESSION['user']['id']);
        }

        // Xóa cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }

        session_destroy();
        header("Location: " . url('Auth/login'));
        exit;
    }

    // ============================================================
    // Hồ sơ cá nhân
    // ============================================================
    public function profile(): void
    {
        requireLogin();

        $user = UserModel::findById((int)$_SESSION['user']['id']);
        $_SESSION['user'] = $user; // Refresh session

        include __DIR__ . '/../views/auth/profile.php';
    }

    // ============================================================
    // Cập nhật hồ sơ
    // ============================================================
    public function updateProfile(): void
    {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id      = (int)$_SESSION['user']['id'];
            $fullname = trim($_POST['fullname'] ?? '');
            $phone    = trim($_POST['phone'] ?? '');
            $address  = trim($_POST['address'] ?? '');

            UserModel::updateProfile($id, $fullname, $phone, $address);

            // Cập nhật session
            $user = UserModel::findById($id);
            $_SESSION['user'] = $user;

            flash_set('success', 'Cập nhật thông tin thành công!');
        }

        header("Location: " . url('Auth/profile'));
        exit;
    }

    // ============================================================
    // Tải lên ảnh đại diện
    // ============================================================
    public function uploadAvatar(): void
    {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
            $file = $_FILES['avatar'];
            $id   = (int)$_SESSION['user']['id'];

            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $maxSize      = 2 * 1024 * 1024; // 2MB

            if ($file['error'] !== UPLOAD_ERR_OK) {
                flash_set('danger', 'Lỗi khi tải lên ảnh.');
            } elseif (!in_array($file['type'], $allowedTypes)) {
                flash_set('danger', 'Chỉ chấp nhận ảnh JPG, PNG, GIF, WEBP.');
            } elseif ($file['size'] > $maxSize) {
                flash_set('danger', 'Ảnh không được vượt quá 2MB.');
            } else {
                $uploadDir = __DIR__ . '/../../uploads/avatars/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Xóa ảnh cũ nếu có
                $currentUser = UserModel::findById($id);
                if ($currentUser['avatar'] && file_exists($uploadDir . $currentUser['avatar'])) {
                    unlink($uploadDir . $currentUser['avatar']);
                }

                $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = 'avatar_' . $id . '_' . time() . '.' . $ext;

                if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
                    UserModel::updateAvatar($id, $filename);
                    $user = UserModel::findById($id);
                    $_SESSION['user'] = $user;
                    flash_set('success', 'Cập nhật ảnh đại diện thành công!');
                } else {
                    flash_set('danger', 'Không thể lưu ảnh. Vui lòng thử lại.');
                }
            }
        }

        header("Location: " . url('Auth/profile'));
        exit;
    }

    // ============================================================
    // Đổi mật khẩu
    // ============================================================
    public function changePassword(): void
    {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id          = (int)$_SESSION['user']['id'];
            $current     = $_POST['current_password'] ?? '';
            $newPass     = $_POST['new_password'] ?? '';
            $confirmPass = $_POST['confirm_password'] ?? '';

            $user = UserModel::findById($id);

            if (!password_verify($current, $user['password'])) {
                flash_set('danger', 'Mật khẩu hiện tại không đúng.');
            } elseif ($newPass !== $confirmPass) {
                flash_set('danger', 'Mật khẩu mới không khớp.');
            } elseif (strlen($newPass) < 6) {
                flash_set('danger', 'Mật khẩu mới phải có ít nhất 6 ký tự.');
            } else {
                UserModel::updatePassword($id, $newPass);
                flash_set('success', 'Đổi mật khẩu thành công!');
            }

            header("Location: " . url('Auth/profile'));
            exit;
        }

        include __DIR__ . '/../views/auth/change_password.php';
    }

    // ============================================================
    // Helper: Gửi email xác thực (mô phỏng bằng file log)
    // ============================================================
    private function sendVerificationEmail(string $email, string $name, string $link): void
    {
        // Trong môi trường thực tế, dùng PHPMailer với SMTP
        // Ở đây mô phỏng bằng cách lưu vào file log
        $logDir = __DIR__ . '/../../logs/';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $logContent = "[" . date('Y-m-d H:i:s') . "]\n";
        $logContent .= "To: {$email}\n";
        $logContent .= "Name: {$name}\n";
        $logContent .= "Verify Link: {$link}\n";
        $logContent .= str_repeat('-', 60) . "\n";

        file_put_contents($logDir . 'email_verification.log', $logContent, FILE_APPEND);

        // Thử gửi email thực (nếu server có cấu hình mail)
        $subject = 'Xác thực tài khoản - Điện Tử Shop';
        $body = "Xin chào {$name},\n\nVui lòng nhấp vào link sau để xác thực tài khoản:\n{$link}\n\nLink có hiệu lực trong 24 giờ.\n\nĐiện Tử Shop";
        $headers = "From: noreply@dientushop.vn\r\nContent-Type: text/plain; charset=UTF-8";

        @mail($email, $subject, $body, $headers);
    }
}
