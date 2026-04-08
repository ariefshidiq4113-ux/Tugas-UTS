-- ============================================================
-- BeritaKu - News Website Database Schema
-- ============================================================

CREATE DATABASE IF NOT EXISTS beritaku CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE beritaku;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT 'default.png',
    role ENUM('admin','editor','user') DEFAULT 'user',
    bio TEXT,
    is_active TINYINT(1) DEFAULT 1,
    email_verified_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Categories Table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) UNIQUE NOT NULL,
    description TEXT,
    color VARCHAR(10) DEFAULT '#0d6efd',
    icon VARCHAR(50) DEFAULT 'bi-newspaper',
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tags Table
CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Articles Table
CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(280) UNIQUE NOT NULL,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    thumbnail VARCHAR(255),
    status ENUM('draft','published','archived') DEFAULT 'draft',
    is_featured TINYINT(1) DEFAULT 0,
    is_breaking TINYINT(1) DEFAULT 0,
    views INT DEFAULT 0,
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Article Tags (Pivot)
CREATE TABLE article_tags (
    article_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (article_id, tag_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

-- Comments Table
CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    article_id INT NOT NULL,
    user_id INT NULL,
    parent_id INT NULL,
    author_name VARCHAR(100),
    author_email VARCHAR(150),
    content TEXT NOT NULL,
    status ENUM('pending','approved','spam') DEFAULT 'pending',
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE SET NULL
);

-- Bookmarks Table
CREATE TABLE bookmarks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    article_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_bookmark (user_id, article_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
);

-- Likes Table
CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    article_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_like (user_id, article_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
);

-- Site Settings Table
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) UNIQUE NOT NULL,
    `value` TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- SEED DATA
-- ============================================================

-- Default Admin User (password: admin123)
INSERT INTO users (name, username, email, password, role, is_active, email_verified_at) VALUES
('Administrator', 'admin', 'admin@beritaku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, NOW()),
('Editor Redaksi', 'editor', 'editor@beritaku.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'editor', 1, NOW()),
('Budi Santoso', 'budisantoso', 'budi@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 1, NOW());

-- Default Categories
INSERT INTO categories (name, slug, description, color, icon, sort_order) VALUES
('Nasional', 'nasional', 'Berita nasional Indonesia', '#dc3545', 'bi-flag', 1),
('Internasional', 'internasional', 'Berita dari seluruh dunia', '#0d6efd', 'bi-globe', 2),
('Ekonomi', 'ekonomi', 'Berita ekonomi dan bisnis', '#198754', 'bi-graph-up', 3),
('Teknologi', 'teknologi', 'Berita teknologi terkini', '#6f42c1', 'bi-cpu', 4),
('Olahraga', 'olahraga', 'Berita olahraga terkini', '#fd7e14', 'bi-trophy', 5),
('Hiburan', 'hiburan', 'Berita hiburan dan selebriti', '#e83e8c', 'bi-star', 6),
('Kesehatan', 'kesehatan', 'Berita kesehatan dan gaya hidup', '#20c997', 'bi-heart-pulse', 7),
('Otomotif', 'otomotif', 'Berita otomotif terkini', '#6c757d', 'bi-car-front', 8);

-- Default Tags
INSERT INTO tags (name, slug) VALUES
('Politik', 'politik'), ('Hukum', 'hukum'), ('Sosial', 'sosial'),
('Lingkungan', 'lingkungan'), ('Pendidikan', 'pendidikan'), ('Bisnis', 'bisnis'),
('Startup', 'startup'), ('AI', 'ai'), ('Sepak Bola', 'sepak-bola'),
('Basket', 'basket'), ('Film', 'film'), ('Musik', 'musik');

-- Default Settings
INSERT INTO settings (`key`, `value`) VALUES
('site_name', 'BeritaKu'),
('site_tagline', 'Berita Terpercaya, Informasi Terkini'),
('site_description', 'Portal berita online terpercaya di Indonesia'),
('site_email', 'redaksi@beritaku.com'),
('site_phone', '+62 21 1234 5678'),
('site_address', 'Jakarta, Indonesia'),
('articles_per_page', '12'),
('comment_moderation', '1'),
('allow_registration', '1'),
('breaking_news', 'Selamat datang di BeritaKu - Portal Berita Terpercaya Indonesia');

-- Sample Articles
INSERT INTO articles (user_id, category_id, title, slug, excerpt, content, status, is_featured, is_breaking, views, published_at) VALUES
(1, 1, 'Pemerintah Luncurkan Program Ekonomi Baru untuk Masyarakat', 'pemerintah-luncurkan-program-ekonomi-baru', 
 'Pemerintah Indonesia resmi meluncurkan program ekonomi baru yang bertujuan meningkatkan kesejahteraan masyarakat di seluruh wilayah Indonesia.',
 '<p>Pemerintah Indonesia resmi meluncurkan program ekonomi baru yang bertujuan meningkatkan kesejahteraan masyarakat di seluruh wilayah Indonesia. Program ini mencakup berbagai sektor mulai dari UMKM, pertanian, hingga teknologi digital.</p><p>Dalam konferensi pers yang digelar di Jakarta, Menteri Koordinator Bidang Perekonomian menyampaikan bahwa program ini merupakan langkah konkret pemerintah dalam menghadapi tantangan ekonomi global.</p><p>Program ini akan dijalankan secara bertahap dengan total anggaran mencapai Rp 500 triliun yang akan dialokasikan selama tiga tahun ke depan.</p>',
 'published', 1, 1, 1250, NOW()),
(2, 4, 'Inovasi Teknologi AI Terbaru Mengubah Cara Kerja Industri', 'inovasi-teknologi-ai-terbaru', 
 'Perkembangan kecerdasan buatan (AI) terus mengalami lonjakan pesat dan mulai mengubah berbagai sektor industri di Indonesia.',
 '<p>Perkembangan kecerdasan buatan (AI) terus mengalami lonjakan pesat dan mulai mengubah berbagai sektor industri di Indonesia. Dari manufaktur hingga layanan kesehatan, AI kini menjadi andalan perusahaan-perusahaan besar.</p><p>Para ahli teknologi memprediksi bahwa dalam lima tahun ke depan, setidaknya 40% pekerjaan akan terpengaruh oleh otomatisasi berbasis AI. Namun, di sisi lain, teknologi ini juga membuka lapangan kerja baru yang lebih kreatif dan inovatif.</p>',
 'published', 1, 0, 890, NOW()),
(1, 5, 'Tim Nasional Indonesia Lolos ke Babak Final Piala Asia', 'timnas-indonesia-lolos-final-piala-asia',
 'Timnas Indonesia berhasil menorehkan sejarah baru dengan lolos ke babak final Piala Asia setelah mengalahkan lawan tangguh.',
 '<p>Timnas Indonesia berhasil menorehkan sejarah baru dengan lolos ke babak final Piala Asia setelah mengalahkan lawan tangguh. Kemenangan ini disambut antusias oleh jutaan pendukung di seluruh Indonesia.</p><p>Pelatih kepala tim menyebut bahwa kerja keras dan dedikasi para pemain menjadi kunci keberhasilan ini. "Kami bangga dengan pencapaian luar biasa ini," ujar sang pelatih.</p>',
 'published', 0, 1, 2100, NOW()),
(2, 6, 'Festival Film Indonesia 2025 Resmi Dibuka dengan Meriah', 'festival-film-indonesia-2025',
 'Festival Film Indonesia 2025 resmi dibuka dengan seremoni kemewahan yang dihadiri ribuan insan perfilman tanah air.',
 '<p>Festival Film Indonesia 2025 resmi dibuka dengan seremoni kemewahan yang dihadiri ribuan insan perfilman tanah air. Tahun ini festival mengangkat tema "Layar Nusantara, Cerita Kita".</p><p>Sebanyak 150 film dari berbagai kategori akan diputar selama festival berlangsung, mulai dari film pendek, dokumenter, hingga film panjang. Beberapa film karya sineas muda juga mendapat tempat istimewa dalam program khusus.</p>',
 'published', 0, 0, 567, NOW()),
(1, 7, 'Penelitian Baru Ungkap Manfaat Luar Biasa Kunyit untuk Kesehatan', 'manfaat-kunyit-untuk-kesehatan',
 'Penelitian terbaru dari Universitas Indonesia mengungkap berbagai manfaat luar biasa dari konsumsi kunyit secara rutin.',
 '<p>Penelitian terbaru dari Universitas Indonesia mengungkap berbagai manfaat luar biasa dari konsumsi kunyit secara rutin. Rempah khas Indonesia ini ternyata memiliki kandungan antioksidan yang jauh lebih tinggi dari yang diperkirakan sebelumnya.</p><p>Studi yang melibatkan 500 responden ini menunjukkan bahwa konsumsi kunyit secara teratur dapat menurunkan risiko penyakit jantung, diabetes, dan bahkan beberapa jenis kanker.</p>',
 'published', 0, 0, 445, NOW()),
(2, 3, 'IHSG Melesat ke Level Tertinggi Sepanjang Sejarah', 'ihsg-level-tertinggi-sepanjang-sejarah',
 'Indeks Harga Saham Gabungan (IHSG) berhasil menyentuh level tertinggi sepanjang sejarah di tengah optimisme investor.',
 '<p>Indeks Harga Saham Gabungan (IHSG) berhasil menyentuh level tertinggi sepanjang sejarah di tengah optimisme investor terhadap kondisi ekonomi Indonesia. Para analis menilai pencapaian ini sebagai sinyal positif bagi pertumbuhan ekonomi nasional.</p><p>Sektor keuangan, konsumsi, dan teknologi menjadi motor penggerak utama yang mendorong penguatan IHSG. Kapitalisasi pasar bursa saham Indonesia pun mencapai rekor baru.</p>',
 'published', 1, 0, 732, NOW());
