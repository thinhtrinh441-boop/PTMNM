<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?= url('Product/list') ?>"><i class="bi bi-shop me-2"></i>Cửa hàng</a>
        <a href="<?= url('Product/list') ?>" class="btn btn-outline-light btn-sm">Về trang chủ</a>
    </div>
</nav>

<div class="container pb-5">
    <h4 class="fw-bold mb-4"><i class="bi bi-receipt me-2"></i>Danh sách đơn hàng</h4>

    <?php if (!empty($orders)): ?>
    <div class="table-responsive bg-white rounded shadow-sm">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Mã ĐH</th>
                    <th>Khách hàng</th>
                    <th>SĐT</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): ?>
                <tr>
                    <td class="fw-bold">#<?= (int) $o['id'] ?></td>
                    <td><?= htmlspecialchars($o['customer_name']) ?></td>
                    <td><?= htmlspecialchars($o['phone']) ?></td>
                    <td class="text-danger fw-bold"><?= number_format((int) $o['total_amount'], 0, ',', '.') ?>đ</td>
                    <td>
                        <?php
                        $payLabels = ['cod' => 'COD', 'bank' => 'Chuyển khoản', 'momo' => 'MoMo'];
                        echo $payLabels[$o['payment_method']] ?? htmlspecialchars($o['payment_method']);
                        ?>
                    </td>
                    <td><span class="badge bg-warning text-dark"><?= htmlspecialchars($o['status']) ?></span></td>
                    <td><?= date('d/m/Y H:i', strtotime($o['created_at'])) ?></td>
                    <td>
                        <a href="<?= url('Order/detail/' . $o['id']) ?>" class="btn btn-sm btn-primary">Chi tiết</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="alert alert-info">Chưa có đơn hàng nào.</div>
    <?php endif; ?>
</div>
</body>
</html>
