<?php
$pageTitle = 'Xác nhận xóa';
include __DIR__ . '/../layout/header.php';
?>

<div class="container py-5">
    <div class="card-modern col-md-6 mx-auto p-4 text-center">
        <i class="bi bi-exclamation-triangle text-danger display-3"></i>
        <h4 class="fw-bold mt-3">Xóa sản phẩm?</h4>
        <p class="text-muted">Bạn có chắc muốn xóa <strong><?= htmlspecialchars($product->getName()) ?></strong>? Hành động không hoàn tác.</p>
        <img src="<?= htmlspecialchars(product_image_url($product->getImage())) ?>" class="rounded mb-3" style="max-height:120px" onerror="this.src='<?= default_product_image() ?>'">
        <div class="d-flex gap-2 justify-content-center">
            <a href="<?= url('Product/list') ?>" class="btn btn-light px-4">Hủy</a>
            <a href="<?= url('Product/delete/' . $product->getID()) ?>" class="btn btn-danger px-4"><i class="bi bi-trash"></i> Xóa</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
