<?php

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
                
                $inv_msg = " <div id=\"invoiceholder\" style=\"width: 100%;hieght: 100%;padding: 150px;\">
                <div id=\"invoice\" class=\"effect2\" style=\"position: relative;top: -290px;margin: 0 auto;width: 700px;background: #FFF;\">
              
                  <div id=\"invoice-top\" style=\"min-height: 90px; margin: 25px; margin-bottom: 0px;\">
                    <div class=\"logo\" style=\"float: left;height: 60px; width: 60px;background: url(./images/logo2.png) no-repeat;background-size: 60px 60px;\"></div>
                    <div class=\"info\" style=\"display: block;float: left;margin-left: 20px;\">
                      <h2 style=\"font-size: .9em;\">KolpoBD</h2>
                      <p style=\"font-size: .7em;color: #666;line-height: 1.2em;\">info@kolpobd.com</br> 
                        01630246627</br>
                      </p>
                    </div>
                    <!--End Info-->
                    <div class=\"title\" style=\"float: right;\">
                      <h1 style=\"font-size: 1.5em;color: #222;\">Invoice #".$Invoice_Number."</h1>
                      <p style=\"font-size: .7em;color: #666;line-height: 1.2em;text-align: right;\">Issued: Oct 31, 2018</br>
                        Delivery Due: Oct 31, 2018</br>
                      </p>
                    </div>
                    <!--End Title-->
                  </div>
                  <!--End InvoiceTop-->
              
                  <div id=\"invoice-mid\" style=\"min-height: 120px;\">
              
                    <div class=\"info\" style=\"display: block;float: left;margin-left: 20px;\">
                      <h2 style=\"font-size: .9em;\">".$user_name."</h2>
                      <p style=\"font-size: .7em;color: #666;line-height: 1.2em;\">".$mobile_no."</br>
                        ".$address."</br>
                    </p></div>
              
                  </div>
                  <!--End Invoice Mid-->
              
                  <div id=\"invoice-bot\" style=\"min-height: 250px;\">
              
                    <div id=\"table\">
                      <table style=\"width: 100%;border-collapse: collapse;\">
                        <tr class=\"tabletitle\" style=\"padding: 5px;background: #EEE;\">
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
                            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\">৳ ".$value["price"]."</p>
                          </td>
                          <td class=\"tableitem\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
                            <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\">৳ ".$value["price"]*$value["quantity"]."</p>
                          </td>
                        </tr>";
              
                }

                $inv_msg .= "<tr class=\"tabletitle\" style=\"padding: 5px;background: #EEE;\">
                          <td style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\"></td>
                          <td style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\"></td>
                          <td style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\"></td>
                          <td class=\"Rate\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
                            <h2 style=\"font-size: .9em;\">Total</h2>
                          </td>
                          <td class=\"payment\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
                            <h2 style=\"font-size: .9em;\">৳ ".$TotalPrice."</h2>
                          </td>
                        </tr>
              
                      </table>
                    </div>
                    <!--End Table-->
              
                    <form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\" target=\"_top\" style=\"float: right;margin-top: 30px;text-align: right;\">
                      <input type=\"hidden\" name=\"cmd\" value=\"_s-xclick\">
                      <input type=\"hidden\" name=\"hosted_button_id\" value=\"QRZ7QTM9XRPJ6\">
                      
                    </form>
              
                    <div id=\"legalcopy\" style=\"margin-top: 30px;\">
                      <p class=\"legal\" style=\"font-size: .7em;color: #666;line-height: 1.2em;width: 70%;\"><strong>Thank you for your Purchase!</strong>  Cash on delivery! Free delivery in BUET
                      </p>
                    </div>
                    </br>
                    </br>
                    </br>
                    <div> Store Copy </div>
                    <div id=\"invoice-bot\" style=\"min-height: 250px;\">
              
                    <div id=\"table\">
                      <table style=\"width: 100%;border-collapse: collapse;\">
                        <tr class=\"tabletitle\" style=\"padding: 5px;background: #EEE;\">
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
                          <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\">৳ ".$value["price"]."</p>
                        </td>
                        <td class=\"tableitem\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
                          <p class=\"itemtext\" style=\"font-size: .9em;color: #666;line-height: 1.2em;\">৳ ".$value["price"]*$value["quantity"]."</p>
                        </td>
                      </tr>";
            
              }
              
                    $inv_msg .=" 
                    <tr class=\"tabletitle\" style=\"padding: 5px;background: #EEE;\">
                          <td style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\"></td>
                          <td style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\"></td>
                          <td style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\"></td>
                          <td class=\"Rate\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
                            <h2 style=\"font-size: .9em;\">Total</h2>
                          </td>
                          <td class=\"payment\" style=\"padding: 5px 0 5px 15px;border: 1px solid #EEE;\">
                            <h2 style=\"font-size: .9em;\">৳ ".$TotalPrice."</h2>
                          </td>
                        </tr>
              
                      </table>
                    </div>
                    <!--End Table-->
                    </div>
                </div>
              </div>";



    require("fpdf/fpdf.php");
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont("Arial","I",12);
    $pdf->Cell(180,20,"Welcome {$name} To KOLPOBD",1,1,'C');
    $pdf->Cell(180,20,"Book Ordered : $books",1,1);
    $pdf->Cell(180,20,"Delivery Location : $address",1,1);

    $pdf->output();

?>