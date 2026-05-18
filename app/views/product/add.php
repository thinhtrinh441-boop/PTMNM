<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm mới - CellphoneS Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --cps-red: #d70018; }
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; border: none; }
        .btn-cps { background-color: var(--cps-red); color: white; font-weight: bold; }
        .btn-cps:hover { background-color: #b50014; color: white; }
        .image-preview { 
            width: 100%; height: 200px; border: 2px dashed #ddd; 
            display: flex; align-items: center; justify-content: center; border-radius: 10px; overflow: hidden;
        }
        .image-preview img { max-height: 100%; max-width: 100%; object-fit: contain; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card shadow">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold text-danger"><i class="bi bi-plus-circle me-2"></i>THÊM SẢN PHẨM MỚI</h5>
                </div>
                <div class="card-body p-4">
                    <form action="/project1/Product/add" method="POST">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tên sản phẩm</label>
                                    <input type="text" name="name" class="form-control" placeholder="Nhập tên sản phẩm..." required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Giá bán (VNĐ)</label>
                                    <input type="number" name="price" class="form-control" placeholder="Ví dụ: 15000000" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Link hình ảnh (URL)</label>
                                    <input type="text" id="image_url" name="image" class="form-control" placeholder="Dán link ảnh từ internet..." oninput="previewImg()">
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <label class="form-label fw-bold">Xem trước ảnh</label>
                                <div class="image-preview" id="preview_box">
                                    <i class="bi bi-image text-muted fs-1"></i>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Mô tả chi tiết</label>
                                    <textarea name="description" class="form-control" rows="4"></textarea>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-cps px-5">LƯU SẢN PHẨM</button>
                                    <a href="/project1/Product/list" class="btn btn-outline-secondary">Hủy bỏ</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImg() {
        const url = document.getElementById('image_url').value;
        const box = document.getElementById('preview_box');
        if(url) {
            box.innerHTML = `<img src="${url}" onerror="this.src='https://placehold.co/200x200?text=Lỗi+Link'">`;
        } else {
            box.innerHTML = `<i class="bi bi-image text-muted fs-1"></i>`;
        }
    }
</script>
</body>
</html>