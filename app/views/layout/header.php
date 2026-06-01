<?php
require_once __DIR__ . '/../../helpers/auth.php';
$pageTitle = $pageTitle ?? 'Thế Giới Điện Tử';
$navActive = $navActive ?? '';
$cartQty = cart_item_count();
$wishQty = wishlist_count();
?>
<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root, [data-theme="light"] {
            --brand:#d70018; --brand-dark:#a80012; --surface:#f4f6f9;
            --card:#fff; --text:#1a1d26; --muted:#6c757d; --border:#e9ecef;
        }
        [data-theme="dark"] {
            --brand:#ff4d4d; --brand-dark:#d70018; --surface:#12141a;
            --card:#1e222b; --text:#e8eaed; --muted:#9aa0a6; --border:#2d333b;
        }
        *{font-family:'Be Vietnam Pro',sans-serif}
        body{background:var(--surface);color:var(--text);min-height:100vh;display:flex;flex-direction:column}
        .site-nav{background:linear-gradient(135deg,var(--brand),#ff6b6b);box-shadow:0 4px 16px rgba(215,0,24,.2)}
        .nav-link-custom{color:rgba(255,255,255,.92)!important;text-decoration:none;font-weight:500;padding:.35rem .7rem;border-radius:8px}
        .nav-link-custom:hover,.nav-link-custom.active{background:rgba(255,255,255,.18)}
        .search-box{background:#fff;border-radius:10px;overflow:hidden;max-width:360px}
        [data-theme="dark"] .search-box{background:var(--card)}
        .cart-badge{font-size:10px;min-width:18px;height:18px}
        .card-modern{background:var(--card);border:1px solid var(--border);border-radius:14px;box-shadow:0 6px 24px rgba(0,0,0,.06)}
        .page-hero{background:var(--card);border-bottom:1px solid var(--border);padding:1.1rem 0}
        .btn-brand{background:var(--brand);color:#fff;font-weight:600;border:none;border-radius:10px}
        .btn-brand:hover{background:var(--brand-dark);color:#fff}
        .btn-outline-brand{border:2px solid var(--brand);color:var(--brand);font-weight:600;border-radius:10px}
        .btn-outline-brand:hover{background:var(--brand);color:#fff}
        .price-text{color:var(--brand);font-weight:800}
        .product-card-v2{background:var(--card);border:1px solid var(--border);border-radius:14px;height:100%;display:flex;flex-direction:column;transition:.25s}
        .product-card-v2:hover{transform:translateY(-4px);box-shadow:0 12px 28px rgba(0,0,0,.1)}
        .product-card-v2 .img-box{height:190px;display:flex;align-items:center;justify-content:center;padding:1rem;background:var(--surface)}
        .product-card-v2 img{max-height:100%;max-width:100%;object-fit:contain}
        .sale-badge{position:absolute;top:10px;left:10px;z-index:2}
        .wish-btn{position:absolute;top:10px;right:10px;z-index:2}
        main{flex:1}
        .toast-container{z-index:9999}
        .user-dropdown .dropdown-toggle::after{display:none}
        .user-dropdown .dropdown-menu{min-width:220px;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.12);border:1px solid var(--border)}
        .user-dropdown .dropdown-item{padding:.6rem 1rem;font-size:.875rem}
        .user-dropdown .dropdown-item:hover{background:rgba(215,0,24,.08);color:var(--brand)}
        @media(min-width:1200px){.col-5-grid{flex:0 0 20%;max-width:20%}}
    </style>
</head>
<body>

<nav class="navbar navbar-dark site-nav sticky-top py-2">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= url('Product/list') ?>">
            <i class="bi bi-lightning-charge-fill"></i> <span class="d-none d-sm-inline">Điện Tử Shop</span>
        </a>

        <form class="search-box d-none d-md-flex flex-grow-1 mx-3" action="<?= url('Product/list') ?>" method="get">
            <input type="text" name="q" class="form-control border-0" placeholder="Tìm sản phẩm..."
                   value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            <button class="btn btn-danger px-3" type="submit"><i class="bi bi-search"></i></button>
        </form>

        <div class="d-flex align-items-center gap-1">
            <button type="button" class="btn btn-sm btn-light" id="darkModeBtn" title="Dark mode"><i class="bi bi-moon-stars"></i></button>

            <a href="<?= url('Wishlist/index') ?>" class="nav-link-custom position-relative">
                <i class="bi bi-heart"></i>
                <?php if ($wishQty > 0): ?><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark cart-badge"><?= $wishQty ?></span><?php endif; ?>
            </a>

            <a href="<?= url('Cart/view') ?>" class="nav-link-custom position-relative">
                <i class="bi bi-cart3 fs-5"></i>
                <?php if ($cartQty > 0): ?><span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning text-dark cart-badge"><?= $cartQty ?></span><?php endif; ?>
            </a>

            <?php if (isAdmin()): ?>
                <a href="<?= url('Product/add') ?>" class="btn btn-light btn-sm fw-bold text-danger d-none d-lg-inline-block">
                    <i class="bi bi-plus-lg"></i>
                </a>
            <?php endif; ?>

            <!-- User info / Login buttons -->
            <?php if (isLoggedIn()): ?>
                <?php $cu = currentUser(); ?>
                <div class="dropdown user-dropdown">
                    <button class="btn btn-light btn-sm d-flex align-items-center gap-2 dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= htmlspecialchars(userAvatarUrl($cu['avatar'] ?? null)) ?>"
                             class="rounded-circle" width="26" height="26"
                             style="object-fit:cover" alt="">
                        <span class="d-none d-md-inline fw-semibold" style="max-width:110px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                            <?= htmlspecialchars($cu['fullname']) ?>
                        </span>
                        <?php if ($cu['role'] === 'Admin'): ?>
                            <span class="badge bg-danger rounded-pill" style="font-size:10px">Admin</span>
                        <?php endif; ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <!-- Thông tin người dùng -->
                        <li class="px-3 py-2 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?= htmlspecialchars(userAvatarUrl($cu['avatar'] ?? null)) ?>"
                                     class="rounded-circle" width="42" height="42" style="object-fit:cover" alt="">
                                <div>
                                    <div class="fw-bold small"><?= htmlspecialchars($cu['fullname']) ?></div>
                                    <div class="text-muted" style="font-size:12px"><?= htmlspecialchars($cu['email']) ?></div>
                                    <?php if ($cu['role'] === 'Admin'): ?>
                                        <span class="badge bg-danger" style="font-size:10px">
                                            <i class="bi bi-shield-fill-check me-1"></i>Admin
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-primary" style="font-size:10px">
                                            <i class="bi bi-person-fill me-1"></i>Người dùng
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                        <li>
                            <a class="dropdown-item" href="<?= url('Auth/profile') ?>">
                                <i class="bi bi-person-circle text-primary me-2"></i>Hồ sơ cá nhân
                            </a>
                        </li>
                        <?php if (isAdmin()): ?>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <a class="dropdown-item text-danger fw-semibold" href="<?= url('Admin/users') ?>">
                                    <i class="bi bi-shield-lock-fill me-2"></i>Quản lý người dùng
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="<?= url('Product/add') ?>">
                                    <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm
                                </a>
                            </li>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="<?= url('Auth/logout') ?>">
                                <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                            </a>
                        </li>
                    </ul>
                </div>

            <?php else: ?>
                <a href="<?= url('Auth/login') ?>" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span class="d-none d-sm-inline ms-1">Đăng nhập</span>
                </a>
                <a href="<?= url('Auth/register') ?>" class="btn btn-light btn-sm text-danger fw-bold">
                    <i class="bi bi-person-plus"></i>
                    <span class="d-none d-sm-inline ms-1">Đăng ký</span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Mobile search -->
    <div class="container d-md-none pb-2">
        <form action="<?= url('Product/list') ?>" method="get" class="search-box w-100 d-flex">
            <input type="text" name="q" class="form-control border-0 form-control-sm" placeholder="Tìm kiếm..."
                   value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            <button class="btn btn-danger btn-sm"><i class="bi bi-search"></i></button>
        </form>
    </div>
</nav>

<!-- Flash messages -->
<div class="toast-container position-fixed top-0 end-0 p-3">
<?php if ($flash = flash_get()): ?>
    <div class="toast show align-items-center text-bg-<?= $flash['type'] === 'success' ? 'success' : ($flash['type'] === 'danger' ? 'danger' : ($flash['type'] === 'warning' ? 'warning' : 'primary')) ?> border-0" role="alert">
        <div class="d-flex">
            <div class="toast-body"><?= htmlspecialchars($flash['message']) ?></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
<?php endif; ?>
</div>

<main>
