<?php

// ============================================================
// Kiểm tra đăng nhập
// ============================================================
function isLoggedIn(): bool
{
    return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
}

// ============================================================
// Kiểm tra Admin
// ============================================================
function isAdmin(): bool
{
    return isLoggedIn() && $_SESSION['user']['role'] === 'Admin';
}

// ============================================================
// Yêu cầu đăng nhập
// ============================================================
function requireLogin(): void
{
    if (!isLoggedIn()) {
        flash_set('danger', 'Vui lòng đăng nhập để tiếp tục.');
        header("Location: " . url('Auth/login'));
        exit;
    }
}

// ============================================================
// Yêu cầu quyền Admin
// ============================================================
function requireAdmin(): void
{
    requireLogin();
    if (!isAdmin()) {
        http_response_code(403);
        die('<div style="text-align:center;padding:80px;font-family:sans-serif">
            <h2 style="color:#d70018">⛔ Truy cập bị từ chối</h2>
            <p>Bạn không có quyền truy cập trang này.</p>
            <a href="javascript:history.back()" style="color:#d70018">← Quay lại</a>
        </div>');
    }
}

// ============================================================
// Lấy thông tin người dùng hiện tại
// ============================================================
function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

// ============================================================
// Lấy avatar người dùng hiện tại
// ============================================================
function userAvatarUrl(?string $avatar): string
{
    if ($avatar && file_exists(__DIR__ . '/../../uploads/avatars/' . $avatar)) {
        return url('uploads/avatars/' . $avatar);
    }
    return 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['user']['fullname'] ?? 'User') . '&background=d70018&color=fff&size=128&bold=true';
}
