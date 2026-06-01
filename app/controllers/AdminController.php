<?php

require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../helpers/auth.php';

class AdminController
{
    // ============================================================
    // Dashboard Admin
    // ============================================================
    public function index(): void
    {
        requireAdmin();

        $users       = UserModel::getAll();
        $totalUsers  = count($users);
        $activeUsers = count(array_filter($users, fn($u) => $u['is_active'] == 1));
        $adminCount  = count(array_filter($users, fn($u) => $u['role'] === 'Admin'));

        include __DIR__ . '/../views/admin/dashboard.php';
    }

    // ============================================================
    // Quản lý người dùng
    // ============================================================
    public function users(): void
    {
        requireAdmin();

        $search = trim($_GET['search'] ?? '');
        $users  = UserModel::getAll();

        if ($search !== '') {
            $users = array_filter($users, function ($u) use ($search) {
                return stripos($u['fullname'], $search) !== false
                    || stripos($u['email'], $search) !== false
                    || stripos($u['username'], $search) !== false;
            });
        }

        include __DIR__ . '/../views/admin/users.php';
    }

    // ============================================================
    // Khóa / Mở khóa tài khoản
    // ============================================================
    public function toggleStatus(): void
    {
        requireAdmin();

        $id     = (int)($_GET['id'] ?? 0);
        $status = (int)($_GET['status'] ?? 0);

        // Không cho phép tự khóa chính mình
        if ($id === (int)$_SESSION['user']['id']) {
            flash_set('danger', 'Bạn không thể khóa tài khoản của chính mình!');
            header("Location: " . url('Admin/users'));
            exit;
        }

        if ($id > 0) {
            UserModel::updateStatus($id, $status ? 1 : 0);
            $msg = $status ? 'Đã mở khóa tài khoản thành công.' : 'Đã khóa tài khoản thành công.';
            flash_set($status ? 'success' : 'warning', $msg);
        }

        header("Location: " . url('Admin/users'));
        exit;
    }

    // ============================================================
    // Thay đổi vai trò
    // ============================================================
    public function changeRole(): void
    {
        requireAdmin();

        $id   = (int)($_POST['id'] ?? 0);
        $role = $_POST['role'] ?? 'User';

        if ($id === (int)$_SESSION['user']['id']) {
            flash_set('danger', 'Bạn không thể thay đổi vai trò của chính mình!');
            header("Location: " . url('Admin/users'));
            exit;
        }

        if ($id > 0 && in_array($role, ['Admin', 'User'])) {
            UserModel::updateRole($id, $role);
            flash_set('success', 'Đã cập nhật vai trò thành công.');
        }

        header("Location: " . url('Admin/users'));
        exit;
    }

    // ============================================================
    // Xóa tài khoản người dùng
    // ============================================================
    public function deleteUser(): void
    {
        requireAdmin();

        $id = (int)($_GET['id'] ?? 0);

        if ($id === (int)$_SESSION['user']['id']) {
            flash_set('danger', 'Bạn không thể xóa tài khoản của chính mình!');
            header("Location: " . url('Admin/users'));
            exit;
        }

        if ($id > 0) {
            UserModel::delete($id);
            flash_set('success', 'Đã xóa tài khoản thành công.');
        }

        header("Location: " . url('Admin/users'));
        exit;
    }
}
