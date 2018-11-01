<!doctype html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Kolpo Admin</title>
    <meta name="description" content="kolpoBD Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <link rel="stylesheet" href="assets/css/lib/datatable/dataTables.bootstrap.min.css">
    <!-- <link rel="stylesheet" href="assets/css/bootstrap-select.less"> -->
    <link rel="stylesheet" href="assets/scss/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->

     <?php 
     include $_SERVER['DOCUMENT_ROOT'].'/../phpincludes/auth.php';
    require_once '../include/DbHandler.php';
    require_once '../include/PassHash.php';
    require '../libs/Slim/Slim.php';

    //include autoloader

    // require_once './dompdf/autoload.inc.php';

    // reference the Dompdf namespace

    // use Dompdf\Dompdf;

    //initialize dompdf class

    // $document = new Dompdf();

    
    \Slim\Slim::registerAutoloader();
    
    $app = new \Slim\Slim();
    
    
    $user_id = NULL;
    
    
    function authenticate(\Slim\Route $route)
    {
        // Getting request headers
        $headers = apache_request_headers();
        $response = array();
        $app = \Slim\Slim::getInstance();
    
        // Verifying Authorization Header
        if (isset($headers['Authorization'])) {
            $db = new DbHandler();
    
            // get the api key
            $api_key = $headers['Authorization'];
            // validating api key
            if (!$db->isValidApiKey($api_key)) {
                // api key is not present in users table
                $response["error"] = true;
                $response["message"] = "Access Denied. Invalid Api key";
                echoRespnse(401, $response);
                $app->stop();
            } else {
                global $user_id;
                // get user primary key id
                $user_id = $db->getUserId($api_key);
            }
        } else {
            // api key is missing in header
            $response["error"] = true;
            $response["message"] = "Api key is misssing";
            echoRespnse(400, $response);
            $app->stop();
        }
    }
    if (mysqli_connect_errno()) {
        echo "COULD NOT CONNECT TO DATABASE";
        exit();
    }

  
    ?>
</head>
<body>
        <!-- Left Panel -->

        <aside id="left-panel" class="left-panel">
            <nav class="navbar navbar-expand-sm navbar-default">

                <div class="navbar-header">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fa fa-bars"></i>
                    </button>
                    <a class="navbar-brand" href="./"><img src="images/logo.png" alt="Logo"></a>
                    <a class="navbar-brand hidden" href="./"><img src="images/logo2.png" alt="Logo"></a>
                </div>

                <div id="main-menu" class="main-menu collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li class="active">
                            <a href="home.php"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
                        </li>
                        <li>
                            <!-- <a href="new-post.php"> <i class="menu-icon fa fa-th"></i>New Post </a> -->
                            <a href="book_info.php"> <i class="menu-icon fa fa-area-chart"></i>Book Info </a>
                            <a href="add_books.php"> <i class="menu-icon fa fa-area-chart"></i>Add Books </a>
                            <a href="order.php"> <i class="menu-icon fa fa-bar-chart"></i>Create Order</a>
                            <a href="delivery_status.php"> <i class="menu-icon fa fa-th"></i>Delivery Status</a>
                            </li>

                    </ul>
                </div><!-- /.navbar-collapse -->
            </nav>
        </aside><!-- /#left-panel -->

        <!-- Right Panel -->

        <div id="right-panel" class="right-panel">

            <!-- Header-->
            <header id="header" class="header">

                <div class="header-menu">

                    <div class="col-sm-7">
                        <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>

                    </div>

                    <div class="col-sm-5">
                        <div class="user-area dropdown float-right">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="user-avatar rounded-circle" src="images/admin.jpg" alt="User Avatar">
                            </a>

                            <div class="user-menu dropdown-menu">
                                <a class="nav-link" href="#"><i class="fa fa- user"></i>My Profile</a>
                                <a class="nav-link" href="logout.php"><i class="fa fa-power -off"></i>Logout</a>
                            </div>
                        </div>



                    </div>
                </div>

            </header><!-- /header -->
            <!-- Header-->



        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Dashboard</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Dashboard</a></li>
                            <li><a href="#">Table</a></li>
                            <li class="active">Data table</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- Main row -->
      <div class="row">

<div class="col-xs-12">
  <div class="box">
      <div class="box-header">
        <h3 class="box-title">Data Table With Full Features</h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table-bordered table-hover" id="Emptable" class="table table-bordered table-striped">
          <thead class="thead-dark">
          <tr>
            <th>Book ID</th>
            <th>Book Name</th>
            <th>Author Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Price ID</th>
          </tr>
          </thead>
          <?php
          $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
          // $conn = new mysqli("localhost", "root", "", "kolpbdc_site");
          
          # There can be multiple authors
          $strings = "SELECT Book.book_id ,Book.name , Author.author_name , Quality.quality_category,Price.price,Price.price_id
          FROM Book,BookAuthor,Author,Price,Quality where Book.book_id = BookAuthor.book_id
          and BookAuthor.author_id = Author.author_id and Price.book_id = Book.book_id and Price.quality_id = Quality.quality_id ORDER BY Price.price_id";
        
          $result = $conn->prepare($strings);
               
                
          $result = $conn->prepare($strings);
          $result->execute();
          $result->bind_result($book_id, $name , $author_name , $quality_category,$price, $price_id);
          $posts = array();
          $i = 0;

          while($result->fetch()) {
          ?>
            <tr <?php echo "id=".$i?>>
              <td> <?php echo $book_id;?></td>
              <td> <?php echo $name;?></td>
              <td> <?php echo $author_name;?></td>
              <td> <?php echo $quality_category;?></td>
              <td> <?php echo $price;?></td>
              <td> <?php echo $price_id;?></td>
              
            </tr>
          <?php $i++; } 
          $result->close();
          ?>

        </table>
      </div>
  </div>

      <div class="box box-primary container">
      <div class="box-header with-border">
      <br><br><br><h4 class="box-title">Add new Order</h4><br><br>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <?php
            $err='';
            $_SESSION['message']='';

            if ($_SERVER['REQUEST_METHOD']=='POST'){

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

              $_SESSION['message'] = $inv_msg;
                
            }
            else{
            ?>

      <form action="order.php" method="post">
        <div class="box-body">
          <div class="form-row">
          <div class="form-group col-md-4">
            <label for="exampleInputEmail1">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username" required="true">
          </div>
          <div class="form-group  col-md-4">
            <label for="exampleInputEmail1">Mobile</label>
            <input type="text" class="form-control" name="mobile" placeholder="Mobile" required="true">
          </div>
          </div>
          <div class="form-row">
          <div class="form-group  col-md-9">
            <label for="exampleInputEmail1">Delivery Address</label>
            <input type="text" class="form-control" name="delivery_address" placeholder="Delivery Address" required="true">
          </div>
          </div>
          <!-- <div class="form-row">
            <div class="form-group  col-md-3">
                <a href="" id="Add" class="btn btn-primary">Add Book to order</a>
            </div>
          </div> -->
        <?php
            for ($i=0; $i < 10; $i++) { 
                # code...
            ?>

                <div class="form-row">
                <div class="form-group  col-md-4">
                  <label for="exampleInputEmail1">Book Id</label>
                  <input type="number" class="form-control" name="<?php echo "book_id".$i;?>" placeholder="Book ID" >
                </div>
                <div class="form-group  col-md-3">
                <label for="exampleInputEmail1">Quality</label>
                  <select name="<?php echo "quality_id".$i;?>" class="form-control">
                      <option>Select Quality</option>
                      <option value="1">News Print</option>
                      <option value="2">White Print</option>
                      <option value="3">Original</option>
                      <option value="4">Second Hand</option>
                  </select>  
                 </div>
                <div class="form-group  col-md-3">
                  <label for="exampleInputEmail1">Quantity</label>
                  <input type="number" class="form-control" name="<?php echo "quantity".$i;?>" placeholder="Quantity of book">
                </div>
              </div>

        <?php

            }
        ?>
        </div>
        </div>
        <!-- /.box-body -->

        <div class="box-footer" style="padding-left: 30px; padding-bottom: 20px;">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
      <?php
        }
        echo $_SESSION['message'];

        // $document->loadHtml($_SESSION['message']);
        // $document->setPaper('A4' , 'landscape');

        // $document->render();
        // $document->stream()

        ?>

</div>
</div>

</div>
      
        </div><!-- .animated -->
    </div><!-- .content -->


    </div><!-- /#right-panel -->

    <!-- Right Panel -->


    <script src="assets/js/vendor/jquery-2.1.4.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>


    <script src="assets/js/lib/data-table/datatables.min.js"></script>
    <script src="assets/js/lib/data-table/dataTables.bootstrap.min.js"></script>
    <script src="assets/js/lib/data-table/dataTables.buttons.min.js"></script>
    <script src="assets/js/lib/data-table/buttons.bootstrap.min.js"></script>
    <script src="assets/js/lib/data-table/jszip.min.js"></script>
    <script src="assets/js/lib/data-table/pdfmake.min.js"></script>
    <script src="assets/js/lib/data-table/vfs_fonts.js"></script>
    <script src="assets/js/lib/data-table/buttons.html5.min.js"></script>
    <script src="assets/js/lib/data-table/buttons.print.min.js"></script>
    <script src="assets/js/lib/data-table/buttons.colVis.min.js"></script>
    <script src="assets/js/lib/data-table/datatables-init.js"></script>


 
<script type="text/javascript">
$(document).ready(function () {
  // body...

 console.log("Here");
 $('#Emptable').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : [[ 1, "asc" ]],
      'info'        : true,
      'autoWidth'   : true
    });



});


</script>




</body>
</html>
