// public/js/main.js

document.addEventListener('DOMContentLoaded', function () {

    // Auto-dismiss alerts after 5s
    document.querySelectorAll('.alert.alert-dismissible').forEach(function (alert) {
        setTimeout(function () {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });

    // Lazy load images
    if ('IntersectionObserver' in window) {
        const lazyImages = document.querySelectorAll('img[data-src]');
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });
        lazyImages.forEach(function (img) { observer.observe(img); });
    }

    // Ticker animation duplicate for seamless loop
    const tickerItems = document.querySelector('.ticker-items');
    if (tickerItems) {
        const clone = tickerItems.cloneNode(true);
        tickerItems.parentNode.appendChild(clone);
    }

    // Reading progress bar (on article pages)
    const article = document.querySelector('.article-content');
    if (article) {
        const bar = document.createElement('div');
        bar.id = 'readProgress';
        bar.style.cssText = 'position:fixed;top:0;left:0;height:3px;background:var(--brand-red,#e8192c);z-index:9999;transition:width .1s;width:0%';
        document.body.appendChild(bar);
        window.addEventListener('scroll', function () {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            bar.style.width = (scrollTop / docHeight * 100) + '%';
        });
    }

    // Back to top
    const backTopBtn = document.createElement('button');
    backTopBtn.innerHTML = '<i class="bi bi-chevron-up"></i>';
    backTopBtn.className = 'btn btn-dark btn-sm rounded-circle position-fixed d-none';
    backTopBtn.style.cssText = 'bottom:24px;right:24px;width:40px;height:40px;z-index:9998;opacity:.7';
    backTopBtn.title = 'Kembali ke atas';
    document.body.appendChild(backTopBtn);
    backTopBtn.addEventListener('click', function () { window.scrollTo({ top: 0, behavior: 'smooth' }); });
    window.addEventListener('scroll', function () {
        backTopBtn.classList.toggle('d-none', window.scrollY < 400);
    });
});
