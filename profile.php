<?php
session_start();
?>
<!DOCTYPE html>
<html lang="eng">
    
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
        <title>User Profile</title>
    </head>
    
    <body>
        <!--is someone logged in-->
        <?php
            require 'connect.php';
		    //default= login, sign up, home, no sign-out
            $login = "display:run-in;";
            $signup = "display:run-in;";
            $signout = "display:none;";
            $create = "display:none;";
            $change_image = "display:none;"; 
            $my_profile = "display:none;";
                
            //check if someone is signed in
            if(isset($_SESSION["username"])){

                //signed in= no login, no sign-up, home, sign-out, create
                $login = "display:none;";
                $signup = "display:none;";
                $signout = "display:run-in;";
                $create = "display:run-in;";
                $my_profile = "display:run-in;";

                $username = $_SESSION["username"];
                echo "Hello ".$username.", you are now logged in!";
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
        <div class="container">
            <div class="row my-2">
                <div class="col-lg-8 order-lg-2">
                    <div class="tab-content py-4">
                        <div class="tab-pane active" id="profile">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="mt-2"><span class="fa fa-clock-o ion-clock float-right"></span>Stories</h5>
                                    <table class="table table-sm table-hover table-striped">
                                        <tbody>                                    
                                            <?php
                                                //check to see if id is in url
                                                if(isset($_GET["User"])){

                                                    //check if someone is logged in
                                                    if(isset($_SESSION["user_id"])){
                                                        if((int)$_GET["User"] === $_SESSION["user_id"]){
                                                            //permits user to change photo
                                                            $change_image = "display:run-in;";
                                                            $user_id = $_SESSION["user_id"];
                                                            $_SESSION["tmp_user"] = $user_id;
                                                        }
                                                        //tmp_user given in url
                                                        else{
                                                            $user_id = $_GET["User"];
                                                            $_SESSION["tmp_user"] = $user_id;
                                                        }
                                                    }
                                                    //tmp_user given in url
                                                    else{
                                                        $user_id = $_GET["User"];
                                                        $_SESSION["tmp_user"] = $user_id;
                                                    }
                                                }
                                                //ELSE; tmp_user already exists as something else
                                                else{
                                                    $user_id = $_SESSION["tmp_user"];
                                                }
                                                
                                                $query_user_articles = $connect->prepare("select story_id,
                                                                                                title,
                                                                                                date_posted,
                                                                                                users.first_name,
                                                                                                users.last_name,
                                                                                                users.img_src
                                                                                                from stories
                                                                                                join users on (users.user_id = stories.user_id)
                                                                                                    where stories.user_id=?");
                                                if(!$query_user_articles){
                                                    printf("Query Prep Failed: %s \n", $connect->error);
                                                    exit;
                                                }
                                                $query_user_articles->bind_param('s', $user_id);
                                                $query_user_articles->execute();
                                                $query_user_articles->bind_result($story_id, $title, $date, $first, $last, $img_src);

                                                while($query_user_articles->fetch()){
                                                    printf('<tr>
                                                                <td>
                                                                    <a href="/~kaitlinaclark/news/view_story.php?Story=%s" class="badge badge-light">
                                                                    <strong>%s</strong> Created %s</a>
                                                                </td>
                                                            </tr>', $story_id, $title, $date);
                                                }

                                                $query_user_articles->close();
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 order-lg-1 text-center">
                    <h1 class="mb-3"><?php printf('%s %s', $first, $last); ?></h1>
                    <img src="<?php echo $img_src; ?>" class="mx-auto img-fluid img-circle d-block" alt="avatar">
                        <form name="upload_image" enctype="multipart/form-data" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" style="<?php echo $change_image; ?>">
                            <h3 class="mt-2">Upload a different photo</h3>
                                <p><input name="uploaded_image" type="file" /></p>
                                <input type="hidden" name="MAX_FILE_SIZE" value="20000000000000" />
                                <input type="submit" value="Select New Profile Picture" />
                        </form>
                </div>
            </div>
        </div>
        <?php
        //FROM WIKI
        // Get the username and make sure it is valid
            if(isset($_FILES['uploaded_image']['name'])){
                // Get the filename and make sure it is valid
                $filename = basename($_FILES['uploaded_image']['name']);
                if( !preg_match('/^[\w_\.\-]+$/', $filename) ){
                    echo "Invalid filename";
                    exit;
                }

                
                $full_path = sprintf("/srv/uploads/%s", $filename);
                if( move_uploaded_file($_FILES['uploaded_image']['tmp_name'], $full_path) ){
                    
                    $query_change_image = $connect->prepare("update users 
                                                                set img_src=? 
                                                                where user_id=?");
                    if(!$query_change_image){
                        printf("Query Prep Failed: %s \n", $connect->error);
                        exit;
                    } 

                    $img_src = "http://ec2-18-191-196-37.us-east-2.compute.amazonaws.com/".$filename;
                    $query_change_image->bind_param("ss", $img_src, $_SESSION["user_id"]);
                    $query_change_image->execute();
                    $query_change_image->close();
                    exit;
                }else{
                    echo $_FILES['uploaded_image']['error']."\n\r".$_FILES['uploaded_image']['tmp_name'];
                    exit;
                }  
                
            }

    ?>
    </body>
    </html>