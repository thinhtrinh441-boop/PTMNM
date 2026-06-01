<?php
$pageTitle = 'Giỏ hàng';
$navActive = 'cart';
include __DIR__ . '/../layout/header.php';
?>

<div class="page-hero">
    <div class="container">
        <h4 class="fw-bold mb-0"><i class="bi bi-cart3 me-2"></i>Giỏ hàng của bạn</h4>
    </div>
</div>

<div class="container py-4 pb-5">
    <div class="row g-4">
        <div class="col-lg-8">
            <?php if (!empty($cart)): ?>
            <div class="card-modern overflow-hidden">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="text-center">SL</th>
                            <th class="text-end">Đơn giá</th>
                            <th class="text-end">Thành tiền</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $item): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="<?= htmlspecialchars($item['image'] ?: 'https://placehold.co/80') ?>" width="64" height="64" class="rounded-3 object-fit-contain bg-light p-1" onerror="this.src='https://placehold.co/80'">
                                    <span class="fw-semibold"><?= htmlspecialchars($item['name']) ?></span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?= url('Cart/update/' . $item['id'] . '/' . max(1, $item['quantity'] - 1)) ?>" class="btn btn-outline-secondary">−</a>
                                    <span class="btn btn-light disabled"><?= (int) $item['quantity'] ?></span>
                                    <a href="<?= url('Cart/update/' . $item['id'] . '/' . ($item['quantity'] + 1)) ?>" class="btn btn-outline-secondary">+</a>
                                </div>
                            </td>
                            <td class="text-end"><?= number_format((int) $item['price'], 0, ',', '.') ?>đ</td>
                            <td class="text-end fw-bold price-text"><?= number_format((int) $item['price'] * (int) $item['quantity'], 0, ',', '.') ?>đ</td>
                            <td class="text-center">
                                <a href="<?= url('Cart/remove/' . $item['id']) ?>" class="text-danger" onclick="return confirm('Xóa khỏi giỏ?');"><i class="bi bi-trash3"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="card-modern text-center py-5">
                <i class="bi bi-cart-x display-4 text-muted"></i>
                <p class="mt-3 text-muted">Giỏ hàng đang trống</p>
                <a href="<?= url('Product/list') ?>" class="btn btn-brand">Mua sắm ngay</a>
            </div>
            <?php endif; ?>
        </div>
        <div class="col-lg-4">
            <div class="card-modern p-4 sticky-top" style="top:90px">
                <h5 class="fw-bold mb-3">Tóm tắt</h5>
                <div class="d-flex justify-content-between mb-2"><span>Tạm tính</span><span class="fw-bold"><?= number_format($total, 0, ',', '.') ?>đ</span></div>
                <div class="d-flex justify-content-between mb-3 text-success"><span>Phí ship</span><span class="fw-bold">Miễn phí</span></div>
                <hr>
                <div class="d-flex justify-content-between mb-4">
                    <span class="fw-bold">Tổng</span>
                    <span class="fs-4 fw-bold price-text"><?= number_format($total, 0, ',', '.') ?>đ</span>
                </div>
                <a href="<?= url('Cart/checkout') ?>" class="btn btn-brand btn-lg w-100 <?= empty($cart) ? 'disabled' : '' ?>">Thanh toán</a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
