<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Bahrose Hassan Babar" />
    <title>Brew & Co. Coffee</title>
    <link rel="stylesheet" href="styles/style.css">
</head>

<body>
    <div id="top"></div>
    <header>
        <?php include 'navbar.php'; ?>
    </header>
    


    <section class="enquiry-hero">
        <div class="enquiry-overlay">

            <h2 class="enquiry-title">Enquiry</h2>
            
            <form class="enquiry-form">
            
            <fieldset>
                
                <label>First Name:
                <input type="text" name="first-name" maxlength="25" required>
                </label>

                <label>Last Name:
                <input type="text" name="last-name" maxlength="25" required>
                </label>

                <label>Email Address:
                <input type="email" name="email" required>
                </label>
                
                <fieldset class="enquiry-form-address">
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
                    <input type="text" name="postcode" maxlength="5" pattern="\d{5}" required>
                </label>

                </fieldset>

                <label>Phone Number:
                    <input type="tel" name="phone" maxlength="10" placeholder="e.g. 0123456789" required>
                </label>
                
                <label>Type of Enquiry:
                <select name="enquiry-type" required>
                    <option value="">Select</option>
                    <option value="Membership">Membership</option>
                    <option value="Products">Products</option>
                    <option value="Pop-up Market">Pop-up Market</option>
                </select>
                </label>
                <label>Your Message:
                <textarea name="message" rows="5" required></textarea>
                </label>
                
                <div class="button-group">
                    <button type="submit" class="btn-submit">Submit</button>
                    <button type="reset" class="btn-reset">Reset</button>
                </div>

            </fieldset>
            </form>
        </div>
    </section>
      
   <?php include 'footer.php'; ?>
   
    <?php include 'backtotop.php'; ?>

</body>
</html>