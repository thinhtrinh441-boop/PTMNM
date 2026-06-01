<?php
$pageTitle = 'Sửa: ' . $product->getName();
$navActive = 'shop';
include __DIR__ . '/../layout/header.php';
?>

<style>
    .preview-box {
        height: 240px; border: 2px dashed #dee2e6; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        background: #fafbfc; overflow: hidden;
    }
    .preview-box img { max-width: 100%; max-height: 100%; object-fit: contain; }
</style>

<div class="page-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="<?= url('Product/list') ?>">Sản phẩm</a></li>
                <li class="breadcrumb-item"><a href="<?= url('Product/detail/' . $product->getID()) ?>">Chi tiết</a></li>
                <li class="breadcrumb-item active">Sửa</li>
            </ol>
        </nav>
        <h4 class="fw-bold mt-2 mb-0"><i class="bi bi-pencil-square text-warning me-2"></i>Sửa sản phẩm</h4>
    </div>
</div>

<div class="container py-4 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card-modern p-4">
                <form action="<?= url('Product/edit/' . $product->getID()) ?>" method="POST">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tên sản phẩm</label>
                                <input type="text" name="name" class="form-control form-control-lg"
                                       value="<?= htmlspecialchars($product->getName()) ?>" required>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Giá bán (VNĐ)</label>
                                    <input type="number" name="price" class="form-control"
                                           value="<?= (int) $product->getPrice() ?>" min="0" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Danh mục</label>
                                    <select name="category_id" class="form-select">
                                        <option value="">-- Chọn danh mục --</option>
                                        <?php foreach ($categories as $cat): ?>
                                        <option value="<?= (int) $cat['id'] ?>"
                                            <?= (int) $product->getCategoryId() === (int) $cat['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 mt-3">
                                <label class="form-label fw-semibold">URL hình ảnh</label>
                                <input type="url" id="img_url" name="image" class="form-control"
                                       value="<?= htmlspecialchars($product->getImage()) ?>" oninput="updatePreview()">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mô tả chi tiết</label>
                                <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($product->getDescription()) ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Xem trước</label>
                            <div class="preview-box" id="preview">
                                <img src="<?= htmlspecialchars($product->getImage()) ?>" id="img_tag" alt="">
                            </div>
                            <a href="<?= url('Product/detail/' . $product->getID()) ?>" class="btn btn-outline-brand btn-sm w-100 mt-3">
                                <i class="bi bi-eye me-1"></i> Xem trang chi tiết
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= url('Product/detail/' . $product->getID()) ?>" class="btn btn-light px-4">Hủy</a>
                        <button type="submit" class="btn btn-brand px-4"><i class="bi bi-check-lg me-1"></i> Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updatePreview() {
    const url = document.getElementById('img_url').value;
    const img = document.getElementById('img_tag');
    if (url) { img.src = url; img.style.display = 'block'; }
    else { img.style.display = 'none'; }
}
window.onload = updatePreview;
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
