<?php
$pageTitle = 'Chính sách bảo hành & vận chuyển';
include __DIR__ . '/../layout/header.php';
?>

<div class="page-hero"><div class="container"><nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small">
<li class="breadcrumb-item"><a href="<?= url('Product/list') ?>">Trang chủ</a></li>
<li class="breadcrumb-item active">Chính sách</li>
</ol></nav><h4 class="fw-bold mt-2">Bảo hành &amp; vận chuyển</h4></div></div>

<div class="container py-4 pb-5">
    <div class="card-modern p-4">
        <h5 class="fw-bold text-danger"><i class="bi bi-shield-check"></i> Chính sách bảo hành</h5>
        <ul>
            <li>Bảo hành chính hãng 12 tháng đối với điện thoại, laptop.</li>
            <li>Phụ kiện bảo hành 6–12 tháng tùy hãng.</li>
            <li>1 đổi 1 trong 30 ngày nếu lỗi do nhà sản xuất.</li>
        </ul>
        <h5 class="fw-bold text-danger mt-4"><i class="bi bi-truck"></i> Chính sách vận chuyển</h5>
        <ul>
            <li>Nội thành TP.HCM: giao 2–4 giờ (phí 0đ đơn từ 500.000đ).</li>
            <li>Toàn quốc: 2–5 ngày làm việc, phí ship 30.000đ (miễn phí đơn từ 500k).</li>
            <li>Kiểm tra hàng trước khi thanh toán COD.</li>
        </ul>
        <a href="<?= url('Page/contact') ?>" class="btn btn-brand mt-3">Liên hệ hỗ trợ</a>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
