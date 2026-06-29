<?php require_once __DIR__ . '/db.php';
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_settings'])) {
        setSetting($pdo, 'hero_title', trim($_POST['hero_title'] ?? ''));
        setSetting($pdo, 'hero_subtitle', trim($_POST['hero_subtitle'] ?? ''));
        setSetting($pdo, 'hero_cta_text', trim($_POST['hero_cta_text'] ?? ''));
        setSetting($pdo, 'hero_cta_link', trim($_POST['hero_cta_link'] ?? ''));
        setSetting($pdo, 'about_title', trim($_POST['about_title'] ?? ''));
        setSetting($pdo, 'about_body', trim($_POST['about_body'] ?? ''));
        $success = 'Homepage content updated.';
    }

    if (isset($_POST['upload_image'])) {
        if (empty($_POST['title']) || empty($_POST['category']) || empty($_FILES['image']['name'])) {
            $error = 'Please fill in the title, category and select an image.';
        } else {
            $uploadDir = __DIR__ . '/uploads';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $safeName = uniqid('img_', true) . '.' . $ext;
            $target = $uploadDir . '/' . $safeName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
                addGalleryItem($pdo, [
                    'title' => trim($_POST['title']),
                    'category' => trim($_POST['category']),
                    'description' => trim($_POST['description'] ?? ''),
                    'image_path' => 'uploads/' . $safeName,
                    'featured' => !empty($_POST['featured']),
                ]);
                $success = 'Image uploaded and saved to the database.';
            } else {
                $error = 'Image upload failed. Please try again.';
            }
        }
    }

    if (isset($_POST['delete_id'])) {
        deleteGalleryItem($pdo, (int) $_POST['delete_id']);
        $success = 'Item removed.';
    }
}

$items = $pdo->query('SELECT * FROM gallery_items ORDER BY featured DESC, created_at DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin | iWorth Technologies</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="modern.css">
</head>
<body>
  <header>
    <div class="container">
      <nav class="navbar">
        <a class="logo" href="index.php"><img src="pagelogo.png" alt="iWorth Technologies"></a>
        <div class="nav-links">
          <a href="index.php">Home</a>
          <a href="gallery.php">Gallery</a>
          <a href="admin.php" class="btn btn-secondary">Refresh</a>
        </div>
      </nav>
    </div>
  </header>

  <main class="container">
    <?php if ($success): ?><p style="margin: 20px 0 0; color: var(--success); font-weight: 700;"><?= htmlspecialchars($success) ?></p><?php endif; ?>
    <?php if ($error): ?><p style="margin: 20px 0 0; color: #dc2626; font-weight: 700;"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <div class="admin-shell">
      <section class="panel">
        <h2>Update homepage content</h2>
        <form method="post">
          <input type="hidden" name="save_settings" value="1">
          <label>Hero title</label>
          <input type="text" name="hero_title" value="<?= htmlspecialchars(getSetting($pdo, 'hero_title', 'Technology that inspires learning and growth')) ?>">
          <label>Hero subtitle</label>
          <textarea name="hero_subtitle"><?= htmlspecialchars(getSetting($pdo, 'hero_subtitle', 'We build modern AV, networking, solar and robotics experiences for schools, businesses and public institutions.')) ?></textarea>
          <label>CTA text</label>
          <input type="text" name="hero_cta_text" value="<?= htmlspecialchars(getSetting($pdo, 'hero_cta_text', 'Request a quotation')) ?>">
          <label>CTA link</label>
          <input type="text" name="hero_cta_link" value="<?= htmlspecialchars(getSetting($pdo, 'hero_cta_link', 'https://wa.me/254741404232?text=Hello%20iWorth%2C%20I%20would%20like%20a%20quote.')) ?>">
          <label>About title</label>
          <input type="text" name="about_title" value="<?= htmlspecialchars(getSetting($pdo, 'about_title', 'Trusted by educators and businesses across Kenya')) ?>">
          <label>About body</label>
          <textarea name="about_body"><?= htmlspecialchars(getSetting($pdo, 'about_body', 'We combine certified expertise, local support and modern products to deliver installations that are dependable and future-ready.')) ?></textarea>
          <button type="submit">Save homepage content</button>
        </form>
      </section>

      <section class="panel">
        <h2>Upload new project image</h2>
        <form method="post" enctype="multipart/form-data">
          <input type="hidden" name="upload_image" value="1">
          <label>Project title</label>
          <input type="text" name="title" placeholder="e.g. Classroom AV upgrade">
          <label>Category</label>
          <select name="category">
            <option value="Education">Education</option>
            <option value="Corporate">Corporate</option>
            <option value="Networking">Networking</option>
            <option value="Solar">Solar</option>
            <option value="AV">AV</option>
          </select>
          <label>Description</label>
          <textarea name="description" placeholder="Brief summary of the installation"></textarea>
          <label>Featured on homepage</label>
          <input type="checkbox" name="featured" value="1" style="width:auto; margin-bottom: 14px;">
          <label>Image file</label>
          <input type="file" name="image" accept="image/*">
          <button type="submit">Upload image</button>
        </form>
      </section>
    </div>

    <section class="panel">
      <h2>Current gallery items</h2>
      <table class="table">
        <thead>
          <tr>
            <th>Preview</th>
            <th>Title</th>
            <th>Category</th>
            <th>Description</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $item): ?>
            <tr>
              <td><img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>"></td>
              <td><?= htmlspecialchars($item['title']) ?></td>
              <td><?= htmlspecialchars($item['category']) ?></td>
              <td><?= htmlspecialchars($item['description']) ?></td>
              <td>
                <form method="post" style="margin:0;">
                  <input type="hidden" name="delete_id" value="<?= (int) $item['id'] ?>">
                  <button type="submit">Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </section>
  </main>
</body>
</html>
