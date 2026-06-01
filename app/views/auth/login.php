<?php $pageTitle = 'Đăng nhập'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px">
                            <i class="bi bi-person-circle fs-2 text-danger"></i>
                        </div>
                        <h3 class="fw-bold">Đăng nhập</h3>
                        <p class="text-muted small">Chào mừng bạn quay trở lại</p>
                    </div>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show rounded-3 py-2 small" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
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
                                <input type="password" name="password" id="passwordInput" class="form-control border-start-0 border-end-0" placeholder="••••••••" required>
                                <button class="btn btn-light border" type="button" onclick="togglePassword()">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label small" for="remember">Ghi nhớ đăng nhập (30 ngày)</label>
                            </div>
                            <a href="#" class="text-decoration-none small text-danger">Quên mật khẩu?</a>
                        </div>

                        <button type="submit" class="btn btn-danger btn-lg w-100 shadow-sm fw-bold">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <span class="text-muted small">Chưa có tài khoản? </span>
                        <a href="<?= url('Auth/register') ?>" class="text-danger fw-bold text-decoration-none small">Đăng ký ngay</a>
                    </div>

                    <div class="text-center mt-3">
                        <a href="<?= url('Product/list') ?>" class="text-decoration-none text-secondary small">
                            <i class="bi bi-arrow-left-circle"></i> Về trang chủ
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
