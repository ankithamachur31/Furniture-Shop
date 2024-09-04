<?php
session_start();
ob_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Shopping Basket &#124; DAVA</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <link href="ratingfiles/ratings.css" rel="stylesheet" type="text/css" />

    <link href="css/home.css" rel="stylesheet" type="text/css" />
    <link href="css/basket.css" rel="stylesheet" type="text/css" />
    <!--///////////////////////////////END OF STYLE SHEET ///////////////////////-->

    <script src="ratingfiles/ratings.js" type="text/javascript"></script>
    <script src="javascript/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="javascript/jquery.cycle.all.js" type="text/javascript"></script>
    <script src="javascript/validation.js" type="text/javascript"></script>
</head>

<body>
    <div id="container">
        <div id="headerDiv">
            <!--/////////////////////////// WELCOME USER ////////////////////////////////-->
            <?php
            if (isset($_POST["btnLogout"])) {
                unset($_SESSION["customer"]);
            }
            if (isset($_SESSION["customer"])) {
                $custName = $_SESSION["customer"]["name"];
                echo "<span id='welcomeSpan'><a id='aWelcome' href='account.php'>Welcome, $custName</a></span>";
                echo "  <script> 
                            $(function() 
                                {
                                    $('#login').remove();
                                })
                            </script>";
            }
            ?>
            <!--///////////////////////// END OF WELCOME USER ///////////////////////////-->
            <p>
                <a id="login" href="login.php">login &#124;</a>
                <a id="cart" href="basket.php">
                    <img src="css/images/imgCartW26xH26.png" width="26" height="26" alt="Cart Image" />
                    my cart&nbsp;<?php $size = sizeof($_SESSION["basket"]);
                                    echo "<span id='nItems'>$size</span>"; ?>&nbsp;items
                </a>
            </p>
        </div>
        <!--///////////////////////////////NAVIGATION PANEL//////////////////////////-->
        <form action="search.php" method="post">
            <div id="navigationDiv">
                <ul>
                    <li> <a class="logo" href="index.php"></a> </li>
                    <li> <a class="button" style="width:110px" href="prodList.php?prodType=bed">BEDS</a> </li>
                    <li> <a class="button" style="width:110px" href="prodList.php?prodType=chair">CHAIRS</a> </li>
                    <li> <a class="button" style="width:110px" href="prodList.php?prodType=chest">CHESTS</a> </li>
                    <li> <a class="button" style="width:120px" href="contactus.php">Contact Us</a> </li>
                    <li class="txtNav"> <input type="text" name="txtSearch" /> </li>
                    <li class="searchNav"> <input type="submit" name="btnSearch" value="" /> </li>
                </ul>
            </div>
        </form>
        <!--///////////////////////////////END OF NAVIGATION/////////////////////////-->
        <div id="basketInfoBoxDiv">
            <hr class='thickLine' />
            <?php
            if (isset($_POST["btnUpdateBasket"]))  // IF UPDATE BASKET BUTTON CLICKED
            {
                $basket = $_SESSION["basket"];
                $size = sizeof($basket);
                for ($i = 0; $i < $size; $i++) {
                    $newQty = $_POST["txtQty" . $i];
                    $basket[$i]["qty"] = $newQty;
                }
                $_SESSION["basket"] = $basket;
                header("Location: basket.php");
            }

            if (isset($_POST["btnConfirmOrder"])) {
                // Perform any necessary actions before redirecting to checkout.php
                header("Location: checkout.php");
                exit();
            }
            ?>
            <form id='frmUpdateBasket' method='post' action='checkout.php'>
                <div id='basketDiv'>
                    <table id='basketTable'>
                        <tr>
                            <th></th>
                            <th>Product</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                        <?php
                        $basket = $_SESSION["basket"];
                        $size = sizeof($basket);
                        $total = 0;
                        for ($i = 0; $i < $size; $i++) {
                            $id = $basket[$i]["id"];
                            $name = $basket[$i]["name"];
                            $price = $basket[$i]["price"];
                            $qty = $basket[$i]["qty"];
                            $totalPerItem = $price * $qty;
                            $total += $totalPerItem;

                            echo "<tr>";
                            echo "<td><img src='css/images/{$basket[$i]['type']}/{$basket[$i]['imageName']}' alt='{$basket[$i]['type']} image' width='50' height='50'></td>";
                            echo "<td>{$name}</td>";
                            echo "<td>Product description goes here.</td>";
                            echo "<td>&#x20B9; {$price}</td>";
                            echo "<td><input type='text' name='txtQty{$i}' value='{$qty}' /></td>";
                            echo "<td>&#x20B9; {$totalPerItem}</td>";
                            echo "</tr>";
                        }
                        ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>Total:</td>
                            <td>&#x20B9; <?php echo $total; ?></td>
                        </tr>
                    </table>
                    <p>
                        <input type='submit' name='btnUpdateBasket' value='Update Basket' />
                        <input type='submit' name='btnConfirmOrder' value='Proceed to Checkout' />
                    </p>
                </div>
            </form>
            <div id='infoThickLine'></div> <!-- THICK LINE AT THE BOTTOM -->
            <div id='continueShopping'>
                <a id='aContinueShop' href='index.php'>Continue shopping</a>
            </div>
        </div>
        <!--///////////////////////////////END OF BASKET INFO BOX //////////////////-->

        <div id="footerDiv">
            <p>
                <a href="#">Terms of Use</a>
                &#124;
                <a href="#">Privacy Policy</a>
                &#124;
                <a href="#">&copy;2024 All Rights Reserved.</a>
            </p>
        </div>
    </div>
    <!--///////////////////////////////END OF CONTAINER /////////////////////////-->
</body>

</html>
<?php ob_flush(); ?>
