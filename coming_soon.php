<?php
require_once 'connection.php';
// Fetch upcoming activities (can also use WHERE type = 'coming' if you add that column)
$result = mysqli_query($conn, "
    SELECT * FROM activities
    WHERE event_date > CURDATE()
    ORDER BY event_date ASC
");
?>
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
    <h2 class="coming-banner-title">Upcoming Special Events</h2>
    <p>Mark your calendar — something exciting is brewing at Brew & Go. Here are our upcoming events!</p>
  </section>

  <?php if (mysqli_num_rows($result) > 0): ?>
    <?php while ($event = mysqli_fetch_assoc($result)): ?>
      <div class="coming-event-card">
        <!-- Media Section -->
        <section class="coming-media">
          <h2 class="media-section-title"><?= htmlspecialchars($event['title']) ?></h2>
          <div class="media-gallery">
            <?php if (!empty($event['image_path'])): ?>
              <figure>
                <img src="<?= htmlspecialchars($event['image_path']) ?>" alt="Event Image" />
                <figcaption><?= htmlspecialchars($event['title']) ?></figcaption>
              </figure>
            <?php endif; ?>
            <?php /* You can add logic here to support event videos if needed */ ?>
          </div>
        </section>

        <!-- About Section -->
        <section class="coming-info">
          <h2>Event Description</h2>
          <p><?= nl2br(htmlspecialchars($event['description'])) ?></p>
          <dl>
            <dt>Booth Operating Hours</dt>
            <dd>
              <?= date('g:i A', strtotime($event['start_time'])) ?> – 
              <?= date('g:i A', strtotime($event['end_time'])) ?>,
              <?= date('d M Y', strtotime($event['event_date'])) ?>
              <?= $event['location'] ? " ({$event['location']})" : "" ?>
            </dd>
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
    <div class="coming-event-card" style="text-align:center;margin:40px 0;font-size:1.2em;">
      No upcoming events at the moment.
    </div>
  <?php endif; ?>

  <aside class="coming-aside">
    <h3>Did You Know?</h3>
    <p>
      This is Brew & Go’s fourth appearance at a cultural community event — and it keeps getting better each time.
    </p>
  </aside>

  <?php include 'footer.php'; ?>
  <?php include 'backtotop.php'; ?>
</body>
</html>
