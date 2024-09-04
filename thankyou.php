<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "furniture_db";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION["customer"]) && isset($_SESSION["basket"]) && !empty($_SESSION["basket"])) {
    $customerId = isset($_SESSION["customer"]["customerId"]) ? $_SESSION["customer"]["customerId"] : null;
    $price = calculateTotal($_SESSION["basket"]);
    $shippingCost = 50;
    $price = $price + $shippingCost;

    foreach ($_SESSION["basket"] as $item) {
        $prodId = isset($item["id"]) ? $item["id"] : null;
        $prodName = isset($item["name"]) ? $item["name"] : null;

        $insertOrder = $conn->prepare("INSERT INTO orders (prodId,prodName, price) VALUES (?, ?, ?)");
        if (!$insertOrder) {
            die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }

        $insertOrder->bind_param("isi", $prodId, $prodName, $price);

        if (!$insertOrder->execute()) {
            die("Execute failed: (" . $insertOrder->errno . ") " . $insertOrder->error);
        }
    }

    // Clear the basket after the order is confirmed
    $_SESSION["basket"] = array();

    echo "Order successfully placed!";
} else {
    echo "Your basket is empty. Add items before confirming the order.";
}

$conn->close();

function calculateTotal($basket)
{
    $price = 0;
    foreach ($basket as $item) {
        $price += $item["price"] * $item["qty"];
    }
    return $price;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Checkout &#124; DAVA</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <link href="css/home.css" rel="stylesheet" type="text/css" />
    <link href="css/basket.css" rel="stylesheet" type="text/css" />
    <link href="css/checkout.css" rel="stylesheet" type="text/css" />
    <script src="javascript/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="javascript/validation.js" type="text/javascript"></script>

    <script type="text/javascript">
        function btnUpdate() {
            var frmUpdate = document.getElementById("frmUpdate");
            var qty = frmUpdate.qtyUpdate.value;

            if (isEmpty(qty)) {
                alert("Please enter quantity!");
                return false;
            }

            if (!isInteger(qty)) {
                alert("Please enter a whole number!");
                return false;
            }

            if (qty < 0) {
                alert("Please enter a positive number!")
                return false;
            }
        }
    </script>
</head>

<body>
    <div id="container">
        <div id="headerDiv">
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
            <p>
                <a id="login" href="login.php">login &#124;</a>
                <a id="cart" href="basket.php">
                    <img src="css/images/imgCartW26xH26.png" width="26" height="26" alt="Cart Image" />
                    my cart&nbsp;<?php $size = sizeof($_SESSION["basket"]);
                                    echo "<span id='nItems'>$size</span>"; ?>&nbsp;items
                </a>
            </p>
        </div>

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

        <div id="basketDiv">
            <h3 id="basketHeading"> Review Order </h3>

            <table id="basketTable">
                <tr>
                    <th id="thProdName" colspan="2">Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th id="thLineTotal">&nbsp;&nbsp;Line Total</th>
                </tr>
                <tr>
                    <td class='tdFirstThinLine' colspan='5'> </td>
                </tr>
                <?php
                if (!isset($_SESSION["customer"]) || !isset($_SESSION["basket"])) {
                    header("Location: index.php");
                } else {
                    $basket = $_SESSION["basket"];
                    $total = 0;

                    foreach ($basket as $key => $item) {
                        $id = isset($item["id"]) ? $item["id"] : null;
                        $type = $item["type"];
                        $imgName = $item["imageName"];
                        $name = $item["name"];
                        $price = $item["price"];
                        $qty = $basket[$key]["qty"];
                        $cost = $qty * $price;
                        $total = $total + ($price * $qty);
                        echo "<tr id='tr$id'>
                                        <td class='tdProdImg'> <img src='css/images/$type/$imgName' width='50' height='52' alt='image $imgName'/> </td>
                                        <td class='tdName'> <p>$name</p> </td>
                                        <td class='tdPrice'>$price </td>
                                        <td class='tdQty'> $qty </td>
                                        <td class='tdLineTotal'>&nbsp;&nbsp;$cost </td>
                                      </tr>
                                      <tr class='trThinLine'>
                                        <td class='tdFirstThinLine' colspan='5'> </td>
                                      </tr>";
                    }
                    echo "<tr>
                            <td class='tdFirstThinLine' colspan='5'> </td>
                          </tr>";
                    echo "<tr id='trTotal'>
                            <td class='tdTotal' colspan='4'>Total: </td>
                            <td class='tdTotal'>&nbsp;&nbsp;$total </td>
                          </tr>";
                }
                ?>
            </table>

            <div id='orderOptions'>
                <form id='frmOrderOptions' action='checkout.php' method='post'>
                    <input type='submit' id='btnConfirmOrder' name='confirm_order' value='Confirm Order' />
                </form>
            </div>

        </div>

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
</body>

</html>
