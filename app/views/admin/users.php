<?php $pageTitle = 'Quản lý người dùng'; ?>
<?php include __DIR__ . '/../layout/header.php'; ?>

<div class="container py-4">

    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-0"><i class="bi bi-people-fill text-danger me-2"></i>Quản lý người dùng</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="<?= url('Product/list') ?>" class="text-danger text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Quản lý người dùng</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <!-- Search -->
            <form method="GET" class="d-flex">
                <div class="input-group input-group-sm" style="min-width:220px">
                    <input type="text" name="search" class="form-control"
                           placeholder="Tìm theo tên, email..."
                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    <button class="btn btn-danger" type="submit"><i class="bi bi-search"></i></button>
                    <?php if (!empty($_GET['search'])): ?>
                        <a href="<?= url('Admin/users') ?>" class="btn btn-outline-secondary">✕</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Thống kê -->
    <div class="row g-3 mb-4">
        <?php
        $totalUsers  = count($users);
        $activeCount = count(array_filter((array)$users, fn($u) => $u['is_active'] == 1));
        $adminCount  = count(array_filter((array)$users, fn($u) => $u['role'] === 'Admin'));
        $lockedCount = $totalUsers - $activeCount;
        ?>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 text-center p-3 h-100">
                <div class="fs-4 fw-bold text-primary"><?= $totalUsers ?></div>
                <div class="small text-muted">Tổng người dùng</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 text-center p-3 h-100">
                <div class="fs-4 fw-bold text-success"><?= $activeCount ?></div>
                <div class="small text-muted">Đang hoạt động</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 text-center p-3 h-100">
                <div class="fs-4 fw-bold text-danger"><?= $lockedCount ?></div>
                <div class="small text-muted">Bị khóa</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-3 text-center p-3 h-100">
                <div class="fs-4 fw-bold text-warning"><?= $adminCount ?></div>
                <div class="small text-muted">Admin</div>
            </div>
        </div>
    </div>

    <!-- Bảng người dùng -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">#</th>
                            <th>Người dùng</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Email xác thực</th>
                            <th>Ngày tạo</th>
                            <th class="text-center pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Không tìm thấy người dùng nào.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $i = 1; foreach ($users as $u): ?>
                        <tr>
                            <td class="ps-4 text-muted small"><?= $i++ ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="<?= htmlspecialchars(userAvatarUrl($u['avatar'] ?? null)) ?>"
                                         class="rounded-circle" width="38" height="38" style="object-fit:cover" alt="">
                                    <div>
                                        <div class="fw-semibold small"><?= htmlspecialchars($u['fullname']) ?></div>
                                        <div class="text-muted" style="font-size:12px">@<?= htmlspecialchars($u['username']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="small"><?= htmlspecialchars($u['email']) ?></td>
                            <td>
                                <?php if ($u['id'] != $_SESSION['user']['id']): ?>
                                <form method="POST" action="<?= url('Admin/changeRole') ?>" class="d-inline">
                                    <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                    <select name="role" class="form-select form-select-sm"
                                            style="width:95px"
                                            onchange="this.form.submit()">
                                        <option value="User" <?= $u['role'] === 'User' ? 'selected' : '' ?>>User</option>
                                        <option value="Admin" <?= $u['role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                </form>
                                <?php else: ?>
                                    <span class="badge bg-danger rounded-pill">
                                        <i class="bi bi-shield-fill-check me-1"></i>Admin (Bạn)
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($u['is_active']): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        <i class="bi bi-circle-fill me-1" style="font-size:8px"></i>Hoạt động
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                        <i class="bi bi-circle-fill me-1" style="font-size:8px"></i>Bị khóa
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($u['email_verified_at'])): ?>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">
                                        <i class="bi bi-check-circle-fill me-1"></i>Đã xác thực
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                        <i class="bi bi-clock-fill me-1"></i>Chưa xác thực
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted"><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                            <td class="text-center pe-4">
                                <?php if ($u['id'] != $_SESSION['user']['id']): ?>
                                <div class="d-flex gap-1 justify-content-center">
                                    <?php if ($u['is_active']): ?>
                                        <a href="<?= url('Admin/toggleStatus') ?>?id=<?= $u['id'] ?>&status=0"
                                           class="btn btn-sm btn-outline-warning"
                                           title="Khóa tài khoản"
                                           onclick="return confirm('Khóa tài khoản <?= htmlspecialchars(addslashes($u['fullname'])) ?>?')">
                                            <i class="bi bi-lock-fill"></i>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= url('Admin/toggleStatus') ?>?id=<?= $u['id'] ?>&status=1"
                                           class="btn btn-sm btn-outline-success"
                                           title="Mở khóa tài khoản">
                                            <i class="bi bi-unlock-fill"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?= url('Admin/deleteUser') ?>?id=<?= $u['id'] ?>"
                                       class="btn btn-sm btn-outline-danger"
                                       title="Xóa tài khoản"
                                       onclick="return confirm('Xóa vĩnh viễn tài khoản này? Hành động không thể hoàn tác!')">
                                        <i class="bi bi-trash3-fill"></i>
                                    </a>
                                </div>
                                <?php else: ?>
                                    <span class="text-muted small">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../layout/footer.php'; ?>
