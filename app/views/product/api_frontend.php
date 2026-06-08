<?php
$pageTitle = 'Quản lý sản phẩm – API Demo';
$navActive = 'shop';
include __DIR__ . '/../layout/header.php';
?>

<style>
:root { --api-base: '<?= rtrim(url('api'), '/') ?>'; }
.api-badge { font-size:11px; font-family:monospace; }
.product-card { transition:.2s; border:1px solid #e9ecef; }
.product-card:hover { transform:translateY(-3px); box-shadow:0 8px 25px rgba(0,0,0,.12); }
.product-card img { height:160px; object-fit:cover; }
.toast-wrap { position:fixed; bottom:20px; right:20px; z-index:9999; min-width:280px; }
#json-output { background:#1e1e2e; color:#cdd6f4; font-size:12px; border-radius:8px;
               max-height:300px; overflow-y:auto; white-space:pre-wrap; }
.method-badge { font-size:10px; padding:2px 7px; border-radius:4px; font-weight:700; }
.badge-get    { background:#0d6efd; color:#fff; }
.badge-post   { background:#198754; color:#fff; }
.badge-put    { background:#fd7e14; color:#fff; }
.badge-patch  { background:#6f42c1; color:#fff; }
.badge-delete { background:#dc3545; color:#fff; }
.stock-badge  { font-size:.75rem; }
</style>

<div class="container py-4">

  <!-- Header -->
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h4 class="fw-bold mb-0"><i class="bi bi-plug-fill text-danger me-2"></i>Product API – Frontend jQuery</h4>
      <small class="text-muted">Base URL: <code class="text-danger"><?= url('api/products') ?></code></small>
    </div>
    <a href="<?= url('admin/products') ?>" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-table me-1"></i>Quản lý bảng
    </a>
  </div>

  <div class="row g-4">

    <!-- ═══ LEFT: Bảng điều khiển API ════════════════════════════ -->
    <div class="col-lg-5">

      <!-- Nav tabs -->
      <ul class="nav nav-pills mb-3 flex-wrap gap-1" id="apiTabs">
        <li class="nav-item">
          <button class="nav-link active" data-tab="tab-list">
            <span class="method-badge badge-get">GET</span> Danh sách
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-tab="tab-get">
            <span class="method-badge badge-get">GET</span> Chi tiết
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-tab="tab-post">
            <span class="method-badge badge-post">POST</span> Thêm
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-tab="tab-put">
            <span class="method-badge badge-put">PUT</span> Sửa
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-tab="tab-patch">
            <span class="method-badge badge-patch">PATCH</span> Patch
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link" data-tab="tab-delete">
            <span class="method-badge badge-delete">DELETE</span> Xóa
          </button>
        </li>
      </ul>

      <!-- ── GET List ── -->
      <div class="tab-pane" id="tab-list">
        <div class="card border-0 shadow-sm p-3">
          <h6 class="fw-bold mb-3"><span class="method-badge badge-get">GET</span> /api/products</h6>
          <div class="row g-2 mb-2">
            <div class="col-6">
              <input id="list-q" class="form-control form-control-sm" placeholder="Tìm theo tên…">
            </div>
            <div class="col-6">
              <select id="list-sort" class="form-select form-select-sm">
                <option value="newest">Mới nhất</option>
                <option value="price_asc">Giá tăng dần</option>
                <option value="price_desc">Giá giảm dần</option>
                <option value="name">Theo tên A→Z</option>
              </select>
            </div>
            <div class="col-4"><input id="list-page"     type="number" class="form-control form-control-sm" value="1" min="1" placeholder="Trang"></div>
            <div class="col-4"><input id="list-per-page" type="number" class="form-control form-control-sm" value="6" min="1" max="50" placeholder="Mỗi trang"></div>
            <div class="col-4"><input id="list-cat"      type="number" class="form-control form-control-sm" placeholder="Category ID"></div>
          </div>
          <button class="btn btn-primary btn-sm w-100" onclick="apiGetList()">
            <i class="bi bi-send me-1"></i>Gửi yêu cầu
          </button>
        </div>
      </div>

      <!-- ── GET Detail ── -->
      <div class="tab-pane d-none" id="tab-get">
        <div class="card border-0 shadow-sm p-3">
          <h6 class="fw-bold mb-3"><span class="method-badge badge-get">GET</span> /api/products/{id}</h6>
          <div class="mb-2">
            <input id="get-id" type="number" class="form-control form-control-sm" placeholder="ID sản phẩm">
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" id="get-related">
            <label class="form-check-label small" for="get-related">Xem sản phẩm liên quan (/related)</label>
          </div>
          <button class="btn btn-primary btn-sm w-100" onclick="apiGetDetail()">
            <i class="bi bi-send me-1"></i>Gửi yêu cầu
          </button>
        </div>
      </div>

      <!-- ── POST ── -->
      <div class="tab-pane d-none" id="tab-post">
        <div class="card border-0 shadow-sm p-3">
          <h6 class="fw-bold mb-3"><span class="method-badge badge-post">POST</span> /api/products</h6>
          <div class="row g-2">
            <div class="col-12"><input id="post-name"  class="form-control form-control-sm" placeholder="Tên sản phẩm *"></div>
            <div class="col-6"> <input id="post-price" type="number" class="form-control form-control-sm" placeholder="Giá *" min="0"></div>
            <div class="col-6"> <input id="post-stock" type="number" class="form-control form-control-sm" placeholder="Tồn kho" value="100" min="0"></div>
            <div class="col-6"> <input id="post-cat"   type="number" class="form-control form-control-sm" placeholder="Category ID"></div>
            <div class="col-6"> <input id="post-image" class="form-control form-control-sm" placeholder="URL ảnh"></div>
            <div class="col-12"><textarea id="post-desc" class="form-control form-control-sm" rows="2" placeholder="Mô tả"></textarea></div>
          </div>
          <button class="btn btn-success btn-sm w-100 mt-2" onclick="apiPost()">
            <i class="bi bi-plus-circle me-1"></i>Thêm sản phẩm
          </button>
        </div>
      </div>

      <!-- ── PUT ── -->
      <div class="tab-pane d-none" id="tab-put">
        <div class="card border-0 shadow-sm p-3">
          <h6 class="fw-bold mb-3"><span class="method-badge badge-put">PUT</span> /api/products/{id}</h6>
          <div class="row g-2">
            <div class="col-12"><input id="put-id"    type="number" class="form-control form-control-sm" placeholder="ID sản phẩm *"></div>
            <div class="col-12"><input id="put-name"  class="form-control form-control-sm" placeholder="Tên *"></div>
            <div class="col-6"> <input id="put-price" type="number" class="form-control form-control-sm" placeholder="Giá *"></div>
            <div class="col-6"> <input id="put-stock" type="number" class="form-control form-control-sm" placeholder="Kho" min="0"></div>
            <div class="col-6"> <input id="put-cat"   type="number" class="form-control form-control-sm" placeholder="Category ID"></div>
            <div class="col-6"> <input id="put-image" class="form-control form-control-sm" placeholder="URL ảnh"></div>
            <div class="col-12"><textarea id="put-desc" class="form-control form-control-sm" rows="2" placeholder="Mô tả"></textarea></div>
          </div>
          <button class="btn btn-warning btn-sm w-100 mt-2 text-white" onclick="apiPut()">
            <i class="bi bi-pencil me-1"></i>Cập nhật toàn bộ
          </button>
        </div>
      </div>

      <!-- ── PATCH ── -->
      <div class="tab-pane d-none" id="tab-patch">
        <div class="card border-0 shadow-sm p-3">
          <h6 class="fw-bold mb-3"><span class="method-badge badge-patch">PATCH</span> /api/products/{id}</h6>
          <small class="text-muted d-block mb-2">Chỉ điền trường muốn cập nhật</small>
          <div class="row g-2">
            <div class="col-12"><input id="patch-id"    type="number" class="form-control form-control-sm" placeholder="ID sản phẩm *"></div>
            <div class="col-12"><input id="patch-name"  class="form-control form-control-sm" placeholder="Tên (để trống = giữ nguyên)"></div>
            <div class="col-6"> <input id="patch-price" type="number" class="form-control form-control-sm" placeholder="Giá"></div>
            <div class="col-6"> <input id="patch-stock" type="number" class="form-control form-control-sm" placeholder="Tồn kho" min="0"></div>
          </div>
          <button class="btn btn-purple btn-sm w-100 mt-2" style="background:#6f42c1;color:#fff" onclick="apiPatch()">
            <i class="bi bi-patch-check me-1"></i>Cập nhật 1 phần
          </button>
        </div>
      </div>

      <!-- ── DELETE ── -->
      <div class="tab-pane d-none" id="tab-delete">
        <div class="card border-0 shadow-sm p-3">
          <h6 class="fw-bold mb-3"><span class="method-badge badge-delete">DELETE</span> /api/products/{id}</h6>
          <input id="del-id" type="number" class="form-control form-control-sm mb-2" placeholder="ID sản phẩm">
          <button class="btn btn-danger btn-sm w-100" onclick="apiDelete()">
            <i class="bi bi-trash me-1"></i>Xóa sản phẩm
          </button>
        </div>
      </div>

      <!-- JSON Response -->
      <div class="mt-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
          <small class="fw-semibold text-muted">JSON Response</small>
          <button class="btn btn-outline-secondary btn-sm py-0" onclick="$('#json-output').text('')">Clear</button>
        </div>
        <pre id="json-output" class="p-3 mb-0">// Response sẽ hiển thị ở đây...</pre>
      </div>
    </div>

    <!-- ═══ RIGHT: Danh sách sản phẩm realtime ════════════════════ -->
    <div class="col-lg-7">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="fw-bold mb-0">📦 Danh sách sản phẩm <span id="product-count" class="badge bg-danger ms-1"></span></h6>
        <div class="d-flex gap-2">
          <input id="search-live" class="form-control form-control-sm" style="width:160px" placeholder="🔍 Tìm nhanh…" oninput="filterCards()">
          <button class="btn btn-outline-danger btn-sm" onclick="loadProducts()">
            <i class="bi bi-arrow-clockwise"></i> Refresh
          </button>
        </div>
      </div>
      <div id="product-grid" class="row g-3">
        <div class="col-12 text-center py-5 text-muted">
          <i class="bi bi-arrow-clockwise fs-1 d-block mb-2"></i>Đang tải…
        </div>
      </div>
      <!-- Phân trang cards -->
      <div id="cards-pagination" class="d-flex justify-content-center mt-3 gap-1"></div>
    </div>
  </div>
</div>

<!-- Toast -->
<div class="toast-wrap" id="toast-wrap"></div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
const API = '<?= url('api') ?>';
let allProducts = [];
let currentPage = 1;
const PER_PAGE  = 6;

// ─── Tab switching ───────────────────────────────────────────────
$('#apiTabs button').on('click', function () {
  $('#apiTabs button').removeClass('active');
  $(this).addClass('active');
  $('.tab-pane').addClass('d-none');
  $('#' + $(this).data('tab')).removeClass('d-none');
});

// ─── Toast ───────────────────────────────────────────────────────
function toast(msg, type = 'success') {
  const id  = 'toast-' + Date.now();
  const cls = type === 'success' ? 'bg-success' : type === 'danger' ? 'bg-danger' : 'bg-warning text-dark';
  $('#toast-wrap').append(`
    <div id="${id}" class="toast align-items-center text-white ${cls} border-0 show mb-2" role="alert">
      <div class="d-flex">
        <div class="toast-body">${msg}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" onclick="$('#${id}').remove()"></button>
      </div>
    </div>`);
  setTimeout(() => $(`#${id}`).remove(), 3500);
}

// ─── Show JSON ───────────────────────────────────────────────────
function showJson(data) {
  $('#json-output').text(JSON.stringify(data, null, 2));
}

// ─── Load & render product cards ────────────────────────────────
function loadProducts() {
  $.getJSON(API + '/products?per_page=100', function (res) {
    allProducts = res.data || [];
    renderCards(allProducts, 1);
    $('#product-count').text(allProducts.length);
  }).fail(function () { toast('Không tải được danh sách!', 'danger'); });
}

function filterCards() {
  const q = $('#search-live').val().toLowerCase();
  const filtered = allProducts.filter(p => p.name.toLowerCase().includes(q));
  renderCards(filtered, 1);
}

function renderCards(products, page) {
  currentPage = page;
  const total  = products.length;
  const pages  = Math.ceil(total / PER_PAGE) || 1;
  const start  = (page - 1) * PER_PAGE;
  const slice  = products.slice(start, start + PER_PAGE);
  const grid   = $('#product-grid');
  grid.empty();

  if (!slice.length) {
    grid.html('<div class="col-12 text-center text-muted py-4">Không có sản phẩm nào.</div>');
  }

  slice.forEach(p => {
    const img   = p.image || 'https://placehold.co/300x160/f0f2f5/8b95a1?text=No+Image';
    const stock = p.stock ?? 0;
    const sc    = stock > 10 ? 'bg-success' : stock > 0 ? 'bg-warning text-dark' : 'bg-danger';
    grid.append(`
      <div class="col-md-6 col-lg-4 product-card-wrap" data-name="${p.name.toLowerCase()}">
        <div class="card product-card h-100 border-0 shadow-sm rounded-3 overflow-hidden">
          <img src="${img}" class="card-img-top" onerror="this.src='https://placehold.co/300x160/f0f2f5/8b95a1?text=No+Image'" alt="${p.name}">
          <div class="card-body p-3">
            <p class="fw-semibold small mb-1 text-truncate" title="${p.name}">${p.name}</p>
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-danger fw-bold small">${(p.price||0).toLocaleString('vi-VN')}đ</span>
              <span class="badge ${sc} stock-badge">Kho: ${stock}</span>
            </div>
            ${p.category_name ? `<span class="badge bg-light text-secondary border mt-1" style="font-size:10px">${p.category_name}</span>` : ''}
          </div>
          <div class="card-footer bg-white border-0 p-2 d-flex gap-1">
            <button class="btn btn-outline-primary btn-sm flex-fill py-0" onclick="fillGet(${p.id})">
              <i class="bi bi-eye"></i> Chi tiết
            </button>
            <button class="btn btn-outline-warning btn-sm flex-fill py-0" onclick="fillPut(${JSON.stringify(p).replace(/"/g,'&quot;')})">
              <i class="bi bi-pencil"></i> Sửa
            </button>
            <button class="btn btn-outline-danger btn-sm flex-fill py-0" onclick="fillDelete(${p.id})">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
      </div>`);
  });

  // Pagination
  const pag = $('#cards-pagination');
  pag.empty();
  if (pages > 1) {
    for (let i = 1; i <= pages; i++) {
      pag.append(`<button class="btn btn-sm ${i === page ? 'btn-danger' : 'btn-outline-secondary'}" onclick="renderCards(${JSON.stringify(products).replace(/"/g,"'")}, ${i})">${i}</button>`);
    }
  }
}

// ─── Convenience fill helpers ────────────────────────────────────
function fillGet(id) {
  $('#get-id').val(id);
  $('[data-tab="tab-get"]').trigger('click');
  apiGetDetail();
}
function fillPut(p) {
  $('#put-id').val(p.id); $('#put-name').val(p.name); $('#put-price').val(p.price);
  $('#put-stock').val(p.stock || 0); $('#put-cat').val(p.category_id || '');
  $('#put-image').val(p.image || ''); $('#put-desc').val(p.description || '');
  $('[data-tab="tab-put"]').trigger('click');
}
function fillDelete(id) {
  $('#del-id').val(id);
  $('[data-tab="tab-delete"]').trigger('click');
}

// ─── API Calls ───────────────────────────────────────────────────
function apiGetList() {
  const params = {
    q:          $('#list-q').val(),
    sort:       $('#list-sort').val(),
    page:       $('#list-page').val(),
    per_page:   $('#list-per-page').val(),
    category_id: $('#list-cat').val(),
  };
  Object.keys(params).forEach(k => { if (!params[k]) delete params[k]; });
  $.getJSON(API + '/products', params)
    .done(res => { showJson(res); toast(`✅ Lấy ${res.data?.length} sản phẩm (tổng: ${res.meta?.total})`); loadProducts(); })
    .fail((xhr) => { showJson(xhr.responseJSON || {}); toast('❌ Lỗi!', 'danger'); });
}

function apiGetDetail() {
  const id      = $('#get-id').val();
  const related = $('#get-related').is(':checked');
  if (!id) { toast('Nhập ID sản phẩm!', 'warning'); return; }
  const url = API + '/products/' + id + (related ? '/related' : '');
  $.getJSON(url)
    .done(res => { showJson(res); toast('✅ Lấy chi tiết thành công'); })
    .fail(xhr  => { showJson(xhr.responseJSON || {}); toast('❌ ' + (xhr.responseJSON?.message || 'Lỗi!'), 'danger'); });
}

function apiPost() {
  const name = $('#post-name').val().trim();
  const price = $('#post-price').val();
  if (!name || !price) { toast('Điền tên và giá!', 'warning'); return; }
  const body = {
    name, price: parseInt(price),
    description: $('#post-desc').val(),
    image:       $('#post-image').val(),
    stock:       parseInt($('#post-stock').val() || 0),
    category_id: parseInt($('#post-cat').val()) || null,
  };
  $.ajax({ url: API + '/products', method: 'POST', contentType: 'application/json',
           data: JSON.stringify(body) })
    .done(res => { showJson(res); toast('✅ Thêm sản phẩm thành công!'); loadProducts(); })
    .fail(xhr  => { showJson(xhr.responseJSON || {}); toast('❌ ' + (xhr.responseJSON?.message || 'Lỗi!'), 'danger'); });
}

function apiPut() {
  const id   = $('#put-id').val();
  const name = $('#put-name').val().trim();
  const price = $('#put-price').val();
  if (!id || !name || !price) { toast('Điền ID, tên và giá!', 'warning'); return; }
  const body = {
    name, price: parseInt(price),
    description: $('#put-desc').val(),
    image:       $('#put-image').val(),
    stock:       parseInt($('#put-stock').val() || 0),
    category_id: parseInt($('#put-cat').val()) || null,
  };
  $.ajax({ url: API + '/products/' + id, method: 'PUT', contentType: 'application/json',
           data: JSON.stringify(body) })
    .done(res => { showJson(res); toast('✅ Cập nhật thành công!'); loadProducts(); })
    .fail(xhr  => { showJson(xhr.responseJSON || {}); toast('❌ ' + (xhr.responseJSON?.message || 'Lỗi!'), 'danger'); });
}

function apiPatch() {
  const id = $('#patch-id').val();
  if (!id) { toast('Nhập ID!', 'warning'); return; }
  const body = {};
  const name  = $('#patch-name').val().trim();
  const price = $('#patch-price').val();
  const stock = $('#patch-stock').val();
  if (name)  body.name  = name;
  if (price) body.price = parseInt(price);
  if (stock !== '') body.stock = parseInt(stock);
  if (!Object.keys(body).length) { toast('Nhập ít nhất 1 trường!', 'warning'); return; }
  $.ajax({ url: API + '/products/' + id, method: 'PATCH', contentType: 'application/json',
           data: JSON.stringify(body) })
    .done(res => { showJson(res); toast('✅ Patch thành công!'); loadProducts(); })
    .fail(xhr  => { showJson(xhr.responseJSON || {}); toast('❌ ' + (xhr.responseJSON?.message || 'Lỗi!'), 'danger'); });
}

function apiDelete() {
  const id = $('#del-id').val();
  if (!id) { toast('Nhập ID!', 'warning'); return; }
  if (!confirm(`Xóa sản phẩm #${id}?`)) return;
  $.ajax({ url: API + '/products/' + id, method: 'DELETE' })
    .done(res => { showJson(res); toast('✅ Đã xóa sản phẩm #' + id); loadProducts(); })
    .fail(xhr  => { showJson(xhr.responseJSON || {}); toast('❌ ' + (xhr.responseJSON?.message || 'Lỗi!'), 'danger'); });
}

// ─── Init ────────────────────────────────────────────────────────
$(document).ready(function () {
  loadProducts();
  $('.tab-pane').addClass('d-none');
  $('#tab-list').removeClass('d-none');
});
</script>

<?php include __DIR__ . '/../layout/footer.php'; ?>
