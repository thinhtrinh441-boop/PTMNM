<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết đơn hàng #<?= (int) $order['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Đơn hàng #<?= (int) $order['id'] ?></h5>
        </div>
        <div class="card-body">
            <p><strong>Khách hàng:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
            <p><strong>Điện thoại:</strong> <?= htmlspecialchars($order['phone']) ?></p>
            <p><strong>Địa chỉ:</strong> <?= nl2br(htmlspecialchars($order['address'])) ?></p>
            <p><strong>Tổng tiền:</strong> <span class="text-danger fw-bold"><?= number_format((int) $order['total_amount'], 0, ',', '.') ?>đ</span></p>
            <p><strong>Thanh toán:</strong> <?= htmlspecialchars($order['payment_method']) ?></p>
            <p><strong>Trạng thái:</strong> <?= htmlspecialchars($order['status']) ?></p>
            <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>

            <hr>
            <h6 class="fw-bold">Sản phẩm trong đơn</h6>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Tên SP</th>
                        <th class="text-center">SL</th>
                        <th class="text-end">Đơn giá</th>
                        <th class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td class="text-center"><?= (int) $item['quantity'] ?></td>
                        <td class="text-end"><?= number_format((int) $item['price'], 0, ',', '.') ?>đ</td>
                        <td class="text-end fw-bold"><?= number_format((int) $item['price'] * (int) $item['quantity'], 0, ',', '.') ?>đ</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            <a href="<?= url('Order/list') ?>" class="btn btn-secondary">Quay lại</a>
            <a href="<?= url('Product/list') ?>" class="btn btn-primary">Tiếp tục mua sắm</a>
        </div>
    </div>
</div>
</body>
</html>
