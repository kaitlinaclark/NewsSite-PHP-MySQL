<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
		<!-- Required meta tags from https://getbootstrap.com/docs/4.0/getting-started/introduction/-->
        <!--Majority of code for Bootstrap styling from http://www.newthinktank.com/2015/11/learn-bootstrap-one-video/-->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!--BOOTSTRAP CSS-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
        crossorigin="anonymous">
        <!--<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
        <!--Stylesheet-->
        <link rel="stylesheet" type="text/css" href="./stylesheet.css">
	<title>Login Page</title>
</head>

<body>
        <!--is someone logged in-->
        <?php
            //default= no files, login, sign up, home, sign in, no sign-out, no upload
            $login = "display:run-in;";
            $signup = "display:run-in;";
            $signin = "display:run-in;";
            $signout = "display:none;";
            $upload = "display:none;";
            $file_out = "";
            if(isset($_SESSION["username"])){
                //signed in= files, no login, no sign-up, home, no sign-in, sign-out, upload
                $login = "display:none;";
                $signup = "display:none;";
                $signin = "display:none;";
                $signout = "display:run-in;";
                $upload = "display:run-in;";
            }
        ?>
        <!--NAVIGATION BAR -->
        <header>
            <nav class="navbar navbar-light bg-light navbar-expand-md">
                <!-- Brand and toggle get grouped for better mobile display -->
                <!-- Button that toggles the navbar on and off on small screens -->
                <button type="button" class="navbar-toggler collapsed" data-toggle="collapse"
                data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <!-- Hides information from screen readers --> <span class="sr-only"></span>
                    <!-- Draws 3 bars in navbar button when in small mode -->&#x2630;</button>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="active nav-item">
                            <a href="/~kaitlinaclark/news/index.php" class="nav-link">Home <span class="sr-only">(current)</span></a></li>
                        <li class="nav-item">
                            <a style="<?php echo $login ?>" href="/~kaitlinaclark/news/login.php" class="nav-link" id="login">
                                Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a style="<?php echo $signout; ?>" href="/~kaitlinaclark/news/signout.php" class="nav-link" id="out">
                                Sign Out
                            </a>
                        </li>
                        <li class="nav-item">
                            <a style="<?php echo $signup; ?>" href="/~kaitlinaclark/news/signup.php" class="nav-link">
                                Sign Up
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="POST">
	<p>First Name: <input type="text" name="first" required></p>
    <p>Last Name: <input type="text" name="last" required></p>
    <p>New Username: <input type="text" name="username" required></p>
    <p>New Password: <input type="password" name="psw" required></p>
	<input type="submit" value="Submit">
	</form>
	<?php
        require 'connect.php';

        if(isset($_POST["username"]) & isset($_POST["psw"]) & isset($_POST["first"]) & isset($_POST["last"])){
            //insert user into MySQL db
               $query_new_user = $connect->prepare("insert into users set
                                                                    first_name=?,
                                                                    last_name=?,
                                                                    username=?,
                                                                    pwd=?,
                                                                    img_src=?");
                if(!$query_new_user){
                    printf("Query Prep Failed: %s \n", $connect->error);
                    exit;
                }
            
            //bind parameters to input values
            $query_new_user->bind_param('ssss', $first, $last, $current_user, $psw_hash, $img_src);
                $first = $_POST["first"];
                $last = $_POST["last"];
                $current_user = $_POST["username"];
                $psw_hash = password_hash($_POST["psw"], PASSWORD_DEFAULT);
                $img_src = "//placehold.it/150";

            //execute query
            $query_new_user->execute();

            //close query
            $query_new_user->close();

            //redirect to login page
            header("Location: http://ec2-18-191-196-37.us-east-2.compute.amazonaws.com/~kaitlinaclark/news/login.php");

        }
	
	?>
</body>

</html>
