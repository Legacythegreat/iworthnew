<?php require_once __DIR__ . '/db.php';
$heroTitle = getSetting($pdo, 'hero_title', 'Technology that inspires learning and growth');
$heroSubtitle = getSetting($pdo, 'hero_subtitle', 'We build modern AV, networking, solar and robotics experiences for schools, businesses and public institutions.');
$heroCtaText = getSetting($pdo, 'hero_cta_text', 'Request a quotation');
$heroCtaLink = getSetting($pdo, 'hero_cta_link', 'https://wa.me/254741404232?text=Hello%20iWorth%2C%20I%20would%20like%20a%20quote.');
$aboutTitle = getSetting($pdo, 'about_title', 'Trusted by educators and businesses across Kenya');
$aboutBody = getSetting($pdo, 'about_body', 'We combine certified expertise, local support and modern products to deliver installations that are dependable and future-ready.');
$featuredItems = listGalleryItems($pdo, 'all', 6);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>iWorth Technologies | Modern AV and IT Solutions</title>
  <meta name="description" content="Modern AV, IT, networking, solar and robotics solutions for schools and businesses in Kenya.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="modern.css">
</head>
<body>
  <div class="top-bar">
    <div class="container">
      <div class="top-contact">
        <a href="tel:+254741404232"><i class="fas fa-phone-alt"></i> +254 741 404232</a>
        <a href="mailto:info@iworth.co.ke"><i class="fas fa-envelope"></i> info@iworth.co.ke</a>
      </div>
      <div class="top-social">
        <a href="https://www.facebook.com/iworthtechnologies/"><i class="fab fa-facebook-f"></i></a>
        <a href="https://ke.linkedin.com/company/iworth-technologies"><i class="fab fa-linkedin-in"></i></a>
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
    <section class="hero">
      <div class="container hero-grid">
        <div>
          <h1><?= htmlspecialchars($heroTitle) ?></h1>
          <p><?= htmlspecialchars($heroSubtitle) ?></p>
          <div class="hero-actions">
            <a class="btn btn-primary" href="<?= htmlspecialchars($heroCtaLink) ?>" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> <?= htmlspecialchars($heroCtaText) ?></a>
            <a class="btn btn-secondary" href="gallery.php">Explore our projects</a>
          </div>
          <div class="stats">
            <div class="stat"><strong>100+</strong><span>Projects delivered</span></div>
            <div class="stat"><strong>24/7</strong><span>Technical support</span></div>
            <div class="stat"><strong>Kenya</strong><span>Local service reach</span></div>
          </div>
        </div>
        <div class="hero-card">
          <img src="interactivepanel.jpg" alt="Interactive display setup">
        </div>
      </div>
    </section>

    <section class="section">
      <div class="container">
        <div class="section-heading">
          <div>
            <h2>What we do best</h2>
            <p>Modern solutions for classrooms, boardrooms, workspaces and community projects.</p>
          </div>
          <a class="btn btn-secondary" href="products.html">View all products</a>
        </div>
        <div class="card-grid">
          <article class="card">
            <i class="fas fa-tv"></i>
            <h3>Interactive Displays</h3>
            <p>Touch-enabled interactive panels for learning, collaboration and conferences.</p>
          </article>
          <article class="card">
            <i class="fas fa-network-wired"></i>
            <h3>Networking & Cabling</h3>
            <p>Structured networks, switches and connectivity that keep organisations running smoothly.</p>
          </article>
          <article class="card">
            <i class="fas fa-solar-panel"></i>
            <h3>Solar & Energy</h3>
            <p>Reliable solar installations for homes, schools and off-grid locations.</p>
          </article>
        </div>
      </div>
    </section>

    <section class="section" style="padding-top: 0;">
      <div class="container">
        <div class="section-heading">
          <div>
            <h2><?= htmlspecialchars($aboutTitle) ?></h2>
            <p><?= htmlspecialchars($aboutBody) ?></p>
          </div>
          <a class="btn btn-secondary" href="about.html">Meet the team</a>
        </div>
        <div class="projects-grid">
          <?php foreach ($featuredItems as $item): ?>
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

    <section class="cta">
      <div class="container">
        <div class="cta-box">
          <div>
            <h2>Ready to upgrade your space?</h2>
            <p>Let us design a solution that fits your learning environment, office, or project site.</p>
          </div>
          <a class="btn btn-primary" href="https://wa.me/254741404232" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> Start a conversation</a>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="container">
      <div>
        <strong>iWorth Technologies</strong>
        <p>Modern AV, IT and solar solutions for Kenya.</p>
      </div>
      <div>
        <a href="gallery.php">Gallery</a> ·
        <a href="products.html">Products</a> ·
        <a href="admin.php">Admin</a>
      </div>
    </div>
  </footer>
</body>
</html>
