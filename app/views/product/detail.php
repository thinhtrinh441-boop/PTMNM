<?php
$pageTitle = $product->getName();
$navActive = 'shop';
$imgUrl = product_image_url($product->getImage());
$discount = $product->getDiscountPercent();
include __DIR__ . '/../layout/header.php';
?>

<div class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= url('Product/list') ?>">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="<?= url('Product/list') ?>">Sản phẩm</a></li>
                <?php if ($product->getCategoryName()): ?>
                <li class="breadcrumb-item"><?= htmlspecialchars($product->getCategoryName()) ?></li>
                <?php endif; ?>
                <li class="breadcrumb-item active"><?= htmlspecialchars($product->getName()) ?></li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-4 pb-5">
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card-modern p-3 text-center">
                <img src="<?= htmlspecialchars($imgUrl) ?>" class="img-fluid rounded" style="max-height:400px" alt=""
                     onerror="this.src='<?= default_product_image() ?>'">
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card-modern p-4">
                <?php if ($product->getCategoryName()): ?><span class="badge bg-danger mb-2"><?= htmlspecialchars($product->getCategoryName()) ?></span><?php endif; ?>
                <h1 class="h3 fw-bold"><?= htmlspecialchars($product->getName()) ?></h1>
                <div class="d-flex align-items-center gap-2 my-2">
                    <?= render_stars($avgRating) ?>
                    <span class="text-muted small"><?= $avgRating ?>/5 (<?= $reviewCount ?> đánh giá)</span>
                </div>
                <div class="py-3 border-top border-bottom my-3">
                    <span class="price-text display-6"><?= format_price($product->getPrice()) ?></span>
                    <?php if ($discount > 0): ?>
                    <del class="text-muted fs-5 ms-2"><?= format_price($product->getOldPrice()) ?></del>
                    <span class="badge bg-danger ms-1">-<?= $discount ?>%</span>
                    <?php endif; ?>
                </div>
                <p class="text-secondary" style="white-space:pre-line"><?= $product->getDescription() ? nl2br(htmlspecialchars($product->getDescription())) : '<em>Chưa có mô tả.</em>' ?></p>

                <ul class="list-unstyled small text-muted mb-3">
                    <li><i class="bi bi-shield-check text-success"></i> Bảo hành 12 tháng — <a href="<?= url('Page/policy') ?>">Xem chính sách</a></li>
                    <li><i class="bi bi-truck text-primary"></i> Giao hàng 2–5 ngày, freeship đơn từ 500k</li>
                    <li><i class="bi bi-arrow-repeat"></i> Đổi trả trong 7 ngày nếu lỗi NSX</li>
                </ul>

                <div class="d-flex align-items-center gap-2 mb-3">
                    <label class="fw-semibold">Số lượng:</label>
                    <input type="number" id="qty" class="form-control" style="width:80px" value="1" min="1" max="99">
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="#" id="btnAddCart" class="btn btn-brand btn-lg"><i class="bi bi-cart-plus"></i> Thêm giỏ</a>
                    <a href="#" id="btnBuyNow" class="btn btn-dark btn-lg">Mua ngay</a>
                    <a href="<?= url('Wishlist/toggle/' . $product->getID()) ?>" class="btn btn-outline-danger btn-lg">
                        <i class="bi bi-heart<?= is_wishlisted($product->getID()) ? '-fill' : '' ?>"></i>
                    </a>
                    <a href="<?= url('Product/edit/' . $product->getID()) ?>" class="btn btn-warning">Sửa</a>
                    <a href="<?= url('Product/deleteConfirm/' . $product->getID()) ?>" class="btn btn-outline-danger">Xóa</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Đánh giá -->
    <div class="card-modern p-4 mt-4" id="reviews">
        <h4 class="fw-bold mb-3"><i class="bi bi-chat-dots"></i> Đánh giá &amp; bình luận</h4>
        <?php if (!empty($reviews)): ?>
            <?php foreach ($reviews as $r): ?>
            <div class="border-bottom pb-3 mb-3">
                <strong><?= htmlspecialchars($r['user_name']) ?></strong>
                <?= render_stars((float)$r['rating']) ?>
                <small class="text-muted"><?= date('d/m/Y', strtotime($r['created_at'])) ?></small>
                <p class="mb-0 mt-1 small"><?= nl2br(htmlspecialchars($r['comment'])) ?></p>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Chưa có bình luận. Hãy là người đầu tiên!</p>
        <?php endif; ?>

        <form method="post" class="mt-3 bg-light p-3 rounded">
            <input type="hidden" name="review_submit" value="1">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="user_name" class="form-control" placeholder="Tên của bạn" required>
                </div>
                <div class="col-md-2">
                    <select name="rating" class="form-select">
                        <?php for ($s=5;$s>=1;$s--): ?><option value="<?= $s ?>"><?= $s ?> sao</option><?php endfor; ?>
                    </select>
                </div>
                <div class="col-12">
                    <textarea name="comment" class="form-control" rows="2" placeholder="Nội dung đánh giá..." required></textarea>
                </div>
                <div class="col-12"><button type="submit" class="btn btn-brand btn-sm">Gửi đánh giá</button></div>
            </div>
        </form>
    </div>

    <?php if (!empty($relatedProducts)): ?>
    <h4 class="fw-bold mt-5 mb-3">Sản phẩm liên quan</h4>
    <div class="row g-3">
        <?php foreach ($relatedProducts as $p): $showAdmin = false; include __DIR__ . '/_card.php'; endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
const productId = <?= (int)$product->getID() ?>;
const base = '<?= BASE_PATH ?>';
function addCart(redir) {
    const q = document.getElementById('qty').value || 1;
    location.href = base + '/Cart/add/' + productId + '/' + q + '/' + redir;
}
document.getElementById('btnAddCart').onclick = e => { e.preventDefault(); addCart('detail'); };
document.getElementById('btnBuyNow').onclick = e => { e.preventDefault(); addCart('cart'); };
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
