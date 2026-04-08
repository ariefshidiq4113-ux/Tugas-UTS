// public/js/admin.js

document.addEventListener('DOMContentLoaded', function () {

    // Sidebar toggle
    const sidebar    = document.getElementById('sidebar');
    const toggleBtn  = document.getElementById('sidebarToggle');
    const closeBtn   = document.getElementById('sidebarClose');

    // Create overlay
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);

    function openSidebar()  { sidebar.classList.add('open'); overlay.style.display = 'block'; }
    function closeSidebar() { sidebar.classList.remove('open'); overlay.style.display = 'none'; }

    if (toggleBtn) toggleBtn.addEventListener('click', function () {
        if (window.innerWidth < 992) {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        } else {
            const main = document.getElementById('adminMain');
            sidebar.classList.toggle('collapsed');
            main.classList.toggle('sidebar-collapsed');
        }
    });
    if (closeBtn)  closeBtn.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);

    // Auto-dismiss alerts
    document.querySelectorAll('.alert.alert-dismissible').forEach(function (el) {
        setTimeout(function () {
            const a = bootstrap.Alert.getOrCreateInstance(el);
            if (a) a.close();
        }, 4000);
    });

    // Confirm delete links
    document.querySelectorAll('a[data-confirm]').forEach(function (el) {
        el.addEventListener('click', function (e) {
            if (!confirm(el.dataset.confirm)) e.preventDefault();
        });
    });

    // Init TinyMCE if present
    if (typeof tinymce !== 'undefined' && document.getElementById('articleContent')) {
        tinymce.init({
            selector: '#articleContent',
            height: 480,
            menubar: false,
            plugins: 'autolink lists link image charmap preview searchreplace fullscreen media table code wordcount',
            toolbar: 'undo redo | formatselect | bold italic underline strikethrough | forecolor | alignleft aligncenter alignright | bullist numlist | link image media | removeformat | code fullscreen',
            content_style: 'body { font-family: Source Sans 3, sans-serif; font-size: 16px; line-height: 1.7; max-width: 100%; } p { margin-bottom: 1rem; }',
            branding: false,
            statusbar: true,
            resize: true,
        });
    }

    // Slug auto-generate from title (for categories form)
    const nameInput = document.querySelector('[name="name"]');
    const slugInput = document.querySelector('[name="slug"]');
    if (nameInput && slugInput && !slugInput.value) {
        nameInput.addEventListener('input', function () {
            slugInput.value = nameInput.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
        });
    }

    // Char counter for excerpt
    const excerpt = document.querySelector('textarea[name="excerpt"]');
    if (excerpt) {
        const counter = document.createElement('small');
        counter.className = 'text-muted';
        excerpt.parentNode.appendChild(counter);
        function updateCounter() {
            counter.textContent = excerpt.value.length + ' karakter';
        }
        excerpt.addEventListener('input', updateCounter);
        updateCounter();
    }

    // Table row click to edit (if data-href is set)
    document.querySelectorAll('tr[data-href]').forEach(function (row) {
        row.style.cursor = 'pointer';
        row.addEventListener('click', function () {
            window.location.href = row.dataset.href;
        });
    });
});
