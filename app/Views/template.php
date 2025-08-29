<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
  />
  <title><?= $title ?? 'LMS_PROJECT' ?></title>
  <style>
    .navbar {
      background: linear-gradient(90deg, #4e54c8, #8f94fb);
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">MySite</a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNavDropdown"
        aria-controls="navbarNavDropdown"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link <?= ($page ?? '')=='home'?'active':'' ?>" href="<?= base_url('/home') ?>">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= ($page ?? '')=='about'?'active':'' ?>" href="<?= base_url('/about') ?>">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= ($page ?? '')=='contact'?'active':'' ?>" href="<?= base_url('/contact') ?>">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <?= $this->renderSection('content') ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
