<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán - Tech Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .checkout-card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="d-flex align-items-center mb-4">
                <a href="/project1/Cart/index" class="btn btn-outline-secondary btn-sm me-3"><i class="bi bi-arrow-left"></i></a>
                <h3 class="fw-bold m-0">Xác nhận thanh toán</h3>
            </div>           
            <div class="card checkout-card p-4">
                <form method="POST" action="/project1/Cart/processCheckout">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Họ tên người nhận:</label>
                        <input type="text" name="name" class="form-control" placeholder="Nhập họ tên" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Số điện thoại:</label>
                        <input type="text" name="phone" class="form-control" placeholder="Nhập số điện thoại" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Địa chỉ giao hàng:</label>
                        <textarea name="address" class="form-control" rows="3" placeholder="Nhập địa chỉ chi tiết" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold">
                        <i class="bi bi-check-circle me-2"></i>HOÀN TẤT ĐẶT HÀNG
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>