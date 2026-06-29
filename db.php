<?php
$databaseFile = __DIR__ . '/site_content.sqlite';

try {
    $pdo = new PDO('sqlite:' . $databaseFile);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

function bootstrapSiteDatabase(PDO $pdo): void
{
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS site_settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            setting_key TEXT UNIQUE NOT NULL,
            setting_value TEXT NOT NULL
        )"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS gallery_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            category TEXT NOT NULL,
            description TEXT,
            image_path TEXT NOT NULL,
            featured INTEGER DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )"
    );

    $defaults = [
        ['hero_title', 'Technology that inspires learning and growth'],
        ['hero_subtitle', 'We build modern AV, networking, solar and robotics experiences for schools, businesses and public institutions.'],
        ['hero_cta_text', 'Request a quotation'],
        ['hero_cta_link', 'https://wa.me/254741404232?text=Hello%20iWorth%2C%20I%20would%20like%20a%20quote.'],
        ['about_title', 'Trusted by educators and businesses across Kenya'],
        ['about_body', 'We combine certified expertise, local support and modern products to deliver installations that are dependable and future-ready.']
    ];

    $stmt = $pdo->prepare('INSERT OR IGNORE INTO site_settings (setting_key, setting_value) VALUES (?, ?)');
    foreach ($defaults as $default) {
        $stmt->execute($default);
    }

    $count = (int) $pdo->query('SELECT COUNT(*) FROM gallery_items')->fetchColumn();
    if ($count === 0) {
        $seedItems = [
            ['Interactive Display', 'Education', 'Interactive flat panels for schools and training centres.', 'interactivepanel.jpg', 1],
            ['Conference Room Setup', 'Corporate', 'Professional AV solutions for meetings and boardrooms.', 'conference.jpeg', 1],
            ['Networking Infrastructure', 'Networking', 'Structured cabling and secure networking for modern offices.', 'networking.webp', 1],
            ['Solar Installation', 'Solar', 'Reliable solar power installations for homes and institutions.', 'solar.webp', 1],
            ['Digital Signage', 'AV', 'Dynamic signage for retail and public communication.', 'digitalsignage.jpg', 0],
        ];

        $insert = $pdo->prepare('INSERT INTO gallery_items (title, category, description, image_path, featured) VALUES (?, ?, ?, ?, ?)');
        foreach ($seedItems as $item) {
            $insert->execute($item);
        }
    }
}

bootstrapSiteDatabase($pdo);

function getSetting(PDO $pdo, string $key, string $default = ''): string
{
    $stmt = $pdo->prepare('SELECT setting_value FROM site_settings WHERE setting_key = ?');
    $stmt->execute([$key]);
    $row = $stmt->fetch();
    return $row ? (string) $row['setting_value'] : $default;
}

function setSetting(PDO $pdo, string $key, string $value): void
{
    $stmt = $pdo->prepare('INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON CONFLICT(setting_key) DO UPDATE SET setting_value = excluded.setting_value');
    $stmt->execute([$key, $value]);
}

function listGalleryItems(PDO $pdo, string $category = 'all', int $limit = 12): array
{
    if ($category === 'all') {
        $stmt = $pdo->prepare('SELECT * FROM gallery_items ORDER BY featured DESC, created_at DESC LIMIT ?');
        $stmt->execute([$limit]);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM gallery_items WHERE LOWER(category) = LOWER(?) ORDER BY featured DESC, created_at DESC LIMIT ?');
        $stmt->execute([$category, $limit]);
    }

    return $stmt->fetchAll();
}

function addGalleryItem(PDO $pdo, array $data): int
{
    $stmt = $pdo->prepare('INSERT INTO gallery_items (title, category, description, image_path, featured) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([
        $data['title'],
        $data['category'],
        $data['description'],
        $data['image_path'],
        isset($data['featured']) ? 1 : 0,
    ]);

    return (int) $pdo->lastInsertId();
}

function deleteGalleryItem(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare('SELECT image_path FROM gallery_items WHERE id = ?');
    $stmt->execute([$id]);
    $item = $stmt->fetch();

    if ($item) {
        $filePath = __DIR__ . '/' . ltrim($item['image_path'], '/');
        if (file_exists($filePath) && !str_contains($item['image_path'], 'http')) {
            @unlink($filePath);
        }
    }

    $stmt = $pdo->prepare('DELETE FROM gallery_items WHERE id = ?');
    $stmt->execute([$id]);
}
