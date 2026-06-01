<?php $pageTitle = 'Hồ sơ cá nhân'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container py-5">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= url('Product/list') ?>" class="text-danger text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active">Hồ sơ cá nhân</li>
        </ol>
    </nav>

    <div class="row g-4">

        <!-- Cột trái: Thông tin avatar + tóm tắt -->
        <div class="col-md-4">

            <!-- Card Avatar -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 text-center">
                <div class="card-body p-4">

                    <!-- Avatar + Upload -->
                    <div class="position-relative d-inline-block mb-3">
                        <img src="<?= htmlspecialchars(userAvatarUrl($user['avatar'] ?? null)) ?>"
                             id="avatarPreview"
                             class="rounded-circle shadow border border-3 border-white"
                             width="120" height="120"
                             style="object-fit:cover"
                             alt="Avatar">
                        <label for="avatarInput"
                               class="position-absolute bottom-0 end-0 btn btn-danger btn-sm rounded-circle p-1"
                               style="width:32px;height:32px;cursor:pointer" title="Thay đổi ảnh">
                            <i class="bi bi-camera-fill" style="font-size:13px"></i>
                        </label>
                    </div>

                    <form method="POST" action="<?= url('Auth/uploadAvatar') ?>" enctype="multipart/form-data" id="avatarForm">
                        <input type="file" id="avatarInput" name="avatar" accept="image/*" class="d-none">
                        <button type="submit" id="avatarSaveBtn" class="btn btn-sm btn-outline-danger d-none mb-2">
                            <i class="bi bi-upload me-1"></i> Lưu ảnh
                        </button>
                    </form>

                    <h5 class="fw-bold mb-0"><?= htmlspecialchars($user['fullname']) ?></h5>
                    <p class="text-muted small mb-2">@<?= htmlspecialchars($user['username']) ?></p>

                    <!-- Badge vai trò -->
                    <?php if ($user['role'] === 'Admin'): ?>
                        <span class="badge bg-danger rounded-pill px-3 py-1">
                            <i class="bi bi-shield-fill-check me-1"></i> Admin
                        </span>
                    <?php else: ?>
                        <span class="badge bg-primary rounded-pill px-3 py-1">
                            <i class="bi bi-person-fill me-1"></i> Người dùng
                        </span>
                    <?php endif; ?>

                    <!-- Trạng thái email -->
                    <div class="mt-3">
                        <?php if (!empty($user['email_verified_at'])): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <i class="bi bi-envelope-check-fill me-1"></i> Email đã xác thực
                            </span>
                        <?php else: ?>
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                <i class="bi bi-envelope-exclamation-fill me-1"></i> Chưa xác thực email
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Thống kê -->
                    <hr>
                    <div class="row text-center g-2 mt-1">
                        <div class="col-6">
                            <div class="small text-muted">Ngày tham gia</div>
                            <div class="fw-semibold small">
                                <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="small text-muted">Trạng thái</div>
                            <div>
                                <?php if ($user['is_active']): ?>
                                    <span class="badge bg-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Bị khóa</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Đăng xuất -->
            <div class="d-grid gap-2">
                <?php if (isAdmin()): ?>
                    <a href="<?= url('Admin/users') ?>" class="btn btn-outline-danger">
                        <i class="bi bi-shield-lock me-1"></i> Quản lý người dùng
                    </a>
                <?php endif; ?>
                <a href="<?= url('Auth/logout') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-box-arrow-right me-1"></i> Đăng xuất
                </a>
            </div>

        </div>

        <!-- Cột phải: Tabs -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <ul class="nav nav-tabs nav-fill mb-4" id="profileTabs">
                        <li class="nav-item">
                            <a class="nav-link active fw-semibold" data-bs-toggle="tab" href="#tabInfo">
                                <i class="bi bi-person-lines-fill me-1"></i> Thông tin
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#tabPassword">
                                <i class="bi bi-key-fill me-1"></i> Đổi mật khẩu
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">

                        <!-- Tab: Thông tin cá nhân -->
                        <div class="tab-pane fade show active" id="tabInfo">
                            <form method="POST" action="<?= url('Auth/updateProfile') ?>">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" name="fullname" class="form-control" required
                                           value="<?= htmlspecialchars($user['fullname']) ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Email</label>
                                    <input type="email" class="form-control bg-light" disabled
                                           value="<?= htmlspecialchars($user['email']) ?>">
                                    <div class="form-text">Email không thể thay đổi.</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Số điện thoại</label>
                                    <input type="tel" name="phone" class="form-control"
                                           placeholder="0xxxxxxxxx"
                                           value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold small">Địa chỉ</label>
                                    <textarea name="address" class="form-control" rows="3"
                                              placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger fw-bold px-4">
                                    <i class="bi bi-save me-1"></i> Lưu thay đổi
                                </button>
                            </form>
                        </div>

                        <!-- Tab: Đổi mật khẩu -->
                        <div class="tab-pane fade" id="tabPassword">
                            <form method="POST" action="<?= url('Auth/changePassword') ?>">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                                    <input type="password" name="current_password" class="form-control" required placeholder="Nhập mật khẩu hiện tại">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">Mật khẩu mới <span class="text-danger">*</span></label>
                                    <input type="password" name="new_password" id="newPw" class="form-control" required
                                           placeholder="Ít nhất 6 ký tự" minlength="6">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-semibold small">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                                    <input type="password" name="confirm_password" id="confirmPw" class="form-control" required
                                           placeholder="Nhập lại mật khẩu mới">
                                    <div id="pwMatchMsg" class="small mt-1"></div>
                                </div>
                                <button type="submit" class="btn btn-warning fw-bold px-4">
                                    <i class="bi bi-key me-1"></i> Đổi mật khẩu
                                </button>
                            </form>
                        </div>

                    </div><!-- /.tab-content -->
                </div>
            </div>
        </div><!-- /.col -->

    </div><!-- /.row -->
</div>

<script>
// Preview ảnh đại diện trước khi upload
document.getElementById('avatarInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
        alert('Ảnh không được vượt quá 2MB!');
        this.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = ev => {
        document.getElementById('avatarPreview').src = ev.target.result;
        document.getElementById('avatarSaveBtn').classList.remove('d-none');
    };
    reader.readAsDataURL(file);
});

// Kiểm tra mật khẩu khớp
document.getElementById('confirmPw')?.addEventListener('input', function() {
    const el = document.getElementById('pwMatchMsg');
    if (this.value === document.getElementById('newPw').value) {
        el.innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Mật khẩu khớp</span>';
    } else {
        el.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle-fill me-1"></i>Mật khẩu không khớp</span>';
    }
});

// Mở đúng tab nếu có lỗi đổi mật khẩu từ flash
<?php
$flash = $_SESSION['flash'] ?? null;
$msg = $flash['message'] ?? '';
if (!empty($msg) && (str_contains($msg, 'mật khẩu') || str_contains($msg, 'Mật khẩu'))):
?>
document.addEventListener('DOMContentLoaded', () => {
    const tab = new bootstrap.Tab(document.querySelector('[href="#tabPassword"]'));
    tab.show();
});
<?php endif; ?>
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
