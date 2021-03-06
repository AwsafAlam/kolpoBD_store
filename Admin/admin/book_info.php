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
        //   $strings = "SELECT Book.book_id ,Book.name , Quality.quality_category,Price.price,Price.price_id
        //   FROM Book,BookAuthor,Author,Price,Quality where Book.book_id = BookAuthor.book_id
        //   and BookAuthor.author_id = Author.author_id and Price.book_id = Book.book_id and Price.quality_id = Quality.quality_id ORDER BY Price.price_id";
        
        $strings = "SELECT Book.book_id ,Book.name ,Author.author_name , Quality.quality_category,Price.price,Price.price_id
        FROM Book JOIN BookAuthor ON (Book.book_id = BookAuthor.book_id)
        JOIN Author ON (Author.author_id = BookAuthor.author_id)
        JOIN Price ON (Price.book_id = Book.book_id)
        JOIN Quality On (Price.quality_id = Quality.quality_id)
        ORDER BY Book.book_id , Price.price_id";

        //   $result = $conn->prepare($strings);
               
                
          $result = $conn->prepare($strings);
          $result->execute();
          $result->bind_result($book_id, $name,$author , $quality_category,$price, $price_id);
          $posts = array();

          while($result->fetch()) {
          ?>
            <tr>
              <td> <?php echo $book_id;?></td>
              <td> <?php echo $name;?></td>
              <td> <?php echo $author;?></td>
              <td> <?php echo $quality_category;?></td>
              <td> <?php echo $price;?></td>
              <td> <?php echo $price_id;?></td>
              
            </tr>
          <?php } 
          $result->close();
          ?>

        </table>
      </div>
  </div>

      <div class="box box-primary container">
      <div class="box-header with-border">
      <br><br><br><h4 class="box-title">Update Book Price. (Must fill all the fields)</h4><br><br>
      </div>
      <!-- /.box-header -->
      <!-- form start -->
      <?php
            $err='';
            $_SESSION['message']='';

            if ($_SERVER['REQUEST_METHOD']=='POST'){

                
                $book = $_POST['book_id'];
                
                $Price_W = $_POST['Price_W'];
                $Price_N = $_POST['Price_N'];
                // $Price_O = $_POST['Price_O'];
              
                
                $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
                // $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_devtesting");

                $strings = "UPDATE Price
                SET Price.price = '".$Price_W."'
                where Price.book_id = '".$book."'
                and Price.quality_id = 1";

                // $strings ="INSERT INTO Book (book_id , name) VALUES (NULL , '".$book."')";

                $result = $conn->query($strings);
               
                $strings2 = "UPDATE Price
                SET Price.price = '".$Price_N."'
                where Price.book_id = '".$book."'
                and Price.quality_id = 2";
                $result = $conn->query($strings2);


                // $result->close();
                
                
                $_SESSION['message']="Price Update Successful!";
                
            }
            else{
            ?>

      <form action="book_info.php" method="post">
        <div class="box-body">
          <div class="form-row">
          <div class="form-group col-md-4">
            <label for="exampleInputEmail1">Book_id</label>
            <input type="number" class="form-control" name="book_id" placeholder="Enter Book_id here" required="true">
          </div>
          <div class="form-group  col-md-4">
            <label for="exampleInputEmail1">News Print</label>
            <input type="text" class="form-control" name="Price_N" placeholder="updated News Print price" required="true">
          </div>
          <div class="form-group  col-md-4">
            <label for="exampleInputEmail1">White Print</label>
            <input type="text" class="form-control" name="Price_W" placeholder="Updated White Print Price" required="true">
          </div>
          </div>
          <?php
            }
            echo "<h1>".$_SESSION['message']."</h1>";
            ?>
        </div>
        </div>
        <!-- /.box-body -->

        <div class="box-footer" style="padding-left: 30px; padding-bottom: 20px;">
          <button type="submit" class="btn btn-primary">Submit</button>

        </div>
      </form>
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
