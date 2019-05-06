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
        <title>News Site</title>
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
            $my_stories = "display:none;";
                
            //check if someone is signed in
            if(isset($_SESSION["username"])){

                //signed in= no login, no sign-up, home, sign-out, create
                $login = "display:none;";
                $signup = "display:none;";
                $signout = "display:run-in;";
                $create = "display:run-in;";
                $my_stories = "display:run-in;";

                $username = $_SESSION["username"];
                echo "Hello ".$username.", you are now logged in!";
                
                /************************************************************************ */
                //display delete/edit option for current_user's stories using MySQL query//
                /************************************************************************ */

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
                            <a href="/~tavisreed/news/index.php" class="nav-link">Home <span class="sr-only">(current)</span></a></li>
                        <li class="nav-item">
                            <a style="<?php echo $login ?>" href="/~tavisreed/news/login.php" class="nav-link" id="login">
                                Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a style="<?php echo $create; ?>"href="/~tavisreed/news/create_story.php" class="nav-link" id="create">
                                Create Story
                            </a>
                        </li>
                        <li class="nav-item">
                            <a style="<?php echo $signout; ?>" href="/~tavisreed/news/signout.php" class="nav-link" id="out">
                                Sign Out
                            </a>
                        </li>
                        <li class="nav-item">
                            <a style="<?php echo $signup; ?>" href="/~tavisreed/news/signup.php" class="nav-link">
                                Sign Up
                            </a>
                        </li>
                        <li class="nav-item">
                            <a style="<?php echo $my_stories; ?>" href="/~tavisreed/news/my_stories.php" class="nav-link">
                                My Stories
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        
        <!--ARTICLES-->
        <div class="container">
            <!--view-source:https://blackrockdigital.github.io/startbootstrap-blog-home/-->
            <div class="row">
            <?php 
                /***************************** */
                //display user's files using MySQL query//
                /**************************** */
                //query author first and last name, title, article text, image, and date
                $query_my_stories = $connect->prepare("select users.first_name, 
                                                            users.last_name, 
                                                            stories.story_id,
                                                            stories.title,  
                                                            stories.date_posted 
                                                            from stories
                                                                join users on (users.user_id = stories.user_id)
                                                            where stories.user_id=?");
		        if(!$query_my_stories){
			        printf("Query Prep Failed: %s \n", $connect->error);
			        exit;
                }
                
                //execute and bind
                $query_my_stories->bind_param('i', $_SESSION["user_id"]);
                $query_my_stories->execute();
                $query_my_stories->bind_result($first_name, $last_name, $story_id, $title, $date);

                
                
                while($query_my_stories->fetch()){
                    //generate captions and article links
                    $edit_article_link = 'http://ec2-18-191-203-217.us-east-2.compute.amazonaws.com/~tavisreed/news/edit_story.php?Story='.$story_id;
                    $delete_article_link = 'http://ec2-18-191-203-217.us-east-2.compute.amazonaws.com/~tavisreed/news/delete_story.php?Story='.$story_id;
                    //display article
                    printf('<div class="card mb-4">
                                <div class="card-body">
                                    <h2 class="card-title">%s</h2>
                                    <a href="%s" class="btn btn-primary">Edit Article</a>
                                    <a href="%s" class="btn btn-primary">Delete Article</a>
                                </div>
                                <div class="card-footer text-muted">
                                    Posted on %s
                                </div>
                            </div>', $title, $edit_article_link, $delete_article_link, $date);

                }
                
                //close query for all articles
                $query_my_stories->close();
            ?>

            </div>
        </div>

        <!--FOOTER-->
        <nav class="navbar fixed-bottom navbar-light bg-light navbar-expand-md">
            <p>The Illest Site Ever...100</p>
        </nav>
    </body>

</html>