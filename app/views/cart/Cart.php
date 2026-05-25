<?php include 'app/views/shares/header.php'; ?>
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
                                    <img src="<?= htmlspecialchars($item['image']) ?>" class="me-3" style="width: 70px; height: 70px; object-fit: contain;">
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
                <p class="mt-3">Giỏ hàng rỗng.</p>
                <a href="/project1/Product/list" class="btn btn-primary">Mua sắm ngay</a>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="card p-4 shadow-sm">
                <h5 class="fw-bold mb-3">Tóm tắt đơn hàng</h5>
                <div class="d-flex justify-content-between mb-4">
                    <span>Tổng cộng:</span>
                    <span class="h4 fw-bold text-danger"><?= number_format($total ?? 0) ?>đ</span>
                </div>
                <a href="/project1/Cart/checkout" class="btn btn-danger btn-lg w-100 fw-bold <?= empty($cart) ? 'disabled' : '' ?>">
                    THANH TOÁN NGAY
                </a>
            </div>
        </div>
    </div>
</div>
<?php include 'app/views/shares/footer.php'; ?>