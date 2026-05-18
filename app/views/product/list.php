<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thế Giới Di Động - Hệ thống Quản lý</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --tgdd-orange: #ff9f43;
            --tgdd-dark: #2f3542;
            --price-red: #d70018;
        }
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Arial, sans-serif; }
        
        /* Navbar */
        .navbar-custom { background-color: var(--tgdd-orange); padding: 12px 0; border-bottom: 3px solid #e67e22; }
        
        /* Product Card */
        .product-card {
            background: #fff;
            border-radius: 8px;
            padding: 15px;
            transition: all 0.3s ease;
            height: 100%;
            border: 1px solid #eee;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        .product-card:hover { 
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            border-color: var(--tgdd-orange);
            transform: translateY(-5px);
        }
        
        /* Promo Labels */
        .promo-label {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 4px;
            z-index: 2;
        }
        .label-installment { background: #f1f1f1; color: #333; border: 1px solid #ddd; }

        .product-img-wrapper {
            width: 100%;
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            background: #fff;
        }
        .product-img { max-width: 100%; max-height: 100%; object-fit: contain; transition: 0.3s; }
        
        .product-name {
            font-size: 14px;
            font-weight: 600;
            color: #333;
            line-height: 1.5;
            height: 42px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            margin-bottom: 10px;
            text-decoration: none;
        }

        .product-price { 
            color: var(--price-red); 
            font-weight: 700; 
            font-size: 17px; 
            margin-bottom: 15px;
        }

        /* Buttons */
        .btn-buy {
            background-color: var(--price-red);
            color: white;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
            font-size: 12px;
            padding: 8px;
            width: 100%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-buy:hover { background-color: #b30014; color: white; }

        .admin-controls {
            position: absolute;
            top: 8px;
            right: 8px;
            display: flex;
            flex-direction: column;
            gap: 5px;
            z-index: 5;
        }
        .btn-admin { 
            width: 32px; height: 32px; 
            display: flex; align-items: center; justify-content: center; 
            border-radius: 50%; font-size: 14px; opacity: 0.8;
        }
        .btn-admin:hover { opacity: 1; transform: scale(1.1); }

        @media (min-width: 1200px) {
            .col-xl-custom { width: 20%; flex: 0 0 20%; }
        }
    </style>
</head>
<body>

<nav class="navbar-custom shadow-sm mb-4 sticky-top">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand fw-bold text-white d-flex align-items-center" href="/project1/Product/list">
            <i class="bi bi-phone-vibrate-fill me-2 fs-3"></i>
            <span class="d-none d-lg-inline text-uppercase">Thế Giới Điện Tử</span>
        </a>

        <div class="d-flex align-items-center gap-3">
            <a href="/project1/Cart/view" class="text-white position-relative text-decoration-none me-2">
                <i class="bi bi-cart3 fs-4"></i>
                <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                        <?= count($_SESSION['cart']) ?>
                    </span>
                <?php endif; ?>
            </a>
            
            <a href="/project1/Product/add" class="btn btn-dark btn-sm fw-bold px-3">
                <i class="bi bi-plus-circle me-1"></i> Nhập hàng
            </a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm">
        <div class="d-flex align-items-center">
            <h5 class="fw-bold mb-0 text-dark border-start border-4 border-warning ps-3 text-uppercase">Điện thoại nổi bật</h5>
            <span class="badge bg-danger ms-2" style="font-size: 10px;">GIÁ RẺ QUÁ</span>
        </div>
        <span class="badge bg-light text-dark border">Tổng: <?= count($products) ?> sản phẩm</span>
    </div>

    <div class="row g-3">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $p): ?>
                <div class="col-6 col-md-4 col-lg-3 col-xl-custom">
                    <div class="product-card">
                        <div class="promo-label label-installment">Trả góp 0%</div>
                        
                        <div class="admin-controls">
                            <a href="/project1/Product/edit/<?= $p->getID() ?>" class="btn btn-warning btn-admin shadow-sm" title="Sửa">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="/project1/Product/delete/<?= $p->getID() ?>" 
                               class="btn btn-danger btn-admin shadow-sm" 
                               onclick="return confirm('Bạn chắc chắn muốn xóa sản phẩm này?');" title="Xóa">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>

                        <div class="product-img-wrapper">
                            <img src="<?= htmlspecialchars($p->getImage() ?: 'https://placehold.co/300x300?text=No+Image') ?>" 
                                 class="product-img" 
                                 onerror="this.src='https://placehold.co/300x300?text=Lỗi+Ảnh'">
                        </div>
                        
                        <a href="#" class="product-name"><?= htmlspecialchars($p->getName()) ?></a>

                        <div class="mb-2">
                             <span class="badge bg-light text-muted border" style="font-size: 9px;">8GB RAM</span>
                             <span class="badge bg-light text-muted border" style="font-size: 9px;">256GB</span>
                        </div>
                        
                        <div class="product-price">
                            <?= number_format($p->getPrice(), 0, ',', '.') ?>đ
                        </div>
                        
                        <div class="mt-auto d-flex gap-1">
                            <a href="/project1/Cart/add/<?= $p->getID() ?>" class="btn-buy text-decoration-none flex-grow-1">
                                MUA NGAY
                            </a>
                            
                            <a href="/project1/Cart/add/<?= $p->getID() ?>" class="btn btn-outline-danger btn-sm px-2 d-flex align-items-center">
                                <i class="bi bi-cart-plus fs-5"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <?php endif; ?>
    </div>
</div>

<footer class="bg-white border-top py-4 mt-5 text-center">
    <p class="mb-1 fw-bold">Hệ thống Quản Lý Bán Hàng 2026</p>
    <small class="text-muted">Powered by PHP MVC & Bootstrap 5</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>