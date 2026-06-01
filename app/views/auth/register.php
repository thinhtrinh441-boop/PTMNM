<?php $pageTitle = 'Đăng ký tài khoản'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px">
                            <i class="bi bi-person-plus fs-2 text-danger"></i>
                        </div>
                        <h3 class="fw-bold">Đăng ký tài khoản</h3>
                        <p class="text-muted small">Tạo tài khoản để mua sắm dễ dàng hơn</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 py-2 small">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success rounded-3 py-3 text-center">
                            <i class="bi bi-envelope-check-fill fs-3 d-block mb-2 text-success"></i>
                            <strong><?= htmlspecialchars($success) ?></strong>
                            <div class="mt-2">
                                <small class="text-muted">Nếu không thấy email, hãy kiểm tra thư mục spam.</small>
                            </div>
                            <a href="<?= url('Auth/login') ?>" class="btn btn-success btn-sm mt-3">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Đi đến trang đăng nhập
                            </a>
                        </div>
                    <?php else: ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Họ và tên</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                <input type="text" name="fullname" class="form-control border-start-0"
                                       placeholder="Nguyễn Văn A" required
                                       value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Tên đăng nhập</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-at text-muted"></i></span>
                                <input type="text" name="username" class="form-control border-start-0"
                                       placeholder="nguyenvana" required
                                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control border-start-0"
                                       placeholder="name@example.com" required
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold small">Mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock text-muted"></i></span>
                                <input type="password" name="password" id="pw1" class="form-control border-start-0 border-end-0"
                                       placeholder="Ít nhất 6 ký tự" required minlength="6">
                                <button class="btn btn-light border" type="button" onclick="togglePw('pw1','eye1')">
                                    <i class="bi bi-eye" id="eye1"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold small">Xác nhận mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock-fill text-muted"></i></span>
                                <input type="password" name="confirm_password" id="pw2" class="form-control border-start-0 border-end-0"
                                       placeholder="Nhập lại mật khẩu" required>
                                <button class="btn btn-light border" type="button" onclick="togglePw('pw2','eye2')">
                                    <i class="bi bi-eye" id="eye2"></i>
                                </button>
                            </div>
                            <div id="pwMatch" class="small mt-1"></div>
                        </div>

                        <button type="submit" class="btn btn-danger btn-lg w-100 shadow-sm fw-bold">
                            <i class="bi bi-person-check me-1"></i> Đăng ký
                        </button>
                    </form>

                    <hr class="my-4">
                    <div class="text-center">
                        <span class="text-muted small">Đã có tài khoản? </span>
                        <a href="<?= url('Auth/login') ?>" class="text-danger fw-bold text-decoration-none small">Đăng nhập</a>
                    </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePw(id, eyeId) {
    const input = document.getElementById(id);
    const icon = document.getElementById(eyeId);
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.className = input.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}

document.getElementById('pw2')?.addEventListener('input', function() {
    const pw1 = document.getElementById('pw1').value;
    const el = document.getElementById('pwMatch');
    if (this.value === pw1) {
        el.innerHTML = '<span class="text-success"><i class="bi bi-check-circle-fill me-1"></i>Mật khẩu khớp</span>';
    } else {
        el.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle-fill me-1"></i>Mật khẩu không khớp</span>';
    }
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
