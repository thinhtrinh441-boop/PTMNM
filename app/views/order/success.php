<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt hàng thành công</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="text-center col-lg-6 mx-auto">
        <div class="display-1 text-success mb-3"><i class="bi bi-check-circle-fill"></i></div>
        <h2 class="fw-bold text-success">Đặt hàng thành công!</h2>
        <p class="text-muted">Mã đơn hàng của bạn: <strong class="text-dark">#<?= (int) $order['id'] ?></strong></p>
        <p>Tổng thanh toán: <span class="fw-bold text-danger fs-4"><?= number_format((int) $order['total_amount'], 0, ',', '.') ?>đ</span></p>
        <p class="small text-muted">Chúng tôi sẽ liên hệ qua SĐT <?= htmlspecialchars($order['phone']) ?> để giao hàng.</p>
        <div class="d-flex gap-2 justify-content-center mt-4">
            <a href="<?= url('Order/detail/' . $order['id']) ?>" class="btn btn-outline-primary">Xem chi tiết đơn</a>
            <a href="<?= url('Product/list') ?>" class="btn btn-danger">Tiếp tục mua sắm</a>
        </div>
    </div>
</div>
</body>
</html>
