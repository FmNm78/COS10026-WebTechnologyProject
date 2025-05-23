<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="author" content="Tammy Ru Xiu TAY" />
  <title>Artisan Brew - Brew & Go</title>
  <link rel="stylesheet" href="styles/style.css" />
</head>
<body>

  <div id="top"></div>

<!-- Navigation -->
<header>
  <?php include 'navbar.php'; ?>
</header>

<!-- Header with background -->
<header class="product-banner">
  <div class="banner-overlay">
    <div class="banner-text">
      <h1>Artisan Brew</h1>
      <a href="menu.php" class="back-button">Back</a>
    </div>
  </div>
</header>


<!-- Product Grid -->
<!-- Product Card Section -->
<section class="product-grid">
  <figure class="product-card">
    <img src="images/coffee/Cheese Americano.jpeg" alt="Cheese Americano" />
    <figcaption>
      <h3>Cheese Americano</h3>
      <p>Smooth black coffee with a creamy cheese foam topping.</p>
      <div class="price-tag">MP: RM13.90 | NP: RM15.90</div>
    </figcaption>
  </figure>
  
  <figure class="product-card">
    <img src="images/coffee/Yuzu Americano.jpeg" alt="Yuzu Americano" />
    <figcaption>
      <h3>Yuzu Americano</h3>
      <p>Espresso with Japanese citrus syrup – fruity and uplifting.</p>
      <div class="price-tag">MP: RM13.90 | NP: RM15.90</div>
    </figcaption>
  </figure>
  
  <figure class="product-card">
    <img src="images/coffee/Orange Mocha.jpeg" alt="Orange Mocha" />
    <figcaption>
      <h3>Orange Mocha</h3>
      <p>Rich chocolate with orange zest. Fruity and indulgent.</p>
      <div class="price-tag">MP: RM12.90 | NP: RM14.90</div>
    </figcaption>
  </figure>
  
  <figure class="product-card">
    <img src="images/coffee/Pistachio Latte.jpeg" alt="Pistachio Latte" />
    <figcaption>
      <h3>Pistachio Latte</h3>
      <p>Nutty, creamy, and visually stunning. A unique fan favorite.</p>
      <div class="price-tag">MP: RM15.90 | NP: RM17.90</div>
    </figcaption>
  </figure>
  
</section>

<!-- Pricing Table -->
<section class="pricing-section">
  <h2>Menu</h2>
  <table>
    <thead>
      <tr>
        <th>Drink</th>
        <th>MP (RM)</th>
        <th>NP (RM)</th>
      </tr>
    </thead>
    <tbody>
      <tr><td>Butterscotch Latte</td><td>11.90</td><td>13.90</td></tr>
      <tr><td>Butterscotch Creme</td><td>14.90</td><td>16.90</td></tr>
      <tr><td>Mint Latte</td><td>12.90</td><td>14.90</td></tr>
      <tr><td>Vienna Latte</td><td>14.90</td><td>16.90</td></tr>
      <tr><td>Pistachio Latte</td><td>15.90</td><td>17.90</td></tr>
      <tr><td>Strawberry Latte</td><td>14.90</td><td>16.90</td></tr>
      <tr><td>Mocha</td><td>11.90</td><td>13.90</td></tr>
      <tr><td>Mint Mocha</td><td>12.90</td><td>14.90</td></tr>
      <tr><td>Orange Mocha</td><td>12.90</td><td>14.90</td></tr>
      <tr><td>Yuzu Americano</td><td>13.90</td><td>15.90</td></tr>
      <tr><td>Cheese Americano</td><td>13.90</td><td>15.90</td></tr>
      <tr><td>Orange Americano</td><td>13.90</td><td>15.90</td></tr>
      <tr>
        <td>Extra Espresso Shot</td>
        <td colspan="2" class="centered-cell">RM2</td>
      </tr>      
    </tbody>
  </table>
</section>

<!-- Price Note -->
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
