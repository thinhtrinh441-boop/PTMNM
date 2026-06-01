<?php
$pageTitle = 'Thêm sản phẩm mới';
$navActive = 'shop';
include __DIR__ . '/../layout/header.php';
?>

<style>
    .image-preview {
        height: 220px; border: 2px dashed #dee2e6; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        background: #fafbfc; overflow: hidden;
    }
    .image-preview img { max-height: 100%; max-width: 100%; object-fit: contain; }
</style>

<div class="page-hero">
    <div class="container">
        <h4 class="fw-bold mb-0"><i class="bi bi-plus-circle text-danger me-2"></i>Thêm sản phẩm mới</h4>
    </div>
</div>

<div class="container py-4 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card-modern p-4">
                <form action="<?= url('Product/add') ?>" method="POST">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg" placeholder="VD: iPhone 15 Pro Max 256GB" required>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" name="price" class="form-control" min="0" step="1000" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Danh mục</label>
                                    <select name="category_id" class="form-select">
                                        <option value="">-- Chọn danh mục --</option>
                                        <?php foreach ($categories as $cat): ?>
                                        <option value="<?= (int) $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3 mt-3">
                                <label class="form-label fw-semibold">Link hình ảnh (URL)</label>
                                <input type="url" id="image_url" name="image" class="form-control" placeholder="https://..." oninput="previewImg()">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Mô tả chi tiết</label>
                                <textarea name="description" class="form-control" rows="5" placeholder="Mô tả đầy đủ hiển thị ở trang chi tiết sản phẩm..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Xem trước ảnh</label>
                            <div class="image-preview" id="preview_box">
                                <div class="text-center text-muted">
                                    <i class="bi bi-image fs-1 d-block mb-2"></i>
                                    <small>Nhập URL để xem trước</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-brand px-4"><i class="bi bi-check-lg me-1"></i> Lưu sản phẩm</button>
                        <a href="<?= url('Product/list') ?>" class="btn btn-outline-secondary px-4">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewImg() {
    const url = document.getElementById('image_url').value;
    const box = document.getElementById('preview_box');
    box.innerHTML = url
        ? `<img src="${url}" alt="preview" onerror="this.parentElement.innerHTML='<span class=\\'text-danger\\'>Link ảnh không hợp lệ</span>'">`
        : `<div class="text-center text-muted"><i class="bi bi-image fs-1 d-block mb-2"></i><small>Nhập URL để xem trước</small></div>`;
}
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
