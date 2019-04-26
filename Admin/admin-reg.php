<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KolpoBd Admin</title>

	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Oswald|Oxygen" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/blog/blog-styles.css">


    <?php include $_SERVER['DOCUMENT_ROOT'].'/../phpincludes/auth.php';

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
            <?php
            $valid=false;
            $err='';
            $_SESSION['message']='';

            if ($_SERVER['REQUEST_METHOD']=='POST'){


                $username= $con->escape_string($_POST['username']);
                $password= $con->escape_string(password_hash($_POST['password'],PASSWORD_BCRYPT));

                $valid=true;
                $num=0;
                // if ($location = mysqli_prepare($con, "SELECT * FROM Admin WHERE 1")){
                //     mysqli_stmt_bind_param($location, "s", $username);
                //     mysqli_stmt_execute($location);
                //     $result = mysqli_stmt_get_result($location);
                //     $num2 = mysqli_num_rows($result);
                // }
                
                // if ($num2>0){
                //     $err="The username already exists!";
                //     $valid=false;
                // }
                // else
                if (strlen($password)<5){
                    $err="Please use a stronger password.";
                    $valid=false;
                }




                if ($valid){
                    if ($location = mysqli_prepare($con, "INSERT INTO Admin (username, password, admin_id) VALUES (?, ?, ?)")){
                        mysqli_stmt_bind_param($location, "sss", $username,$password, $id['admin_id']);
                        mysqli_stmt_execute($location);

                        $_SESSION['message']="Registration Successful!";

                    }

                }



            }
            if (!$valid){
            ?>

            <div class="form-block">
                <h1>Register</h1>
                <form action="admin-reg.php" method="post">
                    <input type="text" name="username" placeholder="Username"><br>
                    <input type="password" name="password" placeholder="Password"><br>

                    <a style="color: red;"><?php echo $err;?></a>
                    <input class="button" type="submit" value="Register">
                </form>

            </div>
            <?php
            }
            echo "<h1>".$_SESSION['message']."</h1>";
            ?>

        </div>
    </section>
</body>
</html>