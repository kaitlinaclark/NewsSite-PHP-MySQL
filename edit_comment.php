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
	<title>Edit Comment</title>
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
                $my_profile = "display:run-in";

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
    <?php
        require 'connect.php';
		date_default_timezone_set('America/Chicago');
		$comment_id = (int) $_GET['Comment'];
		$query_all_comments = $connect->prepare("select comment
															from comments
																where comment_id=?");
                if(!$query_all_comments){
                    printf("Query Prep Failed: %s \n", $connect->error);
                    exit;
                }
                $query_all_comments->bind_param("i", $comment_id);
                $query_all_comments->execute();
                $query_all_comments->bind_result($temp_comment);

                //display all comments for this article
                while($query_all_comments->fetch()){
                    $comment=$temp_comment;
                }

                //close query for all comments
                $query_all_comments->close();


			
		
        
	
	?>
	
	<div class="jumbotron" style="<?php echo $comments; ?>">
                    <div class="form-group">
                        <form name="add_comment" action="edit_comment_operation.php" method="POST">
                        <strong>Edit Comment</strong>
                        <p><textarea rows="7" cols="30" name="comment_text"><?php echo $comment; ?></textarea></p>
                            <p>
                                <input type="submit" value="Edit Comment" />
                            </p>
							<input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>">
                        </form>
                    </div>
                </div>


	</b>
</header>