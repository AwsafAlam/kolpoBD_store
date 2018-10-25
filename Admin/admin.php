<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KolpoBD||Admin</title>

	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
    <style>
    

body{
  margin: 0px;
  padding: 0px;
  background: #1abc9d;
}

h1{
  color: #fff;
  text-align: center;
  font-family: Arial;
  font-weight: normal;
  margin: 2em auto 0px;
}
.outer-screen{
  background: #13202c;
  width: 900px;
  height: 540px;
  margin: 50px auto;
  border-radius: 20px;
  -moz-border-radius: 20px;
  -webkit-border-radius: 20px;
  position: relative;
  padding-top: 35px;
}

.outer-screen:before{
  content: "";
  background: #3e4a53;
  border-radius: 50px;
  position: absolute;
  bottom: 20px;
  left: 0px;
  right: 0px;
  margin: auto;
  z-index: 9999;
  width: 50px;
  height: 50px;
}
.outer-screen:after{
  content: "";
  background: #ecf0f1;
  width: 900px;
  height: 88px;
  position: absolute;
  bottom: 0px;
  border-radius: 0px 0px 20px 20px;
  -moz-border-radius: 0px 0px 20px 20px;
  -webkit-border-radius: 0px 0px 20px 20px;
}

.stand{
  position: relative;  
}

.stand:before{
  content: "";
  position: absolute;
  bottom: -150px;
  border-bottom: 150px solid #bdc3c7;
  border-left: 30px solid transparent;
  border-right: 30px solid transparent;
  width: 200px;
  left: 0px;
  right: 0px;
  margin: auto;
}

.stand:after{
  content: "";
  position: absolute;
  width: 260px;
  left: 0px;
  right: 0px;
  margin: auto;
  border-bottom: 30px solid #bdc3c7;
  border-left: 30px solid transparent;
  border-right: 30px solid transparent;
  bottom: -180px;
  box-shadow: 0px 4px 0px #7e7e7e
}

.inner-screen{
  width: 800px;
  height: 340px;
  background: #1abc9d;
  margin: 0px auto;
  padding-top: 80px;
}

.form{
  width: 400px;
  height: 230px;
  background: #edeff1;
  margin: 0px auto;
  padding-top: 20px;
  border-radius: 10px;
  -moz-border-radius: 10px;
  -webkit-border-radius: 10px;
}

input[type="text"],
input[type="password"]
{
  display: block;
  width: 309px;
  height: 35px;
  margin: 15px auto;
  background: #fff;
  border: 0px;
  padding: 5px;
  font-size: 16px;
   border: 2px solid #fff;
  transition: all 0.3s ease;
  border-radius: 5px;
  -moz-border-radius: 5px;
  -webkit-border-radius: 5px;
}

input[type="text"]:focus{
  border: 2px solid #1abc9d
}

input[type="submit"]{
  display: block;
  background: #1abc9d;
  width: 314px;
  padding: 12px;
  cursor: pointer;
  color: #fff;
  border: 0px;
  margin: auto;
  border-radius: 5px;
  -moz-border-radius: 5px;
  -webkit-border-radius: 5px;
  font-size: 17px;
  transition: all 0.3s ease;
}

input[type="submit"]:hover{
  background: #09cca6
}

a{
  text-align: center;
  font-family: Arial;
  color: gray;
  display: block;
  margin: 15px auto;
  text-decoration: none;
  transition: all 0.3s ease;
  font-size: 12px;
}

a:hover{
  color: #1abc9d;
}


::-webkit-input-placeholder {
   color: gray;
}

:-moz-placeholder { /* Firefox 18- */
   color: gray;  
}

::-moz-placeholder {  /* Firefox 19+ */
   color: gray;  
}

:-ms-input-placeholder {  
   color: gray;  
}
    </style>
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

                  

                <!-- NEw -->
            <h1>Kolpo BD</h1>
            <div class="stand">
            <div class="outer-screen">
                <div class="inner-screen">
                <div class="form">
                <form action="admin.php" method="post">
                    <input type="text" class="zocial-dribbble" name="username" placeholder="Username" />
                    <input type="password" name="password" placeholder="Password" />
                    <input type="submit" value="Login" />
                </form>
                </div> 
                </div> 
            </div> 
            </div>
  
            <?php
            }
            ?>

    </section>
</body>
</html>