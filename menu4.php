<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="author" content="Tammy Ru Xiu TAY" />
  <title>Hot Beverages - Brew & Go</title>
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
      <h1>Hot Beverages</h1>
      <a href="menu.php" class="back-button">Back</a>
    </div>
  </div>
</header>

<section class="product-grid">
  <figure class="product-card">
    <img src="images/coffee/Hot Americano.jpeg" alt="Americano" />
    <figcaption>
      <h3>Americano</h3>
      <p>Straight and bold espresso, perfect for a chilly day.</p>
      <div class="price-tag">MP: RM8.90 | NP: RM10.90</div>
    </figcaption>    
  </figure>

  <figure class="product-card">
    <img src="images/coffee/Hot Latte.jpeg" alt="Latte" />
    <figcaption>
      <h3>Latte</h3>
      <p>Warm espresso blended with silky steamed milk.</p>
      <div class="price-tag">MP: RM10.90 | NP: RM12.90</div>
    </figcaption>    
  </figure>

  <figure class="product-card">
    <img src="images/coffee/Hot Yuri Matcha.jpeg" alt="Yuri Matcha" />
    <figcaption>
      <h3>Yuri Matcha</h3>
      <p>Hot ceremonial-grade matcha with floral undertones.</p>
      <div class="price-tag">MP: RM13.90 | NP: RM15.90</div>
    </figcaption>    
  </figure>

  <figure class="product-card">
    <img src="images/coffee/Hot Houjicha.jpeg" alt="Hojicha" />
    <figcaption>
      <h3>Hojicha</h3>
      <p>Roasted Japanese green tea with deep nutty flavor.</p>
      <div class="price-tag">MP: RM13.90 | NP: RM15.90</div>
    </figcaption>    
  </figure>
</section>

<section class="pricing-section">
  <h2>Menu</h2>
  <table>
    <thead><tr><th>Drink</th><th>MP (RM)</th><th>NP (RM)</th></tr></thead>
    <tbody>
        <tr><td>Americano</td><td>7.90</td><td>9.90</td></tr>
        <tr><td>Latte</td><td>9.90</td><td>11.90</td></tr>
        <tr><td>Butterscotch Latte</td><td>10.90</td><td>12.90</td></tr>
        <tr><td>Cappuccino</td><td>10.90</td><td>12.90</td></tr>
        <tr><td>Chocolate</td><td>12.90</td><td>14.90</td></tr>
        <tr><td>Yuri Matcha</td><td>13.90</td><td>14.90</td></tr>
        <tr><td>Houjicha</td><td>13.90</td><td>14.90</td></tr>
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