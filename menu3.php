<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="author" content="Tammy Ru Xiu TAY" />
  <title>Basic Brew - Brew & Go</title>
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
      <h1>Basic Brew</h1>
      <a href="menu.php" class="back-button">Back</a>
    </div>
  </div>
</header>

<section class="product-grid">
  <figure class="product-card">
    <img src="images/coffee/Iced Americano.jpeg" alt="Americano" />
    <figcaption>
      <h3>Americano</h3>
      <p>Bold and smooth espresso diluted with hot water.</p>
      <div class="price-tag">MP: RM8.90 | NP: RM10.90</div>
    </figcaption>    
  </figure>

  <figure class="product-card">
    <img src="images/coffee/Iced Cappuccino.jpeg" alt="Cappuccino" />
    <figcaption>
      <h3>Cappuccino</h3>
      <p>Rich espresso topped with steamed milk and airy foam.</p>
      <div class="price-tag">MP: RM11.90 | NP: RM13.90</div>
    </figcaption>    
  </figure>

  <figure class="product-card">
    <img src="images/coffee/Aerocano.jpeg" alt="Aerocano" />
    <figcaption>
      <h3>Aerocano</h3>
      <p>Light-bodied espresso, smooth milk and a hint of chocolate.</p>
      <div class="price-tag">MP: RM10.90 | NP: RM12.90</div>
    </figcaption>    
  </figure>

  <figure class="product-card">
    <img src="images/coffee/Aero Latte.jpeg" alt="Aero-latte" />
    <figcaption>
      <h3>Aero-latte</h3>
      <p>Velvety milk layered with espresso for a silky finish.</p>
      <div class="price-tag">MP: RM12.90 | NP: RM14.90</div>
    </figcaption>    
  </figure>
</section>

<section class="pricing-section">
  <h2>Menu</h2>
  <table>
    <thead><tr><th>Drink</th><th>MP (RM)</th><th>NP (RM)</th></tr></thead>
    <tbody>
        <tr><td>Americano</td><td>8.90</td><td>10.90</td></tr>
        <tr><td>Latte</td><td>10.90</td><td>12.90</td></tr>
        <tr><td>Cappuccino</td><td>11.90</td><td>13.90</td></tr>
        <tr><td>Aerocano</td><td>10.90</td><td>12.90</td></tr>
        <tr><td>Aero-latte</td><td>12.90</td><td>14.90</td></tr>
        </tbody>
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