<?php

// Include autoloader
require_once 'dompdf/autoload.inc.php';

// Reference the Dompdf namespace
use Dompdf\Dompdf;

// Instantiate and use the dompdf class
$dompdf = new Dompdf();

$user_name = $_POST['username'];
$mobile_no = $_POST['mobile'];
$address = $_POST['delivery_address'];

// $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
$conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_devtesting");

$strings ="INSERT INTO User (user_id , created_at, name, email, password, mobile, address)
    VALUES(NULL,NULL,'". $user_name."','no_email', 'no_password','". $mobile_no."','". $address."')";
    
$result = $conn->query($strings);
$TotalPrice = 0;

$strings ="INSERT INTO BookOrder (book_order_id, user_id, shipping_address, total_cost, delivery_confirmed,order_issue)
    VALUES (NULL,(SELECT MAX(User.user_id) FROM User),
    (SELECT User.address FROM User where User.user_id = (SELECT MAX(User.user_id) FROM User)), '".$TotalPrice."' , '0' ,NULL)";
$result = $conn->query($strings);

$order = array();

for ($i=0; $i < 10; $i++) { 
    # code...
    $book_item = array();

    $book_id = $_POST['book_id'.$i];
    $quality = $_POST['quality_id'.$i];
    $quantity = $_POST['quantity'.$i];

    if($quality == 1){
        $book_item["quality"]="White Print";
    }
    else if($quality == 2){
        $book_item["quality"]="News Print";
    }
    else{
        $book_item["quality"]="(N/A)";
    }


    if($book_id == NULL)
    {
        break;
    }
    
    $strings ="SELECT name FROM Book WHERE book_id = '".$book_id."'";

    $result = $conn->prepare($strings);
    $result->execute();
    $result->bind_result($book_name);
    
    while($result->fetch()) {       
        $book_item["name"] = $book_name;
    }

    //$result->close();

    $strings ="SELECT DISTINCT Price.price FROM Price,Book where Price.book_id = '". $book_id."' and Price.quality_id = '". $quality."'";
    
    $result = $conn->prepare($strings);
    $result->execute();
    $result->bind_result($price);
    $tmp = array();

    while($result->fetch()) {       
        $tmp["price"] = $price;
    }

    if(sizeof($tmp)  == 0){

        array_push($order, $book_item);
        continue;
    }

    $TotalPrice += $quantity * $tmp["price"];
    if($quantity == 0)
    {
        continue;
    }

    $book_item["price"] = $tmp["price"];
    $book_item["quantity"] = $quantity;

    $strings ="INSERT INTO CartItem ( item_id, book_id, book_order_id, price_id, promo_id, number_of_item)
    VALUES (NULL,'". $book_id."',(SELECT MAX(BookOrder.book_order_id) FROM BookOrder),
    (SELECT DISTINCT Price.price_id FROM Price,Book where Price.book_id = '". $book_id."' and Price.quality_id = '". $quality."' ), '1','". $quantity."')";

    $result = $conn->query($strings);
    //$result->close();

    array_push($order, $book_item);

    }
$strings ="SELECT MAX(BookOrder.book_order_id) FROM BookOrder";

$result = $conn->prepare($strings);
$result->execute();
$result->bind_result($price);
$Invoice_Number = 1045;

while($result->fetch()) {       
    $Invoice_Number = $price;
}

$strings = "UPDATE BookOrder
    SET total_cost = '".$TotalPrice."'
    where book_order_id = '".$Invoice_Number."'";

$result = $conn->query($strings);

$inv_msg = " <div id=\"invoiceholder\" style=\"width: 100%;hieght: 100%;padding: 5px;\">
<div id=\"invoice\" class=\"effect2\" style=\"margin: 0 auto;width: 700px;background: #FFF;\">

    <div id=\"invoice-top\" style=\"min-height: 90px; margin: 25px; margin-bottom: 0px;\">
        <img style=\"height: 60px; width: 60px;\" src=\"./images/logo2.png\" />
        <div class=\"info\" style=\"display: block;\">
            <h2 style=\"font-size: .9em;\">KolpoBD</h2>
            <p style=\"font-size: .7em;color: #666;line-height: 1.2em;\">info@kolpobd.com
            </p>
        </div>
        <div style=\"position: relative;\">
        <div class=\"title\" style=\"display: inline-block;\">
            <h1 style=\"font-size: 1.5em;color: #222;\">Invoice #".$Invoice_Number."</h1>
            <p style=\"font-size: .7em;color: #666;line-height: 1.2em;text-align: left;\">Issued: Oct 31, 2018<br>
            Delivery Due: Oct 31, 2018</br>
            </p>
        </div>
        <div id=\"invoice-mid\" style=\"display: inline-block; float: right;\">
            <h1 style=\"font-size: 1.5em;color: #222;\">".$user_name."</h1>
            <p style=\"font-size: 0.8em;color: #000;line-height: 1.2em;\">Contact: ".$mobile_no."<br>
            Delivery Location: ".$address."</br>
            </p>
        </div>
        </div>
    </div>
    <!--End Invoice Mid-->

    <div id=\"invoice-bot\" style=\"min-height: 250px;\">

    <div id=\"table\">
        <table style=\"width: 100%;border-collapse: collapse;\">
        <tr class=\"tabletitle\" style=\"padding: 1px;background: #EEE;\">
            <td class=\"item\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;width: 50%;\">
            <h2 style=\"font-size: .9em;\">Item Description</h2>
            </td>
            <td class=\"Hours\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <h2 style=\"font-size: .9em;\">Category</h2>
            </td>
            <td class=\"Hours\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <h2 style=\"font-size: .9em;\">Quantity</h2>
            </td>
            <td class=\"Rate\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <h2 style=\"font-size: .9em;\">Price</h2>
            </td>
            <td class=\"subtotal\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <h2 style=\"font-size: .9em;\">Sub-total</h2>
            </td>
        </tr>";

foreach ($order as $value) {

    $inv_msg .= "<tr class=\"service\" style=\"border: 1px solid #EEE;\">
            <td class=\"tableitem\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\">".$value["name"]."</p>
            </td>
            <td class=\"tableitem\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\">".$value["quality"]."</p>
            </td>
            <td class=\"tableitem\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\">".$value["quantity"]."</p>
            </td>
            <td class=\"tableitem\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\">Tk ".$value["price"]."</p>
            </td>
            <td class=\"tableitem\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\">Tk ".$value["price"]*$value["quantity"]."</p>
            </td>
        </tr>";

}

$inv_msg .= "
<tr class=\"service\" style=\"border: 1px solid #EEE;\">
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
    </tr>

    <tr class=\"service\" style=\"border: 1px solid #EEE;\">
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
    </tr>

        <tr class=\"tabletitle\" style=\"padding: 1px;background: #EEE;\">
            <td style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\"></td>
            <td style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\"></td>
            <td style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\"></td>
            <td class=\"Rate\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <h2 style=\"font-size: .9em;\">Total</h2>
            </td>
            <td class=\"payment\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <h2 style=\"font-size: .9em;\">Tk ".$TotalPrice."</h2>
            </td>
        </tr>

        </table>
    </div>
    <!--End Table-->

    
    <div id=\"legalcopy\" style=\"\">
        <p class=\"legal\" style=\"font-size: .7em;color: #666;line-height: 1.2em;width: 70%;\"><strong>Thank you for your Purchase!</strong>  Cash on delivery! Free delivery in BUET
        </p>
    </div>
    <br>
    <div style=\"padding: 2px;border: 2px dashed #666;\"></div>
    <br>
    
    <div id=\"invoice-bot\" style=\"min-height: 250px;\">
    <div class=\"title\" style=\"display: block;\">
            <h1 style=\"font-size: 1.5em;color: #222;\">Invoice #".$Invoice_Number."</h1>
            <p style=\"font-size: .7em;color: #666;line-height: 1.2em;text-align: left;\">Issued: Oct 31, 2018</br>
            Delivery Due: Oct 31, 2018</br>
            </p>
        </div>
    <div id=\"table\">
        <table style=\"width: 100%;border-collapse: collapse;\">
        <tr class=\"tabletitle\" style=\"padding: 1px;background: #EEE;\">
            <td class=\"item\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;width: 50%;\">
            <h2 style=\"font-size: .9em;\">Item Description</h2>
            </td>
            <td class=\"Hours\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <h2 style=\"font-size: .9em;\">Category</h2>
            </td>
            <td class=\"Hours\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <h2 style=\"font-size: .9em;\">Quantity</h2>
            </td>
            <td class=\"Rate\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <h2 style=\"font-size: .9em;\">Price</h2>
            </td>
            <td class=\"subtotal\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <h2 style=\"font-size: .9em;\">Sub-total</h2>
            </td>
        </tr>
    ";
foreach ($order as $value) {

$inv_msg .= "<tr class=\"service\" style=\"border: 1px solid #EEE;\">
        <td class=\"tableitem\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\">".$value["name"]."</p>
        </td>
        <td class=\"tableitem\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\">".$value["quality"]."</p>
        </td>
        <td class=\"tableitem\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\">".$value["quantity"]."</p>
        </td>
        <td class=\"tableitem\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\">Tk ".$value["price"]."</p>
        </td>
        <td class=\"tableitem\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\">Tk ".$value["price"]*$value["quantity"]."</p>
        </td>
        </tr>";

}

    $inv_msg .="
    <tr class=\"service\" style=\"border: 1px solid #EEE;\">
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
    </tr>

    <tr class=\"service\" style=\"border: 1px solid #EEE;\">
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
        <td class=\"tableitem\" style=\"padding: 12px;border: 1px solid #EEE;\">
            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\"></p>
        </td>
    </tr>

    <tr class=\"tabletitle\" style=\"padding: 5px;background: #EEE;\">
            <td style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\"></td>
            <td style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\"></td>
            <td style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\"></td>
            <td class=\"Rate\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <h2 style=\"font-size: .9em;\">Total</h2>
            </td>
            <td class=\"payment\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
            <h2 style=\"font-size: .9em;\">Tk ".$TotalPrice."</h2>
            </td>
        </tr>

        </table>
    </div>
    <!--End Table-->
    </div>
</div>
</div>";

$_SESSION['message'] = $inv_msg;

// Load HTML content
$dompdf->loadHtml($inv_msg);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
// $dompdf->stream();
// 1 -> download , 0-> preview
$dompdf->stream("kolpoBD_Invoice",array("Attachment"=>0));


?>