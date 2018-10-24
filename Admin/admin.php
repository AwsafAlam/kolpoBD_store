<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KolpoBD||Admin</title>

	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Oswald|Oxygen" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/blog/admin.css">
    <link rel="stylesheet" href="assets/css/blog/blog-styles.css">

    <?php 
    
    include $_SERVER['DOCUMENT_ROOT'].'/../phpincludes/auth.php';
    require_once './include/DbHandler.php';
    require_once './include/PassHash.php';
    require './libs/Slim/Slim.php';
    // require_once __DIR__.'/src/Facebook/autoload.php';
    
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

    session_start();

    ?>


</head>

<body>
    
    <section>

        <div class='content-wrap'>
            <div class="form-block">
            <?php
                if ($_SERVER['REQUEST_METHOD']=='POST'){
                    
                    $conn = new mysqli("localhost", "kolpobdc", "5NUl.2tru1T3-H", "kolpobdc_site");
                    $strings = "SELECT * FROM Admin";    
                    
                    $result = $conn->prepare($strings);
                    $result->execute();
                    $result->bind_result($u_id,$name,$pass);
                    // $posts = array();

                    while($result->fetch()) {       
                        $tmp = array();
                        $tmp["username"] = $name;
                        $tmp["password"] = $pass;
                    
                        if (password_verify($_POST['password'],$tmp['password'])){
                            // $id= $user['admin_id'];
                            // $res=mysqli_query($con,"SELECT * FROM admin WHERE admin_id='$id'");
                            // $userProfile = mysqli_fetch_array($res);
    
                            $_SESSION['message']="Hi ". $name;
                            $_SESSION['username']=$tmp['username'];
                            header("location: ./admin/home.php");
    
                        }
                        else{
                            $_SESSION['message']="You shall not pass!";
                        }

                        // array_push($posts, $tmp); 
                            
                    }
                    
                    echo "<h2>".$_SESSION['message']."</h2>";

                }
                else {
            ?>

                    <h1>Login</h1>
                    <form action="admin.php" method="post">
                        <input type="text" name="username" placeholder="Username"><br>
                        <input type="password" name="password" placeholder="Password">
                        <input type="submit" value="Login">
                    </form>
                </div>

            <?php
            }
            ?>

        </div>
    </section>
</body>
</html>