<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="author" content="Tammy Ru Xiu TAY" />
  <title>Non-Coffee - Brew & Go</title>
  <link rel="stylesheet" href="styles/style.css" />
</head>

<body>

  <div id="top"></div>

  <!-- Navigation Bar -->
<header>
    <?php include 'navbar.php'; ?>
</header>

<header class="product-banner">
  <div class="banner-overlay">
    <div class="banner-text">
      <h1>Non-Coffee</h1>
      <a href="menu.php" class="back-button">Back</a>
    </div>
  </div>
</header>

<section class="product-grid">
  <figure class="product-card">
    <img src="images/coffee/Iced Houjicha.jpeg" alt="Houjicha" />
    <figcaption>
      <h3>Houjicha</h3>
      <p>Roasted green tea with a deep, nutty aroma and creamy finish.</p>
      <div class="price-tag">MP: RM13.90 | NP: RM15.90</div>
    </figcaption>
  </figure>

  <figure class="product-card">
    <img src="images/coffee/Orange Chocolate.jpeg" alt="Orange Chocolate" />
    <figcaption>
      <h3>Orange Chocolate</h3>
      <p>Smooth chocolate blended with refreshing orange zest.</p>
      <div class="price-tag">MP: RM13.90 | NP: RM15.90</div>
    </figcaption>
  </figure>

  <figure class="product-card">
    <img src="images/coffee/Strawberry Matcha.jpeg" alt="Strawberry Matcha" />
    <figcaption>
      <h3>Strawberry Matcha</h3>
      <p>Creamy matcha layered with sweet strawberry puree.</p>
      <div class="price-tag">MP: RM14.90 | NP: RM16.90</div>
    </figcaption>
  </figure>

  <figure class="product-card">
    <img src="images/coffee/Mint Chocolate.jpeg" alt="Mint Chocolate" />
    <figcaption>
      <h3>Mint Chocolate</h3>
      <p>A refreshing chocolate treat with minty twist.</p>
      <div class="price-tag">MP: RM13.90 | NP: RM15.90</div>
    </figcaption>
  </figure>
</section>

<section class="pricing-section">
  <h2>Menu</h2>
  <table>
    <thead><tr><th>Drink</th><th>MP (RM)</th><th>NP (RM)</th></tr></thead>
    <tbody>
        <tr><td>Chocolate</td><td>13.90</td><td>15.90</td></tr>
        <tr><td>Mint Chocolate</td><td>13.90</td><td>15.90</td></tr>
        <tr><td>Orange Chocolate</td><td>13.90</td><td>15.90</td></tr>
        <tr><td>Yuzu Soda</td><td>13.90</td><td>15.90</td></tr>
        <tr><td>Strawberry Soda</td><td>13.90</td><td>15.90</td></tr>
        <tr><td>Yuzu Cheese</td><td>13.90</td><td>15.90</td></tr>
        <tr><td>Yuri Matcha</td><td>13.90</td><td>15.90</td></tr>
        <tr><td>Strawberry Matcha</td><td>14.90</td><td>16.90</td></tr>
        <tr><td>Yuzu Matcha</td><td>14.90</td><td>16.90</td></tr>
        <tr><td>Houjicha</td><td>13.90</td><td>15.90</td></tr>
        </tr></tbody>
  </table>
</section>

<aside class="price-note-aside">
  <h2>Important Price Info</h2>
  <ol>
    <li><strong>MP</strong> = Member Price</li>
    <li><strong>NP</strong> = Normal Price</li>
    <li>Add RM2 for Oat Milk</li>
  </ol>
  <dl>
    <dt>MP</dt>
    <dd>Discounted rate for Brew & Go members.</dd>
    <dt>NP</dt>
    <dd>Standard price for all customers.</dd>
  </dl>
</aside>

<!-- Footer -->
   <?php include 'footer.php'; ?>
   
    <?php include 'backtotop.php'; ?>

</body>
</html>