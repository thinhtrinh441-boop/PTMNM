<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giỏ hàng - Tech Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .cart-item img { width: 70px; height: 70px; object-fit: contain; border-radius: 8px; }
        .table { background: white; border-radius: 12px; overflow: hidden; }
        .summary-card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="d-flex align-items-center mb-4">
        <a href="/project1/Product/list" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
        <h3 class="fw-bold m-0">Giỏ hàng của bạn</h3>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <?php if (!empty($cart)): ?>
            <div class="table-responsive shadow-sm">
                <table class="table align-middle m-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-end">Đơn giá</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $item): ?>
                        <tr class="cart-item">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= htmlspecialchars($item['image']) ?>" class="me-3" onerror="this.src='https://placehold.co/100'">
                                    <span class="fw-bold"><?= htmlspecialchars($item['name']) ?></span>
                                </div>
                            </td>
                            <td class="text-center"><?= $item['quantity'] ?></td>
                            <td class="text-end fw-bold text-danger"><?= number_format($item['price']) ?>đ</td>
                            <td class="text-center">
                                <a href="/project1/Cart/remove/<?= $item['id'] ?>" class="text-danger"><i class="bi bi-trash3"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-5 bg-white rounded shadow-sm">
                <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                <p class="mt-3">Giỏ hàng rỗng. Hãy chọn sản phẩm nhé!</p>
                <a href="/project1/Product/list" class="btn btn-primary">Tiếp tục mua sắm</a>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="card summary-card p-4">
                <h5 class="fw-bold mb-3">Tóm tắt đơn hàng</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span>Tạm tính:</span>
                    <span class="fw-bold"><?= number_format($total) ?>đ</span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span>Phí vận chuyển:</span>
                    <span class="text-success fw-bold">Miễn phí</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <span class="h5 fw-bold">Tổng cộng:</span>
                    <span class="h4 fw-bold text-danger"><?= number_format($total) ?>đ</span>
                </div>
                <a href="/project1/Cart/checkout" class="btn btn-danger btn-lg w-100 fw-bold <?= empty($cart) ? 'disabled' : '' ?>">
                    THANH TOÁN NGAY
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>