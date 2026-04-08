# BeritaKu — Portal Berita Online

Portal berita Indonesia berbasis PHP MVC dengan Bootstrap 5, lengkap dengan panel admin dan antarmuka pengguna.

---

## 🗂️ Struktur Proyek (MVC)

```
beritaku/
├── config/
│   └── database.php          # Konfigurasi database & konstanta
├── core/
│   ├── Database.php           # PDO singleton wrapper
│   ├── Model.php              # Base model class
│   ├── Controller.php         # Base controller class
│   └── Router.php             # URL router
├── app/
│   ├── controllers/
│   │   ├── HomeController.php
│   │   ├── ArticleController.php
│   │   ├── AuthController.php
│   │   └── admin/
│   │       └── AdminController.php
│   ├── models/
│   │   ├── Article.php
│   │   └── Models.php         # Category, User, Comment, Tag, Setting
│   └── views/
│       ├── layouts/
│       │   ├── public.php     # Layout halaman publik
│       │   └── admin.php      # Layout panel admin
│       ├── home/
│       ├── articles/
│       ├── auth/
│       ├── admin/
│       │   ├── articles/
│       │   ├── categories/
│       │   ├── users/
│       │   ├── comments/
│       │   └── settings.php
│       └── errors/
├── public/
│   ├── index.php              # Entry point + semua routes
│   ├── .htaccess
│   ├── css/
│   │   ├── style.css          # CSS publik
│   │   └── admin.css          # CSS admin
│   ├── js/
│   │   ├── main.js            # JS publik
│   │   └── admin.js           # JS admin
│   └── uploads/               # Upload gambar (buat folder ini)
└── database/
    └── schema.sql             # Schema + seed data
```

---

## 🚀 Instalasi

### Persyaratan
- PHP 8.0+
- MySQL 8.0+ / MariaDB 10.4+
- Apache (mod_rewrite aktif) / Nginx
- Composer (opsional)

### Langkah Instalasi

**1. Clone / copy proyek**
```bash
cp -r beritaku/ /var/www/html/beritaku
# atau taruh di htdocs/beritaku
```

**2. Buat database**
```bash
mysql -u root -p < database/schema.sql
```
atau buka phpMyAdmin → Import → pilih `database/schema.sql`

**3. Konfigurasi database**

Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'beritaku');
define('DB_USER', 'root');
define('DB_PASS', '');  // sesuaikan password Anda
define('BASE_URL', 'http://localhost/beritaku/public');
```

**4. Buat folder uploads**
```bash
mkdir -p public/uploads/articles
mkdir -p public/uploads/avatars
chmod -R 755 public/uploads
```

**5. Aktifkan mod_rewrite (Apache)**
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

**6. Akses website**
- **Frontend:** http://localhost/beritaku/public
- **Admin:** http://localhost/beritaku/public/admin/dashboard

---

## 🔐 Akun Default

| Role  | Email                  | Password |
|-------|------------------------|----------|
| Admin | admin@beritaku.com     | password |
| Editor| editor@beritaku.com    | password |
| User  | budi@gmail.com         | password |

> ⚠️ **Segera ganti password** setelah instalasi!

---

## ✨ Fitur

### Frontend (Pengguna)
- ✅ Halaman beranda mirip detikcom dengan breaking news ticker
- ✅ Hero section artikel unggulan
- ✅ Grid berita per kategori
- ✅ Artikel populer (sidebar)
- ✅ Halaman detail artikel dengan konten HTML
- ✅ Sistem komentar (publik & login)
- ✅ Halaman kategori dengan pagination
- ✅ Pencarian artikel
- ✅ Share ke WhatsApp, Twitter, Facebook
- ✅ Reading progress bar
- ✅ Registrasi & login user
- ✅ Halaman profil pengguna

### Backend (Admin/Editor)
- ✅ Dashboard statistik (artikel, user, views, komentar)
- ✅ CRUD artikel lengkap + TinyMCE editor
- ✅ Upload thumbnail artikel
- ✅ Tandai artikel sebagai Featured / Breaking News
- ✅ Manajemen kategori (dengan warna & icon)
- ✅ Manajemen pengguna (role: admin/editor/user)
- ✅ Moderasi komentar (approve/hapus)
- ✅ Pengaturan situs (nama, tagline, breaking news, dll)
- ✅ Sidebar kolapsibel dengan badge pending comments

---

## 🏗️ Arsitektur MVC

```
Request → public/index.php (Router)
        → Controller (business logic)
            → Model (data / database)
        → View (presentasi HTML)
```

- **Model**: extends `Model` base class, interaksi PDO via `Database` singleton
- **Controller**: extends `Controller` base, method `view()`, `redirect()`, `json()`
- **View**: PHP template murni, layout dipisah (public/admin)
- **Router**: regex-based URL matching, mendukung parameter `{id}`, `{slug}`

---

## 🔧 Kustomisasi

### Tambah Route Baru
```php
// Di public/index.php
$router->get('/halaman-baru', ['NamaController', 'methodName']);
$router->post('/simpan-data', ['NamaController', 'storeMethod']);
```

### Tambah Model Baru
```php
class NamaModel extends Model {
    protected string $table = 'nama_tabel';
    // tambah method khusus di sini
}
```

### Tambah Controller Baru
```php
class NamaController extends Controller {
    public function index(): void {
        $data = (new NamaModel())->findAll();
        $this->view('folder.nama_view', compact('data'), 'public');
    }
}
```
