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
	<title>Create Story</title>
</head>

<body>
<!--is someone logged in-->
<?php
            //default= login, sign up, home, no sign-out
            $login = "display:run-in;";
            $signup = "display:run-in;";
            $signout = "display:none;";
            $create = "display:none;";
            $my_profile = "display:none;";
            if(isset($_SESSION["username"])){
                //signed in= no login, no sign-up, home, sign-out
                $login = "display:none;";
                $signup = "display:none;";
                $signout = "display:run-in;";
                $create = "display:run-in;";
                $my_profile = "display:run-in;";

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
                            <a style="<?php echo $create; ?>"href="/~kaitlinaclark/news/create_story.php" class="nav-link" id="create">
                                Create Story
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
                        <li class="nav-item">
                            <a style="<?php echo $my_profile; ?>" href="/~kaitlinaclark/news/profile.php?User=<?php echo $_SESSION["user_id"]; ?>"  class="nav-link">
                                My Profile
                            </a>
                        </li> 
                    </ul>
                </div>
            </nav>
        </header>
	<h1> Create New Story </h1>
	<form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="POST">
	<p>Story Title: <input type="text" name="story_title" required></p>
    <p>Story:<textarea rows="4" cols="50" name="story_text">
		Type your story here.
    </textarea></p>
    <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
	<input type="submit" value="Submit">
	</form>
    <?php
        require 'connect.php';
        
        if(isset($_POST["story_title"]) && isset($_POST["story_text"])){
            //check for CSRF
            if(!hash_equals($_SESSION['token'], $_POST['token'])){
                die("Request forgery detected");
            }
            
                //insert story into MySQL db
                $query_new_story = $connect->prepare("insert into stories set
                                                                    user_id=?,
                                                                    title=?,
                                                                    story=?,
                                                                    date_posted=?");
                if(!$query_new_story){
                    printf("Query Prep Failed: %s \n", $connect->error);
                    exit;
                }
            
                //bind parameters to input values
                $query_new_story->bind_param('isss', $user_id, $title, $story, $date);
                    $user_id = $_SESSION["user_id"];
                    $title = $_POST["story_title"];
                    $story = $_POST["story_text"];
                    $date = date("Y-m-d", time());

                //execute query
                $query_new_story->execute();

                //close query
                $query_new_story->close();

            //redirect to index page
            header("Location: http://ec2-18-191-196-37.us-east-2.compute.amazonaws.com/~kaitlinaclark/news/index.php");
        }
	
	?>
    </body>
</html>