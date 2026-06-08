<?php
$pageTitle = 'Quản lý sản phẩm';
$navActive  = 'admin';
include __DIR__ . '/../layout/header.php';
requireAdmin();
$filters = [
    'q'           => trim($_GET['q'] ?? ''),
    'category_id' => $_GET['category_id'] ?? '',
    'sort'        => $_GET['sort'] ?? 'newest',
];
$page   = max(1, (int) ($_GET['page'] ?? 1));
$result = ProductModel::search($filters, $page, 10);
$products   = $result['items'];
$pagination = $result;
$categories = ProductModel::getCategories();
$flash      = flash_get();
?>

<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="fw-bold mb-0"><i class="bi bi-box-seam me-2 text-danger"></i>Quản lý sản phẩm</h4>
    <a href="<?= url('Product/add') ?>" class="btn btn-danger btn-sm fw-bold">
      <i class="bi bi-plus-lg me-1"></i>Thêm sản phẩm
    </a>
  </div>

  <?php if ($flash): ?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($flash['message']) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <!-- Bộ lọc -->
  <form method="GET" action="<?= url('admin/products') ?>" class="row g-2 mb-3">
    <div class="col-md-4">
      <input type="text" name="q" class="form-control form-control-sm"
             placeholder="Tìm theo tên…" value="<?= htmlspecialchars($filters['q']) ?>">
    </div>
    <div class="col-md-3">
      <select name="category_id" class="form-select form-select-sm">
        <option value="">-- Tất cả danh mục --</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>" <?= $filters['category_id'] == $cat['id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <select name="sort" class="form-select form-select-sm">
        <option value="newest"     <?= $filters['sort'] === 'newest'     ? 'selected' : '' ?>>Mới nhất</option>
        <option value="price_asc"  <?= $filters['sort'] === 'price_asc'  ? 'selected' : '' ?>>Giá tăng dần</option>
        <option value="price_desc" <?= $filters['sort'] === 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
        <option value="name"       <?= $filters['sort'] === 'name'       ? 'selected' : '' ?>>Theo tên A→Z</option>
      </select>
    </div>
    <div class="col-md-2">
      <button type="submit" class="btn btn-outline-danger btn-sm w-100">
        <i class="bi bi-search me-1"></i>Lọc
      </button>
    </div>
  </form>

  <!-- Bảng sản phẩm -->
  <div class="table-responsive rounded shadow-sm">
    <table class="table table-hover align-middle mb-0 bg-white">
      <thead class="table-dark">
        <tr>
          <th style="width:50px">ID</th>
          <th style="width:60px">Ảnh</th>
          <th>Tên sản phẩm</th>
          <th>Danh mục</th>
          <th class="text-end">Giá</th>
          <th class="text-center">Kho</th>
          <th class="text-center" style="width:150px">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($products)): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">Không có sản phẩm nào.</td></tr>
        <?php else: ?>
          <?php foreach ($products as $p): ?>
          <tr>
            <td class="text-muted small">#<?= $p->getID() ?></td>
            <td>
              <img src="<?= htmlspecialchars(product_image_url($p->getImage())) ?>"
                   alt="" class="rounded" style="width:48px;height:48px;object-fit:cover">
            </td>
            <td>
              <a href="<?= url('Product/detail/' . $p->getID()) ?>" class="fw-semibold text-decoration-none text-dark">
                <?= htmlspecialchars($p->getName()) ?>
              </a>
            </td>
            <td>
              <?php if ($p->getCategoryName()): ?>
                <span class="badge bg-secondary"><?= htmlspecialchars($p->getCategoryName()) ?></span>
              <?php else: ?>
                <span class="text-muted small">—</span>
              <?php endif; ?>
            </td>
            <td class="text-end fw-semibold text-danger"><?= format_price($p->getPrice()) ?></td>
            <td class="text-center">
              <?php $stock = $p->getStock(); ?>
              <span class="badge <?= $stock > 10 ? 'bg-success' : ($stock > 0 ? 'bg-warning text-dark' : 'bg-danger') ?>">
                <?= $stock ?> sp
              </span>
            </td>
            <td class="text-center">
              <a href="<?= url('Product/edit/' . $p->getID()) ?>"
                 class="btn btn-outline-primary btn-sm me-1" title="Sửa">
                <i class="bi bi-pencil"></i>
              </a>
              <a href="<?= url('Product/deleteConfirm/' . $p->getID()) ?>"
                 class="btn btn-outline-danger btn-sm" title="Xóa">
                <i class="bi bi-trash"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Phân trang -->
  <div class="mt-3 d-flex justify-content-between align-items-center">
    <small class="text-muted">
      Tổng: <strong><?= $pagination['total'] ?></strong> sản phẩm
      | Trang <strong><?= $pagination['page'] ?></strong> / <?= $pagination['totalPages'] ?>
    </small>
    <nav><?= pagination_links($pagination['page'], $pagination['totalPages'], array_filter($filters)) ?></nav>
  </div>
</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
