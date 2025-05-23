<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="author" content="Miaw Fong LIM" />
  <title>Login - Brew & Go</title>
  <link rel="stylesheet" href="styles/style.css" />
</head>

<body class="login-page">
    <header>
      <?php include 'navbar.php'; ?>
    </header>

      
      <section class="login-wrapper">
        <div class="login-outer">
          <div class="login-inner">
            <h1>Member Login</h1>
            <form class="login-form" action="#" method="post">
              <fieldset>
      
                <label for="login">Login ID:</label>
                <input type="text" id="login" name="login" maxlength="10" pattern="[A-Za-z]+" required>
      
                <label for="password">Password:</label>
                <input type="text" id="password" name="password" maxlength="25" pattern="[A-Za-z]+" required>
      
                <button type="submit">Login</button>
                <button type="reset">Reset</button>

                <p class="forgot-password">
                    <a href="#">Forgot your password?</a>
                </p>
                  
                <p class="register-link">
                    Not a member yet? <a href="membership.php">Sign up for membership</a>
                </p>
                  
              </fieldset>
            </form>
          </div>
        </div>
      </section>
      

   <?php include 'footer.php'; ?>
   
    <?php include 'backtotop.php'; ?>

</body>
</html>
