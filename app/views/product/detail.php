<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .product-card { border-radius: 15px; overflow: hidden; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/project1/Product/list">Danh sách</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Chi tiết sản phẩm</li>
                    </ol>
                </nav>

                <div class="card product-card shadow-lg border-0">
                    <div class="card-header bg-dark text-white py-3">
                        <h4 class="mb-0 text-center"><i class="bi bi-info-circle me-2"></i>Thông Tin Chi Tiết</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted fw-bold">Tên sản phẩm:</div>
                            <div class="col-sm-8 fs-5 fw-bold text-primary"><?= htmlspecialchars($product->getName()) ?></div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted fw-bold">Giá bán:</div>
                            <div class="col-sm-8 fs-5 text-danger fw-bold"><?= number_format($product->getPrice(), 0, ',', '.') ?> VNĐ</div>
                        </div>
                        <hr>
                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted fw-bold">Mô tả sản phẩm:</div>
                            <div class="col-sm-8 text-secondary" style="white-space: pre-line;">
                                <?= !empty($product->getDescription()) ? htmlspecialchars($product->getDescription()) : '<i>Không có mô tả cho sản phẩm này.</i>' ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light p-3 d-flex justify-content-between">
                        <a href="/project1/Product/list" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                        <div>
                            <a href="/project1/Product/edit/<?= $product->getID() ?>" class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i> Chỉnh sửa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>