<?php $pageTitle = 'Xác thực Email'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 text-center p-5">

                <?php if ($type === 'success'): ?>
                    <div class="text-success mb-4">
                        <i class="bi bi-envelope-check-fill" style="font-size:4rem"></i>
                    </div>
                    <h4 class="fw-bold text-success mb-2">Xác thực thành công!</h4>
                    <p class="text-muted mb-4"><?= htmlspecialchars($message) ?></p>
                    <a href="<?= url('Auth/login') ?>" class="btn btn-success btn-lg px-5 fw-bold">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập ngay
                    </a>
                <?php else: ?>
                    <div class="text-danger mb-4">
                        <i class="bi bi-envelope-x-fill" style="font-size:4rem"></i>
                    </div>
                    <h4 class="fw-bold text-danger mb-2">Xác thực thất bại</h4>
                    <p class="text-muted mb-4"><?= htmlspecialchars($message) ?></p>
                    <a href="<?= url('Auth/register') ?>" class="btn btn-danger btn-lg px-5 fw-bold">
                        <i class="bi bi-arrow-left-circle me-2"></i>Quay lại đăng ký
                    </a>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
