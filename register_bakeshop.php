<?php
include('backend/register_bakeshop.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply to Bakeshop</title>
    <link rel="stylesheet" href="css/signupbakeshop.css">
</head>
<body>

    <?php
    include('backend/checkid.php')
    ?>
    
    <div class="signup-container">
        <div class="signup-box">
            <div class="signup-box-content">
                <h1>Apply to Bakeshop</h1>
                <form action="backend/register_bakeshop.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    <h2>Bakeshop Information</h2>
                    <div class="form-group">
                        <label for="business-name">Bakeshop Name</label>
                        <input type="text" id="business-name" name="business_name" required>
                    </div>
                    <div class="form-group">
                        <label for="bakeshop-address">Bakeshop Street/Building Number</label>
                        <input type="text" id="bakeshop-address" name="bakeshop_address" required>
                    </div>
                    <div class="form-group">
                        <label for="bakeshop-city">City</label>
                        <input type="text" id="bakeshop-city" name="bakeshop_city" required>
                    </div>
                    <div class="form-group">
                        <label for="bakeshop-province">Province</label>
                        <input type="text" id="bakeshop-province" name="bakeshop_province" required>
                    </div>
                    <div class="form-group">
                        <label for="bakeshop-postal-code">Postal/Zip Code</label>
                        <input type="text" id="bakeshop-postal-code" name="bakeshop_postal_code" required>
                    </div>
                    <h2>Permits</h2>
                    <div class="form-group">
                        <label for="business-permit">Business Permit Photo</label>
                        <input type="file" id="business-permit" name="business_permit_photo" accept="image/*">
                    </div>
                    <div class="form-group">
                        <label for="health-permit">Health Permit Photo</label>
                        <input type="file" id="health-permit" name="health_permit_photo" accept="image/*">
                    </div>
                    <h2>Bakeshop Picture</h2>
                    <div class="form-group">
                        <input type="file" id="bakeshop-photo" name="bakeshop_photo" accept="image/*">
                    </div>
                    <button type="submit">Apply</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
