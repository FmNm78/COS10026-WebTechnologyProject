<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="author" content="Tammy Ru Xiu TAY" />
  <title>Our Activities - Brew & Go</title>
  <link rel="stylesheet" href="styles/style.css" />
</head>

<body>

    <div id="top"></div>
    
    
    <div class="main-wrapper">
      <!-- Navigation Bar -->
      <?php include 'navbar.php'; ?>



    <!-- Activities Entry Page -->
    <section class="activities-entry-page">
      <h1>Our Activities</h1>
      <p>Explore what's happening at Brew & Go:</p>

      <div class="activities-grid">
        <!-- Coming Soon -->
        <div class="activities-card">
          <a href="coming_soon.php">
            <img src="images/CS/Seni Kita Weekend 4.0.jpg" alt="Coming Soon Activities" />
            <h3>Coming Soon</h3>
            <p>Exciting activities coming your way – stay tuned!</p>
          </a>
        </div>

        <!-- Current -->
        <div class="activities-card">
          <a href="current.php">
            <img src="images/Current/Grand Opening 2.0.jpg" alt="Current Activities" />
            <h3>Current Activities</h3>
            <p>Check out what's happening now at Brew & Go.</p>
          </a>
        </div>

        <!-- Past Activities -->
        <div class="activities-card">
          <a href="past_activities.php">
            <img src="images/Past/Seni Kita Weekend 1.0 v1.jpg" alt="Past Activities" />
            <h3>Past Activities</h3>
            <p>Throwback to some of our amazing past events.</p>
          </a>
        </div>
      </div>
    </section>
  </div> 

  <!-- Footer -->
   <?php include 'footer.php'; ?>
   
    <?php include 'backtotop.php'; ?>
</body>
</html>
