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
                            <!-- <a href="new-post.php"> <i class="menu-icon fa fa-th"></i>New Post </a> -->
                            <a href="book_info.php"> <i class="menu-icon fa fa-area-chart"></i>Book Info </a>
                            <a href="add_books.php"> <i class="menu-icon fa fa-area-chart"></i>Add Books </a>
                            <a href="order.php"> <i class="menu-icon fa fa-bar-chart"></i>Orders</a>
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
            <div class="row">
                <div class='col-sm-8'>
            <?php
            $err='';
            $_SESSION['message']='';

            if ($_SERVER['REQUEST_METHOD']=='POST'){

                
                $book = $_POST['name'];
                $author = $_POST['author'];
              
                $Edition = $_POST['Edition'];
              
                $Price_W = $_POST['Price_W'];
                $Price_N = $_POST['Price_N'];
                $Price_O = $_POST['Price_O'];
              
                $Dept = $_POST['dept'];
                $Sem = $_POST['sem'];
                
                $Author_2= $_POST['Author_2'];
                $Author_3= $_POST['Author_3'];
                $Author_4= $_POST['Author_4'];
                $Author_5= $_POST['Author_5'];
                
                // $Tag_1= $con->escape_string($_POST['Tag_1']);
                // $Tag_2= $con->escape_string($_POST['Tag_2']);
                // $Tag_3= $con->escape_string($_POST['Tag_3']);
                // $Tag_4= $con->escape_string($_POST['Tag_4']);
                // $Tag_5= $con->escape_string($_POST['Tag_5']);
                // $Tag_6= $con->escape_string($_POST['Tag_6']);
                // $Tag_7= $con->escape_string($_POST['Tag_7']);
                // $Tag_8= $con->escape_string($_POST['Tag_8']);
                // $Tag_9= $con->escape_string($_POST['Tag_9']);
                // $Tag_10= $con->escape_string($_POST['Tag_10']);
                // $Tag_11= $con->escape_string($_POST['Tag_11']);
                
                
                $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");

                $strings ="INSERT INTO Book (book_id , name) VALUES (NULL , '".$book."')";
                $result = $conn->query($strings);
                
                $strings ="SELECT book_id FROM Book WHERE name = '".$book."'";
                
                $result = $conn->prepare($strings);
                $result->execute();
                $result->bind_result($book_id);
                $tmp = array();

                while($result->fetch()) {       
                    $tmp["book_id"] = $book_id;
                }
                $result->close();
                
                $strings ="INSERT INTO BookEdition (id ,book_id , edition_id) VALUES (NULL , '".$tmp["book_id"]."' , '".$Edition."')";
                $result = $conn->query($strings);

                $strings ="INSERT INTO Price (price_id ,book_id , quality_id , price) VALUES (NULL , '".$tmp["book_id"]."' ,'1' ,'".$Price_W."')";
                $result = $conn->query($strings);

                $strings ="INSERT INTO Price (price_id ,book_id , quality_id , price) VALUES (NULL , '".$tmp["book_id"]."' ,'2' ,'".$Price_N."')";
                $result = $conn->query($strings);

                $strings ="INSERT INTO Price (price_id ,book_id , quality_id , price) VALUES (NULL , '".$tmp["book_id"]."' ,'3' ,'".$Price_O."')";
                $result = $conn->query($strings);

                $strings ="INSERT INTO BookSemester (id ,university_id, department_id, semester_id , book_id) VALUES (NULL , '1' ,'".$Dept."' ,'".$Sem."', '".$tmp["book_id"]."')";
                $result = $conn->query($strings);
                

                $strings ="INSERT INTO Author (author_id , author_name ) VALUES (NULL , '".$author."')";
                $result = $conn->query($strings);
                
                $strings ="SELECT author_id FROM Author WHERE author_name = '".$author."'";
                
                $result = $conn->prepare($strings);
                $result->execute();
                $result->bind_result($id);
                
                while($result->fetch()) {       
                    $tmp["author_id"] = $id;
                }
                $result->close();

                $strings ="INSERT INTO BookAuthor (id ,book_id ,author_id) VALUES (NULL , '".$tmp["book_id"]."' , '".$tmp["author_id"]."')";
                $result = $conn->query($strings);
                
                if($Author_2 != ""){
                    $strings ="INSERT INTO Author (author_id , author_name ) VALUES (NULL , '".$Author_2."')";
                    $result = $conn->query($strings);
                    
                    $strings ="SELECT author_id FROM Author WHERE author_name = '".$Author_2."'";
                    
                    $result = $conn->prepare($strings);
                    $result->execute();
                    $result->bind_result($id);
                    
                    while($result->fetch()) {       
                        $tmp["author_id"] = $id;
                    }
                    $result->close();
    
                    $strings ="INSERT INTO BookAuthor (id ,book_id ,author_id) VALUES (NULL , '".$tmp["book_id"]."' , '".$tmp["author_id"]."')";
                    $result = $conn->query($strings);
                }
                if($Author_3 != ""){
                    $strings ="INSERT INTO Author (author_id , author_name ) VALUES (NULL , '".$Author_3."')";
                    $result = $conn->query($strings);
                    
                    $strings ="SELECT author_id FROM Author WHERE author_name = '".$Author_3."'";
                    
                    $result = $conn->prepare($strings);
                    $result->execute();
                    $result->bind_result($id);
                    
                    while($result->fetch()) {       
                        $tmp["author_id"] = $id;
                    }
                    $result->close();
    
                    $strings ="INSERT INTO BookAuthor (id ,book_id ,author_id) VALUES (NULL , '".$tmp["book_id"]."' , '".$tmp["author_id"]."')";
                    $result = $conn->query($strings);
                }
                if($Author_4 != ""){
                    $strings ="INSERT INTO Author (author_id , author_name ) VALUES (NULL , '".$Author_4."')";
                    $result = $conn->query($strings);
                    
                    $strings ="SELECT author_id FROM Author WHERE author_name = '".$Author_4."'";
                    
                    $result = $conn->prepare($strings);
                    $result->execute();
                    $result->bind_result($id);
                    
                    while($result->fetch()) {       
                        $tmp["author_id"] = $id;
                    }
                    $result->close();
    
                    $strings ="INSERT INTO BookAuthor (id ,book_id ,author_id) VALUES (NULL , '".$tmp["book_id"]."' , '".$tmp["author_id"]."')";
                    $result = $conn->query($strings);
                }
                if($Author_5 != ""){
                    $strings ="INSERT INTO Author (author_id , author_name ) VALUES (NULL , '".$Author_5."')";
                    $result = $conn->query($strings);
                    
                    $strings ="SELECT author_id FROM Author WHERE author_name = '".$Author_5."'";
                    
                    $result = $conn->prepare($strings);
                    $result->execute();
                    $result->bind_result($id);
                    
                    while($result->fetch()) {       
                        $tmp["author_id"] = $id;
                    }
                    $result->close();
    
                    $strings ="INSERT INTO BookAuthor (id ,book_id ,author_id) VALUES (NULL , '".$tmp["book_id"]."' , '".$tmp["author_id"]."')";
                    $result = $conn->query($strings);
                }
                

                $Price_S = $Price_N * 0.5;
                $strings ="INSERT INTO Price (price_id ,book_id , quality_id , price) VALUES (NULL , '".$tmp["book_id"]."' ,'4' ,'".$Price_S."')";
                $result = $conn->query($strings);
                
                
                $_SESSION['message']="Add Book Successful!";
                
            }
            else{
            ?>

<form action="add_books.php" method="post">
  <div class="form-group">
    <label>Book Name</label>
    <input type="text" class="form-control" aria-describedby="Book Name" name="name" placeholder="Book Name"><br>
    <small id="emailHelp" class="form-text text-muted">Only Enter the book Name</small>
  </div>
  <div class="form-group">
    <label for="exampleInputEdition">Edition</label>
    <select name="Edition" class="form-control">
        <option>Select Edition.<option>
        <option></option>
        <option value="1">1st Edition</option>
        <option value="2">2nd Edition</option>
        <option value="3">3rd Edition</option>
        <option value="4">4th Edition</option>
        <option value="5">5th Edition</option>
        <option value="6">6th Edition</option>
        <option value="7">7th Edition</option>
        <option value="8">8th Edition</option>
        <option value="9">9th Edition</option>
        <option value="10">10th Edition</option>
        <option value="11">11th Edition</option>
        <option value="12">12th Edition</option>
        <option value="13">13th Edition</option>
        <option value="14">14th Edition</option>
        <option value="15">15th Edition</option>
        <option value="16">16th Edition</option>
        <option value="17">17th Edition</option>
        <option value="18">18th Edition</option>
        <option value="19">19th Edition</option>
        <option value="20">20th Edition</option>

    </select>
    <!-- <input type="number" name="Edition" class="form-control" placeholder="Edition"><br> -->
  </div>
    <div class="form-group">
    <select name="dept" class="form-control">
        <option>Select Department</option>
        <option value="1">CSE</option>
        <option value="2">EEE</option>
        <option value="3">Chemical</option>
        <option value="4">BME</option>
        <option value="5">ME</option>
        <option value="6">IPE</option>
        <option value="7">Architecture</option>
        <option value="8">Civil</option>
        <option value="9">MME</option>
        <option value="10">NAME</option>
        <option value="11">URP</option>
        <option value="12">WRE</option>
    </select>
    </div>
    <div class="form-group">
    <select name="sem" class="form-control">
        <option value="1">L1 T1</option>
        <option value="2">L1 T2</option>
        <option value="3">L2 T1</option>
        <option value="4">L2 T2</option>
        <option value="5">L3 T1</option>
        <option value="6">L3 T2</option>
        <option value="7">L4 T1</option>
        <option value="8">L4 T2</option>
        <option value="9">L5 T1</option>
        <option value="10">L5 T2</option>
    </select>
        
    </div>
    <div class="form-group">
        <label>Price White Print</label>
        <input type="number" name="Price_W" class="form-control" placeholder="Price White Print">
    </div>
    <div class="form-group">
        <input type="number" name="Price_N" class="form-control" placeholder="Price News Print"><br>
    </div>
    <div class="form-group">
        <input class="form-control" type="number" name="Price_O" placeholder="Price Original Print"><br>
    </div>
    <div class="form-group">
        <label>Best Known Author of Book. (Mandatory)</label>
        <input type="text" class="form-control" name="author" placeholder="author">
    </div>
    <div class="form-group">
        <input type="text" name="Author_2" class="form-control" placeholder="Author 2"><br>
    </div>
    <div class="form-group">
        <input type="text" name="Author_3" class="form-control" placeholder="Author 3"><br>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="Author_4" placeholder="Author 4"><br>
    </div>
    <div class="form-group">
        <input type="text" name="Author_5" class="form-control" placeholder="Author 5"><br>
    </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

                    
                    
                    <!-- <input type="text" name="Tag_1" placeholder="Tag"><br>
                    <input type="text" name="Tag_2" placeholder="Tag"><br>
                    <input type="text" name="Tag_3" placeholder="Tag"><br>
                    <input type="text" name="Tag_4" placeholder="Tag"><br>
                    <input type="text" name="Tag_5" placeholder="Tag"><br>
                    <input type="text" name="Tag_6" placeholder="Tag"><br>
                    <input type="text" name="Tag_7" placeholder="Tag"><br>
                    <input type="text" name="Tag_8" placeholder="Tag"><br>
                    <input type="text" name="Tag_9" placeholder="Tag"><br>
                    <input type="text" name="Tag_10" placeholder="Tag"><br>
                    <input type="text" name="Tag_11" placeholder="Tag"><br> -->
                    <!-- <input type="text" name="Tag_12" placeholder="Tag"><br>
                    <input type="text" name="Tag_13" placeholder="Tag"><br>
                    <input type="text" name="Tag_14" placeholder="Tag"><br>
                    <input type="text" name="Tag_15" placeholder="Tag"><br>
                    <input type="text" name="Tag_16" placeholder="Tag"><br>
                    <input type="text" name="Tag_17" placeholder="Tag"><br>
                    <input type="text" name="Tag_18" placeholder="Tag"><br>
                    <input type="text" name="Tag_19" placeholder="Tag"><br>
                    <input type="text" name="Tag_20" placeholder="Tag"><br> -->
    
           
            </div>
            <?php
            }
            echo "<h1>".$_SESSION['message']."</h1>";
            ?>

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
        $(document).ready(function() {
          $('#bootstrap-data-table-export').DataTable();
        } );
    </script>


</body>
</html>
