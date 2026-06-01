<?php
$pageTitle = 'Cửa hàng điện tử';
$navActive = 'shop';
include __DIR__ . '/../layout/header.php';
$q = $_GET['q'] ?? '';
$cat = $_GET['category'] ?? '';
$sort = $_GET['sort'] ?? 'newest';
?>

<!-- Banner slider -->
<div id="homeCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="0" class="active"></button>
        <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="1"></button>
        <button type="button" data-bs-target="#homeCarousel" data-bs-slide-to="2"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <div class="bg-danger text-white text-center py-5">
                <h2 class="fw-bold">SALE ĐIỆN THOẠI — Giảm đến 12%</h2>
                <p class="mb-3">iPhone, Samsung, Xiaomi chính hãng</p>
                <a href="<?= url('Product/list') ?>" class="btn btn-light btn-lg fw-bold">Mua ngay</a>
            </div>
        </div>
        <div class="carousel-item">
            <div class="bg-dark text-white text-center py-5">
                <h2 class="fw-bold">LAPTOP M3 — Trả góp 0%</h2>
                <p class="mb-0">MacBook, gaming, văn phòng</p>
            </div>
        </div>
        <div class="carousel-item">
            <div style="background:linear-gradient(90deg,#ff9f43,#d70018)" class="text-white text-center py-5">
                <h2 class="fw-bold">PHỤ KIỆN — Freeship toàn quốc</h2>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#homeCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
    <button class="carousel-control-next" type="button" data-bs-target="#homeCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
</div>

<div class="container py-4 pb-5">
    <!-- Lọc & sắp xếp -->
    <div class="card-modern p-3 mb-4">
        <form method="get" action="<?= url('Product/list') ?>" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Tìm kiếm</label>
                <input type="text" name="q" class="form-control" value="<?= htmlspecialchars($q) ?>" placeholder="Tên sản phẩm...">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Danh mục</label>
                <select name="category" class="form-select">
                    <option value="">Tất cả</option>
                    <?php foreach ($categories as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= (string)$cat === (string)$c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Sắp xếp</label>
                <select name="sort" class="form-select">
                    <option value="newest" <?= $sort==='newest'?'selected':'' ?>>Mới nhất</option>
                    <option value="price_asc" <?= $sort==='price_asc'?'selected':'' ?>>Giá tăng dần</option>
                    <option value="price_desc" <?= $sort==='price_desc'?'selected':'' ?>>Giá giảm dần</option>
                    <option value="name" <?= $sort==='name'?'selected':'' ?>>Tên A-Z</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-brand w-100"><i class="bi bi-funnel"></i> Lọc</button>
            </div>
        </form>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Kết quả: <?= $pagination['total'] ?> sản phẩm</h5>
        <span class="text-muted small">Trang <?= $pagination['page'] ?>/<?= $pagination['totalPages'] ?></span>
    </div>

    <?php if (!empty($products)): ?>
    <div class="row g-3">
        <?php foreach ($products as $p): include __DIR__ . '/_card.php'; endforeach; ?>
    </div>
    <div class="mt-4"><?= pagination_links($pagination['page'], $pagination['totalPages'], $queryParams) ?></div>
    <?php else: ?>
    <div class="card-modern text-center py-5">
        <i class="bi bi-search display-4 text-muted"></i>
        <p class="mt-2">Không tìm thấy sản phẩm phù hợp.</p>
        <a href="<?= url('Product/list') ?>" class="btn btn-brand">Xem tất cả</a>
    </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
