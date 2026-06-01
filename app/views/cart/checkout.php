<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .checkout-card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="<?= url('Cart/view') ?>" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
                <h3 class="fw-bold m-0">Xác nhận thanh toán</h3>
            </div>

            <div class="row g-4">
                <div class="col-md-5">
                    <div class="card checkout-card p-3">
                        <h6 class="fw-bold mb-3">Đơn hàng của bạn</h6>
                        <?php foreach ($_SESSION['cart'] ?? [] as $item): ?>
                        <div class="d-flex justify-content-between small mb-2">
                            <span><?= htmlspecialchars($item['name']) ?> × <?= (int) $item['quantity'] ?></span>
                            <span><?= number_format((int) $item['price'] * (int) $item['quantity'], 0, ',', '.') ?>đ</span>
                        </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold text-danger">
                            <span>Tổng cộng</span>
                            <span><?= number_format($total, 0, ',', '.') ?>đ</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="card checkout-card p-4">
                        <form method="POST" action="<?= url('Cart/processCheckout') ?>">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Họ tên người nhận</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Số điện thoại</label>
                                <input type="tel" name="phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Địa chỉ giao hàng</label>
                                <textarea name="address" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Phương thức thanh toán</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                                    <option value="bank">Chuyển khoản ngân hàng</option>
                                    <option value="momo">Ví MoMo</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold">
                                <i class="bi bi-check-circle me-2"></i>HOÀN TẤT ĐẶT HÀNG
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
