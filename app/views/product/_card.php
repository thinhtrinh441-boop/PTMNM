<?php /** @var ProductModel $p */ $colClass = $colClass ?? 'col-6 col-md-4 col-lg-3 col-5-grid';
$imgUrl = product_image_url($p->getImage());
$discount = $p->getDiscountPercent();
?>
<div class="<?= $colClass ?>">
    <article class="product-card-v2 position-relative">
        <?php if ($discount > 0): ?>
        <span class="badge bg-danger sale-badge">-<?= $discount ?>%</span>
        <?php endif; ?>
        <a href="<?= url('Wishlist/toggle/' . $p->getID()) ?>" class="btn btn-sm <?= is_wishlisted($p->getID()) ? 'btn-danger' : 'btn-light' ?> wish-btn rounded-circle" title="Yêu thích">
            <i class="bi bi-heart<?= is_wishlisted($p->getID()) ? '-fill' : '' ?>"></i>
        </a>
        <a href="<?= url('Product/detail/' . $p->getID()) ?>" class="img-box text-decoration-none">
            <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($p->getName()) ?>"
                 onerror="this.src='<?= default_product_image() ?>'">
        </a>
        <div class="p-3 flex-grow-1 d-flex flex-column">
            <?php if ($p->getCategoryName()): ?><span class="badge text-bg-light border mb-1" style="font-size:.65rem"><?= htmlspecialchars($p->getCategoryName()) ?></span><?php endif; ?>
            <a href="<?= url('Product/detail/' . $p->getID()) ?>" class="text-decoration-none fw-semibold text-dark" style="font-size:.9rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden"><?= htmlspecialchars($p->getName()) ?></a>
            <div class="my-2">
                <span class="price-text fs-5"><?= format_price($p->getPrice()) ?></span>
                <?php if ($discount > 0): ?><del class="text-muted small ms-1"><?= format_price($p->getOldPrice()) ?></del><?php endif; ?>
            </div>
            <div class="mt-auto d-grid gap-1">
                <a href="<?= url('Product/detail/' . $p->getID()) ?>" class="btn btn-outline-brand btn-sm"><i class="bi bi-eye"></i> Chi tiết</a>
                <a href="<?= url('Cart/add/' . $p->getID()) ?>" class="btn btn-brand btn-sm"><i class="bi bi-cart-plus"></i> Mua ngay</a>
                <?php if (!isset($showAdmin) || $showAdmin): ?>
                <div class="d-flex gap-1">
                    <a href="<?= url('Product/edit/' . $p->getID()) ?>" class="btn btn-warning btn-sm flex-grow-1"><i class="bi bi-pencil"></i></a>
                    <a href="<?= url('Product/deleteConfirm/' . $p->getID()) ?>" class="btn btn-outline-danger btn-sm flex-grow-1"><i class="bi bi-trash"></i></a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </article>
</div>
