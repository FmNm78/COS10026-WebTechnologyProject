<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="author" content="Bahrose Hassan Babar" />
  <title>Our Menu - Brew & Go</title>
  <link rel="stylesheet" href="styles/style.css" />
</head>

<body>

  <div id="top"></div>

<!-- Navigation Bar -->
<header>
    <?php include 'navbar.php'; ?>
</header>

<!-- Join Us Section -->
<section class="joinus-hero">
    <div class="joinus-overlay">
      <div class="joinus-container">
  
        <!-- Open Positions -->
        <section class="joinus-section">
            <h2 class="section-title">Open Positions</h2>
            <div class="positions-wrapper">
              <a href="#joinus-form" class="position-card">
                <div class="position-content">
                  <h3>Barista</h3>
                  <p>One Jaya Mall & Plaza Merdeka Mall</p>
                </div>
              </a>
              
              <a href="#joinus-form" class="position-card">
                <div class="position-content">
                  <h3>Cashier</h3>
                  <p>One Jaya Mall & Plaza Merdeka Mall</p>
                </div>
              </a>
            </div>
          </section>
          
  
        <!-- Benefits -->
        <section class="joinus-section">
            <h2 class="section-title">Benefits</h2>
            <div class="benefit-cards">
              <div class="benefit-card">
                <img src="images/EPF & Socso.png" alt="EPF & Socso" class="epf-logo"/>
                <p>EPF & SOCSO</p>
              </div>
              <div class="benefit-card">
                <img src="images/Meal Allowance.png" alt="Meal Allowance" />
                <p>Meal Allowance</p>
              </div>
              <div class="benefit-card">
                <img src="images/Commision.png" alt="Commission" />
                <p>Sales Commission</p>
              </div>
            </div>
          </section>
          
  
        <!-- Requirements -->
        <section class="joinus-section">
            <h2>Requirements</h2>
            <p class="requirements-desc">We’re looking for passionate team members who meet the following:</p>            
          <ul class="requirement-list">
            <li><span>01</span> Age 18 years old & above</li>
            <li><span>02</span> Fluent in English, Mandarin & BM</li>
            <li><span>03</span> Full-time & Part-time available</li>
            <li><span>04</span> Possess own transportation</li>
          </ul>
        </section>
  
       <!-- Application Form -->
       <section class="joinus-form-section" id="joinus-form">
        <h2>Let's work together！</h2>
  <form class="joinus-form" action="process_joinus.php" method="post" enctype="multipart/form-data">
    <fieldset>
      <label>First Name:
        <input type="text" name="first_name" maxlength="25" pattern="[A-Za-z\s]+" required>
      </label>

      <label>Last Name:
        <input type="text" name="last_name" maxlength="25" pattern="[A-Za-z\s]+" required>
      </label>

      <label>Email Address:
        <input type="email" name="email" required>
      </label>

      <fieldset class="joinus-form-address">
        <legend><strong>Address</strong></legend>

        <label>Street Address:
          <input type="text" name="street" maxlength="40" required>
        </label>

        <label>City/Town:
          <input type="text" name="city" maxlength="20" required>
        </label>

        <label>State:
          <select name="state" required>
            <option value="">-- Select State --</option>
            <option value="Johor">Johor</option>
            <option value="Kedah">Kedah</option>
            <option value="Kelantan">Kelantan</option>
            <option value="Malacca">Malacca</option>
            <option value="Negeri Sembilan">Negeri Sembilan</option>
            <option value="Pahang">Pahang</option>
            <option value="Penang">Penang</option>
            <option value="Perak">Perak</option>
            <option value="Perlis">Perlis</option>
            <option value="Sabah">Sabah</option>
            <option value="Sarawak">Sarawak</option>
            <option value="Selangor">Selangor</option>
            <option value="Terengganu">Terengganu</option>
          </select>
        </label>

        <label>Postcode:
          <input type="text" name="postcode" pattern="\d{5}" maxlength="5" required>
        </label>
      </fieldset>

      <label>Phone Number:
        <input type="tel" name="phone" maxlength="10" placeholder="e.g. 0123456789" required>
      </label>

      <fieldset class="joinus-form-shift">
        <legend><strong>Preferred Shift</strong></legend>
        <label>
          <input type="radio" name="shift" value="morning" required> Morning
        </label>
        <label>
          <input type="radio" name="shift" value="evening"> Evening
        </label>
      </fieldset>

      <label>CV Upload:
        <input type="file" name="cv" accept=".doc,.docx,.pdf">
      </label>

      <label>Photo Upload:
        <input type="file" name="photo" accept="image/*">
        <small>(Max size: 200KB)</small>
      </label>


      <div class="button-group">
        <button type="submit" class="btn-submit">Submit</button>
        <button type="reset" class="btn-reset">Reset</button>
      </div>
      
    </fieldset>
  </form>
</div>
</div>
</section>

<!-- Footer -->
   <?php include 'footer.php'; ?>
   
    <?php include 'backtotop.php'; ?>

</body>
</html>
