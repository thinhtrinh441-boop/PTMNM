</main>

<footer class="bg-dark text-white-50 pt-5 pb-3 mt-auto">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="text-white fw-bold"><i class="bi bi-lightning-charge text-danger"></i> Điện Tử Shop</h5>
                <p class="small">Hệ thống bán hàng điện tử — PHP, MySQL, Laragon.</p>
                <p class="small mb-0"><i class="bi bi-geo-alt me-1"></i> 123 Nguyễn Văn Cừ, Q.5, TP.HCM</p>
            </div>
            <div class="col-md-4">
                <h6 class="text-white fw-bold">Liên hệ</h6>
                <p class="small mb-1"><i class="bi bi-telephone me-2"></i> Hotline: <strong class="text-white">1900 1234</strong></p>
                <p class="small mb-1"><i class="bi bi-envelope me-2"></i> support@dientushop.vn</p>
                <p class="small"><i class="bi bi-clock me-2"></i> 8:00 – 21:00 (T2–CN)</p>
            </div>
            <div class="col-md-4">
                <h6 class="text-white fw-bold">Hỗ trợ</h6>
                <ul class="list-unstyled small">
                    <li><a href="<?= url('Page/policy') ?>" class="text-white-50 text-decoration-none">Bảo hành &amp; vận chuyển</a></li>
                    <li><a href="<?= url('Page/contact') ?>" class="text-white-50 text-decoration-none">Form liên hệ</a></li>
                    <li><a href="<?= url('Order/list') ?>" class="text-white-50 text-decoration-none">Tra cứu đơn hàng</a></li>
                    <li><a href="<?= url('Product/list') ?>" class="text-white-50 text-decoration-none">Sản phẩm</a></li>
                </ul>
            </div>
        </div>
        <hr class="border-secondary">
        <p class="text-center small mb-0">&copy; <?= date('Y') ?> Website Bán Hàng Điện Tử — MVC Bootstrap 5</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
    const html=document.documentElement;
    const btn=document.getElementById('darkModeBtn');
    const saved=localStorage.getItem('theme')||'light';
    html.setAttribute('data-theme',saved);
    btn&&btn.addEventListener('click',()=>{
        const t=html.getAttribute('data-theme')==='dark'?'light':'dark';
        html.setAttribute('data-theme',t);
        localStorage.setItem('theme',t);
        btn.innerHTML=t==='dark'?'<i class="bi bi-sun"></i>':'<i class="bi bi-moon-stars"></i>';
    });
    if(saved==='dark'&&btn)btn.innerHTML='<i class="bi bi-sun"></i>';
    document.querySelectorAll('.toast.show').forEach(el=>{
        setTimeout(()=>bootstrap.Toast.getOrCreateInstance(el).hide(),4000);
    });
})();
</script>
</body>
</html>
