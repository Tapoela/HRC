<header class="site-header">
  <div class="container">
    <a class="brand" href="/">
        <img class="club-logo" src="<?= base_url('assets/public/images/logo/logo.svg') ?>" alt="Heidelberg Rugby Club">
        <img class="protea-mark" src="<?= base_url('assets/public/images/protea.png') ?>" alt="Protea">
        <span>Heidelberg Rugby</span>
    </a>


    <button class="nav-toggle" aria-label="Menu" onclick="document.querySelector('.site-nav').classList.toggle('open')">&#9776;</button>

    <nav class="site-nav">
      <a href="/">Home</a>
      <a href="/about">About</a>
      <a href="/teams">Teams</a>
      <a href="/results">Results</a>
      <a href="/fixtures">Fixtures</a>
      <a href="/events">Events</a>
      <a href="/contact">Contact</a>
      <a href="/login" class="admin-link">Admin</a>
      <a href="/register">Register</a>
    </nav>
  </div>
</header>
