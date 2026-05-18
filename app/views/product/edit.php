<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .preview-box { width: 100%; height: 250px; border: 2px dashed #ddd; border-radius: 10px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #fff; }
        .preview-box img { max-width: 100%; max-height: 100%; }
    </style>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card border-0 shadow-sm col-lg-8 mx-auto">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-4">Thông tin sản phẩm</h4>
            <form action="" method="POST">
                <div class="row">
                    <div class="col-md-7">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên sản phẩm</label>
                            <input type="text" name="name" class="form-control" value="<?= isset($product) ? htmlspecialchars($product->getName()) : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Giá bán (VNĐ)</label>
                            <input type="number" name="price" class="form-control" value="<?= isset($product) ? $product->getPrice() : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">URL Hình ảnh</label>
                            <input type="text" id="img_url" name="image" class="form-control" value="<?= isset($product) ? htmlspecialchars($product->getImage()) : '' ?>" oninput="updatePreview()">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Xem trước ảnh</label>
                        <div class="preview-box" id="preview">
                            <img src="<?= isset($product) ? htmlspecialchars($product->getImage()) : '' ?>" id="img_tag">
                        </div>
                    </div>
                </div>
                <div class="mb-3 mt-3">
                    <label class="form-label fw-bold">Mô tả</label>
                    <textarea name="description" class="form-control" rows="4"><?= isset($product) ? htmlspecialchars($product->getDescription()) : '' ?></textarea>
                </div>
                <hr>
                <div class="d-flex justify-content-end gap-2">
                    <a href="/project1/Product/list" class="btn btn-light px-4">Hủy</a>
                    <button type="submit" class="btn btn-primary px-4">Lưu sản phẩm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updatePreview() {
        const url = document.getElementById('img_url').value;
        const img = document.getElementById('img_tag');
        if (url) {
            img.src = url;
            img.style.display = 'block';
        } else {
            img.style.display = 'none';
        }
    }
    window.onload = updatePreview;
</script>
</body>
</html>