<?php require_once __DIR__ . '/db.php';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$items = listGalleryItems($pdo, $category, 24);
$categories = ['all', 'education', 'corporate', 'networking', 'solar', 'av'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gallery | iWorth Technologies</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="modern.css">
</head>
<body>
  <div class="top-bar">
    <div class="container">
      <div class="top-contact">
        <a href="tel:+254741404232"><i class="fas fa-phone-alt"></i> +254 741 404232</a>
        <a href="mailto:info@iworth.co.ke"><i class="fas fa-envelope"></i> info@iworth.co.ke</a>
      </div>
    </div>
  </div>

  <header>
    <div class="container">
      <nav class="navbar">
        <a class="logo" href="index.php"><img src="pagelogo.png" alt="iWorth Technologies"></a>
        <div class="nav-links">
          <a href="index.php">Home</a>
          <a href="about.html">About</a>
          <a href="gallery.php">Gallery</a>
          <a href="products.html">Products</a>
          <a href="admin.php" class="btn btn-secondary">Manage Content</a>
        </div>
      </nav>
    </div>
  </header>

  <main>
    <section class="section">
      <div class="container">
        <div class="section-heading">
          <div>
            <h2>Project gallery</h2>
            <p>Browse the installations and technology spaces we have created for clients across Kenya.</p>
          </div>
        </div>
        <div class="section-heading" style="margin-bottom: 18px;">
          <?php foreach ($categories as $value): ?>
            <a class="btn <?= $category === $value ? 'btn-primary' : 'btn-secondary' ?>" href="gallery.php?category=<?= urlencode($value) ?>"><?= ucfirst($value) ?></a>
          <?php endforeach; ?>
        </div>
        <div class="projects-grid">
          <?php foreach ($items as $item): ?>
            <article class="project-card">
              <img src="<?= htmlspecialchars($item['image_path']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
              <div class="body">
                <span class="tag"><?= htmlspecialchars($item['category']) ?></span>
                <h3><?= htmlspecialchars($item['title']) ?></h3>
                <p><?= htmlspecialchars($item['description']) ?></p>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
