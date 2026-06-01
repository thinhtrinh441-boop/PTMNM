<?php
$pageTitle = 'Sản phẩm yêu thích';
$navActive = 'shop';
include __DIR__ . '/../layout/header.php';
?>

<div class="container py-4 pb-5">
    <h4 class="fw-bold mb-4"><i class="bi bi-heart-fill text-danger"></i> Danh sách yêu thích</h4>
    <?php if (!empty($products)): ?>
    <div class="row g-3">
        <?php foreach ($products as $p): $showAdmin = false; include __DIR__ . '/../product/_card.php'; endforeach; ?>
    </div>
    <?php else: ?>
    <div class="card-modern text-center py-5">
        <p class="text-muted">Chưa có sản phẩm yêu thích.</p>
        <a href="<?= url('Product/list') ?>" class="btn btn-brand">Khám phá cửa hàng</a>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
