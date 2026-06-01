<?php
$pageTitle = 'Liên hệ';
include __DIR__ . '/../layout/header.php';
?>

<div class="page-hero"><div class="container"><nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small">
<li class="breadcrumb-item"><a href="<?= url('Product/list') ?>">Trang chủ</a></li>
<li class="breadcrumb-item active">Liên hệ</li>
</ol></nav><h4 class="fw-bold mt-2">Form liên hệ khách hàng</h4></div></div>

<div class="container py-4 pb-5">
    <div class="row g-4">
        <div class="col-md-5">
            <div class="card-modern p-4">
                <h6 class="fw-bold">Thông tin cửa hàng</h6>
                <p class="small mb-1"><i class="bi bi-geo-alt text-danger"></i> 123 Nguyễn Văn Cừ, Q.5, TP.HCM</p>
                <p class="small mb-1"><i class="bi bi-telephone"></i> 1900 1234</p>
                <p class="small"><i class="bi bi-envelope"></i> support@dientushop.vn</p>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card-modern p-4">
                <form method="post">
                    <div class="mb-3"><label class="form-label fw-bold">Họ tên *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label fw-bold">Email *</label><input type="email" name="email" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label fw-bold">Điện thoại</label><input type="text" name="phone" class="form-control"></div>
                    <div class="mb-3"><label class="form-label fw-bold">Nội dung *</label><textarea name="message" class="form-control" rows="4" required></textarea></div>
                    <button type="submit" class="btn btn-brand">Gửi liên hệ</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
