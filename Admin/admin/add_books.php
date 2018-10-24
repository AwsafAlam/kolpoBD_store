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

     <?php include $_SERVER['DOCUMENT_ROOT'].'/../phpincludes/auth.php';
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
                            <a href="index.php"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
                        </li>
                        <li>
                            <!-- <a href="new-post.php"> <i class="menu-icon fa fa-th"></i>New Post </a> -->
                            <!-- <a href="stats.php"> <i class="menu-icon fa fa-bar-chart"></i>Stats </a> -->
                            <a href="book-info.php"> <i class="menu-icon fa fa-area-chart"></i>Book Info </a>
                            <a href="add_books.php"> <i class="menu-icon fa fa-area-chart"></i>Add Books </a>
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
                <div class='content-wrap'>
            <?php
            $err='';
            $_SESSION['message']='';

            if ($_SERVER['REQUEST_METHOD']=='POST'){

                $Book= $con->escape_string($_POST['Book']);
                $Edition= $con->escape_string($_POST['Edition']);

                $Dept= $con->escape_string($_POST['Dept']);
                $Sem= $con->escape_string($_POST['Sem']);

                $Tag_1= $con->escape_string($_POST['Tag_1']);
                $Tag_2= $con->escape_string($_POST['Tag_2']);
                $Tag_3= $con->escape_string($_POST['Tag_3']);
                $Tag_4= $con->escape_string($_POST['Tag_4']);
                $Tag_5= $con->escape_string($_POST['Tag_5']);
                $Tag_6= $con->escape_string($_POST['Tag_6']);
                $Tag_7= $con->escape_string($_POST['Tag_7']);
                $Tag_8= $con->escape_string($_POST['Tag_8']);
                $Tag_9= $con->escape_string($_POST['Tag_9']);
                $Tag_10= $con->escape_string($_POST['Tag_10']);
                $Tag_11= $con->escape_string($_POST['Tag_11']);
                // $Tag_12= $con->escape_string($_POST['Tag_12']);
                // $Tag_13= $con->escape_string($_POST['Tag_13']);
                // $Tag_14= $con->escape_string($_POST['Tag_14']);
                // $Tag_15= $con->escape_string($_POST['Tag_15']);
                // $Tag_16= $con->escape_string($_POST['Tag_16']);
                // $Tag_17= $con->escape_string($_POST['Tag_17']);
                // $Tag_18= $con->escape_string($_POST['Tag_18']);
                // $Tag_19= $con->escape_string($_POST['Tag_19']);
                // $Tag_20= $con->escape_string($_POST['Tag_20']);
                
                $Price_W= $con->escape_string($_POST['Price_W']);
                $Price_N= $con->escape_string($_POST['Price_N']);
                $Price_O= $con->escape_string($_POST['Price_O']);
                $Price_S= $con->escape_string($_POST['Price_S']);

                $Author_1= $con->escape_string($_POST['Author_1']);
                $Author_2= $con->escape_string($_POST['Author_2']);
                $Author_3= $con->escape_string($_POST['Author_3']);
                $Author_4= $con->escape_string($_POST['Author_4']);
                $Author_5= $con->escape_string($_POST['Author_5']);
                
                // $strings="INSERT INTO questions(title,userid,username,question,category,anonymous,notification,image,tags,userpic) VALUES (" . "'". $title . "'". "," . "'". $userid . "'". "," . "'". $username . "'". "," . "'". $question . "'". "," . "'". $category. "'" ."," ."'" . $anonymous. "'" . "," . "'". $imagecount. "'" . "," . "'". $imagenames. "'" . ",". "'". $tags . "'". "," . "'". $fbpic . "'".  ")";
                // $str= "INSERT INTO questions(username,question,category,notification,image) VALUES ( ";
                // $result = $conn->query($strings);

                if ($location = mysqli_prepare($con, "INSERT INTO Book (book_id, name , img) VALUES (?, ? , ?)")){
                    mysqli_stmt_bind_param($location, "sss", $id['bool_id'] , $Book , $id['bool_img']);
                    mysqli_stmt_execute($location);

                    $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
                    $strings = "SELECT book_id FROM Book WHERE name = '".$Book."'";    
                    
                    $result = $conn->prepare($strings);
                    $result->execute();
                    $result->bind_result($book_id);
                    // $posts = array();
                    $tmp = array();

                    while($result->fetch()) {       
                        $tmp["book_id"] = $book_id;
                    }

                    // $strings = "SELECT department_id FROM Department WHERE name = '".$Dept."'";    
                    
                    //     $result = $conn->prepare($strings);
                    //     $result->execute();
                    //     $result->bind_result($dept_id);
                    //     $tag = array();

                    //     while($result->fetch()) {       
                    //         $tag["dept_id"] = $dept_id;
                    //     }
                    
                    //     $strings = "SELECT semester_id FROM Semester WHERE number = '".$Sem."'";    
                    
                    //     $result = $conn->prepare($strings);
                    //     $result->execute();
                    //     $result->bind_result($sem_id);
                    //     // $posts = array();
                    //     //$tag = array();

                    //     while($result->fetch()) {       
                    //         $tag["sem_id"] = $sem_id;
                    //     }
                    
                    // $strings = "INSERT INTO BookSemester (id , university_id , department_id , semester_id) VALUES  (NULL, 1 ,'".$tag["dept_id"]."','".$tag["sem_id"]."')";    
                    // $result = $conn->prepare($strings);
                    // $result->execute();

                    $strings = "INSERT INTO BookEdition (id , book_id , edition_id) VALUES (NULL, '".$tmp["book_id"]."','".$Edition."')";    
                    $result = $conn->prepare($strings);
                    $result->execute();


                    if($Price_W != ""){
                        $strings = "INSERT INTO Price (price_id , book_id , quality_id , price) VALUES  (NULL, '".$tmp["book_id"]."', '1', '".$Price_W."')";    
                        $result = $conn->prepare($strings);
                        $result->execute();
                    }
                    if($Price_W != ""){
                        $strings = "INSERT INTO Price (price_id , book_id , quality_id , price) VALUES  (NULL, '".$tmp["book_id"]."', '2', '".$Price_N."')";    
                        $result = $conn->prepare($strings);
                        $result->execute();
                    }
                    if($Price_W != ""){
                        $strings = "INSERT INTO Price (price_id , book_id , quality_id , price) VALUES  (NULL, '".$tmp["book_id"]."', '3', '".$Price_O."')";    
                        $result = $conn->prepare($strings);
                        $result->execute();
                    }
                    if($Price_S != ""){
                        $strings = "INSERT INTO Price (price_id , book_id , quality_id , price) VALUES  (NULL, '".$tmp["book_id"]."', '4', '".$Price_S."')";    
                        $result = $conn->prepare($strings);
                        $result->execute();
                    }

                    if($Author_1 != ""){
                        $location = mysqli_prepare($con, "INSERT INTO Author (author_id, author_name) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['author_id'] , $Author_1);
                        mysqli_stmt_execute($location);

                        $strings = "SELECT author_id FROM Author WHERE author_name = '".$Author_1."'";    
                    
                        $result = $conn->prepare($strings);
                        $result->execute();
                        $result->bind_result($author_id);
                        // $posts = array();
                        $author = array();

                        while($result->fetch()) {       
                            $author["author_id"] = $author_id;
                        }
                        
                        $strings = "INSERT INTO BookAuthor (id, book_id , author_id) VALUES ( NULL,'".$tmp["book_id"]."' , '".$author["author_id"]."'";    
                    
                        $result = $conn->prepare($strings);
                        $result->execute();

                        
                    }
                    if($Author_2 != ""){
                        $location = mysqli_prepare($con, "INSERT INTO Author (author_id, author_name) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['author_id'] , $Author_2);
                        mysqli_stmt_execute($location);

                        $strings = "SELECT author_id FROM Author WHERE author_name = '".$Author_2."'";    
                    
                        $result = $conn->prepare($strings);
                        $result->execute();
                        $result->bind_result($author_id);
                        // $posts = array();
                        $author = array();

                        while($result->fetch()) {       
                            $author["author_id"] = $author_id;
                        }
                        
                        $location = mysqli_prepare($con, "INSERT INTO BookAuthor (id, book_id , author_id) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['id'] , $tmp["book_id"] , $author["author_id"]);
                        mysqli_stmt_execute($location);
                    }
                    if($Author_3 != ""){
                        $location = mysqli_prepare($con, "INSERT INTO Author (author_id, author_name) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['author_id'] , $Author_3);
                        mysqli_stmt_execute($location);
                    
                        $strings = "SELECT author_id FROM Author WHERE author_name = '".$Author_3."'";    
                    
                        $result = $conn->prepare($strings);
                        $result->execute();
                        $result->bind_result($author_id);
                        // $posts = array();
                        $author = array();

                        while($result->fetch()) {       
                            $author["author_id"] = $author_id;
                        }
                        
                        $location = mysqli_prepare($con, "INSERT INTO BookAuthor (id, book_id , author_id) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['id'] , $tmp["book_id"] , $author["author_id"]);
                        mysqli_stmt_execute($location);
                    }
                    if($Author_4 != ""){
                        $location = mysqli_prepare($con, "INSERT INTO Author (author_id, author_name) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['author_id'] , $Author_4);
                        mysqli_stmt_execute($location);

                        $strings = "SELECT author_id FROM Author WHERE author_name = '".$Author_4."'";    
                    
                        $result = $conn->prepare($strings);
                        $result->execute();
                        $result->bind_result($author_id);
                        // $posts = array();
                        $author = array();

                        while($result->fetch()) {       
                            $author["author_id"] = $author_id;
                        }
                        
                        $location = mysqli_prepare($con, "INSERT INTO BookAuthor (id, book_id , author_id) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['id'] , $tmp["book_id"] , $author["author_id"]);
                        mysqli_stmt_execute($location);
                    }
                    if($Author_5 != ""){
                        $location = mysqli_prepare($con, "INSERT INTO Author (author_id, author_name) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['author_id'] , $Author_5);
                        mysqli_stmt_execute($location);

                        $strings = "SELECT author_id FROM Author WHERE author_name = '".$Author_5."'";    
                    
                        $result = $conn->prepare($strings);
                        $result->execute();
                        $result->bind_result($author_id);
                        // $posts = array();
                        $author = array();

                        while($result->fetch()) {       
                            $author["author_id"] = $author_id;
                        }
                        
                        $location = mysqli_prepare($con, "INSERT INTO BookAuthor (id, book_id , author_id) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['id'] , $tmp["book_id"] , $author["author_id"]);
                        mysqli_stmt_execute($location);
                    }
                    
                    if($Tag_1 != ""){
                        $location = mysqli_prepare($con, "INSERT INTO Tag (Tag_id, Tag_name) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['Tag_id'] , $Tag_1);
                        mysqli_stmt_execute($location);

                        $strings = "SELECT tag_id FROM Tag WHERE tag_name = '".$Tag_1."'";    
                    
                        $result = $conn->prepare($strings);
                        $result->execute();
                        $result->bind_result($tag_id);
                        // $posts = array();
                        $tag = array();

                        while($result->fetch()) {       
                            $tag["tag_id"] = $tag_id;
                        }
                        $strings = "INSERT INTO BookTag (id, tag_id , book_id) VALUES ( NULL,'".$tag["tag_id"]."' , '".$author["book_id"]."'";    
                        $result = $conn->prepare($strings);
                        $result->execute();

                    }
                    if($Tag_2 != ""){
                        $location = mysqli_prepare($con, "INSERT INTO Tag (Tag_id, Tag_name) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['Tag_id'] , $Tag_2);
                        mysqli_stmt_execute($location);

                        $strings = "SELECT tag_id FROM Tag WHERE tag_name = '".$Tag_2."'";    
                    
                        $result = $conn->prepare($strings);
                        $result->execute();
                        $result->bind_result($tag_id);
                        // $posts = array();
                        $tag = array();

                        while($result->fetch()) {       
                            $tag["tag_id"] = $tag_id;
                        }
                        
                        $location = mysqli_prepare($con, "INSERT INTO BookTag (id, tag_id , book_id) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['id'] , $tag["tag_id"] , $author["book_id"]);
                        mysqli_stmt_execute($location);
                    }
                    if($Tag_3 != ""){
                        $location = mysqli_prepare($con, "INSERT INTO Tag (Tag_id, Tag_name) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['Tag_id'] , $Tag_3);
                        mysqli_stmt_execute($location);

                        $strings = "SELECT tag_id FROM Tag WHERE tag_name = '".$Tag_3."'";    
                    
                        $result = $conn->prepare($strings);
                        $result->execute();
                        $result->bind_result($tag_id);
                        // $posts = array();
                        $tag = array();

                        while($result->fetch()) {       
                            $tag["tag_id"] = $tag_id;
                        }
                        
                        $location = mysqli_prepare($con, "INSERT INTO BookTag (id, tag_id , book_id) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['id'] , $tag["tag_id"] , $author["book_id"]);
                        mysqli_stmt_execute($location);
                    }
                    if($Tag_4 != ""){
                        $location = mysqli_prepare($con, "INSERT INTO Tag (Tag_id, Tag_name) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['Tag_id'] , $Tag_4);
                        mysqli_stmt_execute($location);

                        $strings = "SELECT tag_id FROM Tag WHERE tag_name = '".$Tag_4."'";    
                    
                        $result = $conn->prepare($strings);
                        $result->execute();
                        $result->bind_result($tag_id);
                        // $posts = array();
                        $tag = array();

                        while($result->fetch()) {       
                            $tag["tag_id"] = $tag_id;
                        }
                        
                        $location = mysqli_prepare($con, "INSERT INTO BookTag (id, tag_id , book_id) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['id'] , $tag["tag_id"] , $author["book_id"]);
                        mysqli_stmt_execute($location);
                    }
                    if($Tag_5 != ""){
                        $location = mysqli_prepare($con, "INSERT INTO Tag (Tag_id, Tag_name) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['Tag_id'] , $Tag_5);
                        mysqli_stmt_execute($location);

                        $strings = "SELECT tag_id FROM Tag WHERE tag_name = '".$Tag_5."'";    
                    
                        $result = $conn->prepare($strings);
                        $result->execute();
                        $result->bind_result($tag_id);
                        // $posts = array();
                        $tag = array();

                        while($result->fetch()) {       
                            $tag["tag_id"] = $tag_id;
                        }
                        
                        $location = mysqli_prepare($con, "INSERT INTO BookTag (id, tag_id , book_id) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['id'] , $tag["tag_id"] , $author["book_id"]);
                        mysqli_stmt_execute($location);
                    }
                    if($Tag_6 != ""){
                        $location = mysqli_prepare($con, "INSERT INTO Tag (Tag_id, Tag_name) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['Tag_id'] , $Tag_6);
                        mysqli_stmt_execute($location);

                        $strings = "SELECT tag_id FROM Tag WHERE tag_name = '".$Tag_6."'";    
                    
                        $result = $conn->prepare($strings);
                        $result->execute();
                        $result->bind_result($tag_id);
                        // $posts = array();
                        $tag = array();

                        while($result->fetch()) {       
                            $tag["tag_id"] = $tag_id;
                        }
                        
                        $location = mysqli_prepare($con, "INSERT INTO BookTag (id, tag_id , book_id) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['id'] , $tag["tag_id"] , $author["book_id"]);
                        mysqli_stmt_execute($location);
                    }
                    if($Tag_7 != ""){
                        $location = mysqli_prepare($con, "INSERT INTO Tag (Tag_id, Tag_name) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['Tag_id'] , $Tag_7);
                        mysqli_stmt_execute($location);

                        $strings = "SELECT tag_id FROM Tag WHERE tag_name = '".$Tag_7."'";    
                    
                        $result = $conn->prepare($strings);
                        $result->execute();
                        $result->bind_result($tag_id);
                        // $posts = array();
                        $tag = array();

                        while($result->fetch()) {       
                            $tag["tag_id"] = $tag_id;
                        }
                        
                        $location = mysqli_prepare($con, "INSERT INTO BookTag (id, tag_id , book_id) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['id'] , $tag["tag_id"] , $author["book_id"]);
                        mysqli_stmt_execute($location);
                    }
                    if($Tag_8 != ""){
                        $location = mysqli_prepare($con, "INSERT INTO Tag (Tag_id, Tag_name) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['Tag_id'] , $Tag_8);
                        mysqli_stmt_execute($location);

                        $strings = "SELECT tag_id FROM Tag WHERE tag_name = '".$Tag_8."'";    
                    
                        $result = $conn->prepare($strings);
                        $result->execute();
                        $result->bind_result($tag_id);
                        // $posts = array();
                        $tag = array();

                        while($result->fetch()) {       
                            $tag["tag_id"] = $tag_id;
                        }
                        
                        $location = mysqli_prepare($con, "INSERT INTO BookTag (id, tag_id , book_id) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['id'] , $tag["tag_id"] , $author["book_id"]);
                        mysqli_stmt_execute($location);
                    }
                    if($Tag_9 != ""){
                        $location = mysqli_prepare($con, "INSERT INTO Tag (Tag_id, Tag_name) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['Tag_id'] , $Tag_9);
                        mysqli_stmt_execute($location);

                        $strings = "SELECT tag_id FROM Tag WHERE tag_name = '".$Tag_9."'";    
                    
                        $result = $conn->prepare($strings);
                        $result->execute();
                        $result->bind_result($tag_id);
                        // $posts = array();
                        $tag = array();

                        while($result->fetch()) {       
                            $tag["tag_id"] = $tag_id;
                        }
                        
                        $location = mysqli_prepare($con, "INSERT INTO BookTag (id, tag_id , book_id) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['id'] , $tag["tag_id"] , $author["book_id"]);
                        mysqli_stmt_execute($location);
                    }
                    if($Tag_10 != ""){
                        $location = mysqli_prepare($con, "INSERT INTO Tag (Tag_id, Tag_name) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['Tag_id'] , $Tag_10);
                        mysqli_stmt_execute($location);

                        $strings = "SELECT tag_id FROM Tag WHERE tag_name = '".$Tag_10."'";    
                    
                        $result = $conn->prepare($strings);
                        $result->execute();
                        $result->bind_result($tag_id);
                        // $posts = array();
                        $tag = array();

                        while($result->fetch()) {       
                            $tag["tag_id"] = $tag_id;
                        }
                        
                        $location = mysqli_prepare($con, "INSERT INTO BookTag (id, tag_id , book_id) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['id'] , $tag["tag_id"] , $author["book_id"]);
                        mysqli_stmt_execute($location);
                    }
                    if($Tag_11 != ""){
                        $location = mysqli_prepare($con, "INSERT INTO Tag (Tag_id, Tag_name) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['Tag_id'] , $Tag_11);
                        mysqli_stmt_execute($location);

                        $strings = "SELECT tag_id FROM Tag WHERE tag_name = '".$Tag_11."'";    
                    
                        $result = $conn->prepare($strings);
                        $result->execute();
                        $result->bind_result($tag_id);
                        // $posts = array();
                        $tag = array();

                        while($result->fetch()) {       
                            $tag["tag_id"] = $tag_id;
                        }
                        
                        $location = mysqli_prepare($con, "INSERT INTO BookTag (id, tag_id , book_id) VALUES (?, ?)");
                        mysqli_stmt_bind_param($location, "sss", $id['id'] , $tag["tag_id"] , $author["book_id"]);
                        mysqli_stmt_execute($location);
                    }
                    
                    
                    $_SESSION['message']="Add Book Successful!";
                }
            }
            else{
            ?>

            <div class="form-block">
                <h1>Add Book ( Try to fill all the fields )</h1>
                <form action="add_books.php" method="post">
                    <input type="text" name="Book" placeholder="Book Name"><br>
                    <h5>If edition is unknown leave blank , 1 for 1st edition, 2 for 2nd, so on...</h5>
                    <input type="number" name="Edition" placeholder="Edition"><br>
                    <h5>EEE , CSE , MME .. be careful to write only official abbreviation "only in BLOCK Letters"</h5><br>
                    <input type="text" name="Dept" placeholder="Department"><br>
                    <h5>1 for L1T1 , 2 for L2T2 and so on</h5><br>
                    <input type="number" name="Sem" placeholder="Semester"><br>
                    <h4>Write as many tags you can think of, this will help in search. e.g for Sadiku book -> you can add Node Analysis, Semiconductorn etc tags </h4><br>
                    
                    <input type="text" name="Tag_1" placeholder="Tag"><br>
                    <input type="text" name="Tag_2" placeholder="Tag"><br>
                    <input type="text" name="Tag_3" placeholder="Tag"><br>
                    <input type="text" name="Tag_4" placeholder="Tag"><br>
                    <input type="text" name="Tag_5" placeholder="Tag"><br>
                    <input type="text" name="Tag_6" placeholder="Tag"><br>
                    <input type="text" name="Tag_7" placeholder="Tag"><br>
                    <input type="text" name="Tag_8" placeholder="Tag"><br>
                    <input type="text" name="Tag_9" placeholder="Tag"><br>
                    <input type="text" name="Tag_10" placeholder="Tag"><br>
                    <input type="text" name="Tag_11" placeholder="Tag"><br>
                    <!-- <input type="text" name="Tag_12" placeholder="Tag"><br>
                    <input type="text" name="Tag_13" placeholder="Tag"><br>
                    <input type="text" name="Tag_14" placeholder="Tag"><br>
                    <input type="text" name="Tag_15" placeholder="Tag"><br>
                    <input type="text" name="Tag_16" placeholder="Tag"><br>
                    <input type="text" name="Tag_17" placeholder="Tag"><br>
                    <input type="text" name="Tag_18" placeholder="Tag"><br>
                    <input type="text" name="Tag_19" placeholder="Tag"><br>
                    <input type="text" name="Tag_20" placeholder="Tag"><br> -->
                    <br>
                    <br>
                    <br>
                    <input type="number" name="Price_W" placeholder="Price White Print"><br>
                    <input type="number" name="Price_N" placeholder="Price News Print"><br>
                    <input type="number" name="Price_O" placeholder="Price Original Print"><br>
                    <input type="number" name="Price_S" placeholder="Price Second hand Print"><br>
                    <br>
                    <input type="text" name="Author_1" placeholder="Author 1"><br>
                    <input type="text" name="Author_2" placeholder="Author 2"><br>
                    <input type="text" name="Author_3" placeholder="Author 3"><br>
                    <input type="text" name="Author_4" placeholder="Author 4"><br>
                    <input type="text" name="Author_5" placeholder="Author 5"><br>
                    
                    <a style="color: red;"><?php echo $err;?></a>
                    <input class="button" type="submit" value="Add">
                </form>

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
