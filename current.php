<?php
require_once 'connection.php';
date_default_timezone_set('Asia/Kuching');

// Current date and time for Asia/Kuching
$today = date('Y-m-d');
$now = date('H:i:s');

// Fetch current activities (happening today, now)
$result = mysqli_query($conn, "
    SELECT * FROM activities
    WHERE event_date = '$today'
      AND start_time <= '$now' AND end_time >= '$now'
    ORDER BY start_time ASC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="author" content="Norman Zhi Wen Chung" />
  <title>Current Activities - Brew & Go</title>
  <link rel="stylesheet" href="styles/style.css" />
</head>

<body>
  <div id="top"></div>
  <?php include 'navbar.php'; ?>

  <section class="hero-header">
    <div class="banner-text">
      <div class="hero-text">
        <h1>Current</h1>
        <a href="activities.php" class="back-button">Back</a>
      </div>
    </div>
  </section>

  <section class="current-intro">
    <h2 class="current-banner-title">Now Happening!</h2>
    <p>
      Be part of the action — something exciting is happening right now at Brew & Go. 
      Join us at these live events for the full experience!
    </p>
  </section>

  <?php if (mysqli_num_rows($result) > 0): ?>
    <?php while ($event = mysqli_fetch_assoc($result)): ?>
      <div class="current-event-card">
        <!-- Media Section -->
        <section class="current-media">
          <h2 class="media-section-title"><?= htmlspecialchars($event['title']) ?></h2>
          <div class="media-gallery">
            <?php if (!empty($event['image_path'])): ?>
              <figure>
                <img src="<?= htmlspecialchars($event['image_path']) ?>" alt="Event Image" />
                <figcaption><?= htmlspecialchars($event['title']) ?></figcaption>
              </figure>
            <?php endif; ?>
            <?php /* Add logic here for multiple images/videos if you expand the DB structure */ ?>
          </div>
        </section>

        <!-- About Section -->
        <section class="current-info">
          <h2>Event Description</h2>
          <p><?= nl2br(htmlspecialchars($event['description'])) ?></p>
          <dl>
            <dt>Event Date & Time</dt>
            <dd>
              <?= date('g:i A', strtotime($event['start_time'])) ?> – 
              <?= date('g:i A', strtotime($event['end_time'])) ?>,
              <?= date('d M Y', strtotime($event['event_date'])) ?>
            </dd>
            <?php if (!empty($event['location'])): ?>
              <dt>Location</dt>
              <dd><?= htmlspecialchars($event['location']) ?></dd>
            <?php endif; ?>
          </dl>
          <?php if (!empty($event['external_link'])): ?>
            <p>
              <a href="<?= htmlspecialchars($event['external_link']) ?>" target="_blank" class="event-link">More Info</a>
            </p>
          <?php endif; ?>
        </section>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="current-event-wrapper">
    <div class="current-event-card">
      No events are currently happening.
    </div>
  </div>
  <?php endif; ?>

  <!-- Aside -->
  <aside class="current-aside">
    <h3>Did You Know?</h3>
    <p>
      Brew & Go brings live events right to your neighborhood — join us and create memories!
    </p>
  </aside>

  <?php include 'footer.php'; ?>
  <?php include 'backtotop.php'; ?>
</body>
</html>
