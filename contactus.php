<?php
session_start();

// Modify these values with your actual database credentials
$host = "localhost";
$username = "root";
$password = "";
$database = "furniture_db";  // Change this to the desired database name

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["btnSubmitFeedback"])) {
    $uname = $_POST["txtName"];
    $email = $_POST["txtEmail"];
    $feedback = $_POST["txtFeedback"];

    $insertQuery = "INSERT INTO feedback (uname, email, feedback) VALUES ('$uname', '$email', '$feedback')";
    $conn->query($insertQuery);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Furniture &amp; House Decoration | DAVA</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="css/contactus.css"> <!-- Create a new CSS file for contactus styles -->
    <script src="javascript/jquery-1.8.3.min.js"></script>
    <script src="javascript/jquery.cycle.all.js"></script>
    <script src="javascript/jquery.easing.1.3.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#imgSlides').cycle({
                fx: 'fade',
                speed: 800,
                timeout: 3000,
                pager: '#ulThumbs',
                pause: 1,
                pagerAnchorBuilder: function (idx, slide) {
                    return '#ulThumbs li:eq(' + (idx) + ') a';
                }
            });

            $('#featuredSlides').cycle({
                fx: 'scrollHorz',
                timeout: 0,
                next: '#right',
                prev: '#left',
                nowrap: 0
            });
        });
    </script>
</head>

<body>
    <div id="containerDiv">
        <div id="headerDiv">
            <?php
            if (isset($_POST["btnLogout"])) {
                unset($_SESSION["customer"]);
            }
            if (isset($_SESSION["customer"])) {
                $custName = $_SESSION["customer"]["name"];
                echo "<span id='welcomeSpan'><a id='aWelcome' href='account.php'>Welcome, $custName</a></span>";
                echo "<script>$(function() $('#login').remove();)</script>";
            }
            ?>
            <p>
                <a id="login" href="login.php">login &#124;</a>
                <a id="cart" href="basket.php">
                    <img src="css/images/imgCartW26xH26.png" width="26" height="26" alt="Cart Image" />
                    my cart&nbsp;<?php $size = sizeof($_SESSION["basket"]);
                                    echo "$size"; ?>&nbsp;items
                </a>
            </p>
        </div>

        <form action="" method="post">
            <div id="navigationDiv">
                <ul>
                    <li><a class="logo" href="index.php"></a></li>
                    <li><a class="button" style="width:110px" href="prodList.php?prodType=bed">BEDS</a></li>
                    <li><a class="button" style="width:110px" href="prodList.php?prodType=chair">CHAIRS</a></li>
                    <li><a class="button" style="width:110px" href="prodList.php?prodType=chest">CHESTS</a></li>
                    <li><a class="button" style="width:120px" href="contactus.php">Contact Us</a></li>
                    <li class="txtNav"><input type="text" name="txtSearch" /></li>
                    <li class="searchNav"><input type="submit" name="btnSearch" value="" /></li>
                </ul>
            </div>
        </form>

        <div id="contactUsDiv">
            <h3>Contact Us</h3>
            <form action="" method="post">
                <div class="form-group">
                    <label for="txtName">Your Name:</label>
                    <input type="text" name="txtName" id="txtName" required>
                </div>

                <div class="form-group">
                    <label for="txtEmail">Your Email:</label>
                    <input type="email" name="txtEmail" id="txtEmail" required>
                </div>

                <div class="form-group">
                    <label for="txtFeedback">Feedback:</label>
                    <textarea name="txtFeedback" id="txtFeedback" rows="4" required></textarea>
                </div>

                <input type="submit" name="btnSubmitFeedback" value="Submit Feedback">
            </form>
        </div>
    </div>
</body>

</html>
