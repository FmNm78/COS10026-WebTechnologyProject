<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="author" content="Norman Zhi Wen Chung" />
  <title>Coming Soon - Brew & Go</title>
  <link rel="stylesheet" href="styles/style.css" />
</head>

<body>
  <div id="top"></div>

  <!-- Navigation -->
  
  <?php include 'navbar.php'; ?>

  <section class="hero-header">
    <div class="banner-text">
      <div class="hero-text">
        <h1>Coming Soon</h1>
        <a href="activities.php" class="back-button">Back</a>
      </div>
    </div>
  </section>
  
  
  <section class="coming-intro">
    <h2 class="coming-banner-title">Upcoming Special Event</h2>
    <p>Mark your calendar — something exciting is brewing at Brew & Go. Catch us at <strong>Seni Kita Weekend</strong> with our handcrafted coffee booth!</p>
  </section>

  <div class="coming-event-card">
  <!-- Media Section -->
  <section class="coming-media">
    <h2 class="media-section-title">Seni Kita Weekend</h2>
    <div class="media-gallery">
      <figure>
        <img src="images/CS/Seni Kita Weekend 4.0.jpg" alt="Brew Booth Setup" />
        <figcaption>Our drinks ready for the crowd</figcaption>
      </figure>
      <div class="media-video">
        <video controls>
          <source src="images/CS/Seni Kita Weekend 4.0 video.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
        <p class="video-caption">Sneak peek of the event vibes</p>
      </div>
      <figure>
        <img src="images/CS/Seni Kita Weekend 4.0 v1.jpg" alt="Booth Experience" />
        <figcaption>Visitors enjoying Brew & Go at the fair</figcaption>
      </figure>
    </div>
  </section>

    <!-- About Section -->
    <section class="coming-info">
      <h2>Event Description</h2>
      <p>
        Brew & Go is proud to participate in the <strong>Seni Kita Weekend</strong> event — a creative bazaar featuring art, culture, and coffee. 
      </p>
      <ol>
        <li>We're bringing our signature drinks to HAUS KCH.</li>
        <li>Visitors can enjoy exclusive creations only available during the event.</li>
      </ol>
      <dl>
        <dt>Booth Operating Hours</dt>
        <dd>3:00 PM – 10:00 PM, 29 March 2025 (HAUS KCH, Yun Phin Building)</dd>
      </dl>
    </section>

  </div>

  <!-- Aside -->
  <aside class="coming-aside">
    <h3>Did You Know?</h3>
    <p>This is Brew & Go’s fourth appearance at a cultural community event — and it keeps getting better each time.</p>
  </aside>



  <!-- Footer -->
   <?php include 'footer.php'; ?>
   
    <?php include 'backtotop.php'; ?>
</body>
</html>
