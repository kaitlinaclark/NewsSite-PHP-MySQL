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
		    //default= login, sign up, home, sign in, no sign-out, no comment
            $login = "display:run-in;";
            $signup = "display:run-in;";
            $signout = "display:none;";
            $comments = "display:none;";
            $create = "display:none;";
            $my_profile = "display:none;";
            if(isset($_SESSION["username"])){
                //signed in= no login, no sign-up, home, sign-out, comment
                $login = "display:none;";
                $signup = "display:none;";
                $signout = "display:run-in;";
                $comments = "display:run-in;";
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
        
        <!--ARTICLES-->
        <div class="container">
            <!--Display Article-->
            <?php 
			    require 'connect.php';
                //query story from db
                $story_id = (int) $_GET["Story"];
                $_SESSION["story_id"] = $story_id;
                $query_article = $connect->prepare("select stories.title, 
                                                        stories.story, 
                                                        stories.date_posted,
                                                        users.first_name,
                                                        users.last_name, 
                                                        users.user_id from users
                                                        join stories on (users.user_id = stories.user_id)
                                                            where story_id=?");
                if(!$query_article){
                    printf("Query Prep Failed: %s \n", $connect->error);
                    exit;
                }
                $query_article->bind_param('i', $story_id);
                $query_article->execute();
                $query_article->bind_result($title, $story, $date, $first_name, $last_name, $user_id);
                
                //display article
                while($query_article->fetch()){
					printf('<div class="card mb-12">
                            <div class="card-body">
                                <h2 class="card-title">%s</h2>
                                <p class="card-text">%s</p>
                            </div>
                            <div class="card-footer text-muted">
                                Posted on %s by
                                <a href="/~kaitlinaclark/news/profile.php?User=%s">%s %s</a>
                            </div>
                        </div>', $title, $story, $date, $user_id, $first_name, $last_name);
				}

                //close query for article
                $query_article->close();
                ?>

                <!--Display Add Comment Form-->
                <div class="jumbotron" style="<?php echo $comments; ?>">
                    <div class="form-group">
                        <form name="add_comment" action="add_comment.php" method="POST">
                        <strong>Add Comment</strong>
                        <p><textarea rows="7" cols="30" name="comment_text"></textarea></p>
                            <p>
                                <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                                <input type="submit" value="Add Comment" />
                            </p>
                        </form>
                    </div>
                </div>

                <?php
				require 'connect.php';
                ///Display Comments
                $query_all_comments = $connect->prepare("select comment_id,
                                                                comments.user_id,
                                                                comment,
                                                                comments.date_posted,
                                                                users.first_name,
                                                                users.last_name
                                                                from comments
                                                                join users on (comments.user_id = users.user_id)
                                                                    where story_id=?");
                if(!$query_all_comments){
                    printf("Query Prep Failed: %s \n", $connect->error);
                    exit;
                }
                $query_all_comments->bind_param("i", $story_id);
                $query_all_comments->execute();
                $query_all_comments->bind_result($comment_id, $user_id, $comment, $comment_date, $comment_first, $comment_last);

                //display all comments for this article
                while($query_all_comments->fetch()){

                    if(isset($_SESSION["user_id"])){
                        if($_SESSION["user_id"] === (int)$user_id){
                            //edit and delete links
                            $delete_comment_link = 'http://ec2-18-191-196-37.us-east-2.compute.amazonaws.com/~kaitlinaclark/news/delete.php?Comment='.$comment_id;
                            $edit_comment_link = 'http://ec2-18-191-196-37.us-east-2.compute.amazonaws.com/~kaitlinaclark/news/edit_comment.php?Comment='.$comment_id;
                            printf('<div class="card-body">
                                <p class="text-muted">Posted on %s by %s %s</p>
                                <p>%s</p>
                                <a href="%s" class="btn btn-link">Delete</a>
                                <a href="%s" class="btn btn-link">Edit</a>
                                
                                    </div>', $comment_date, $comment_first, $comment_last, $comment, $delete_comment_link, $edit_comment_link);
                        }
                        else{
                            printf('<div class="card-body">
                                        <p class="text-muted">Posted on %s by %s %s</p>
                                        <p>%s</p>
                                    </div>', $comment_date, $comment_first, $comment_last, $comment);
                        }
                    }
                    else{
                        printf('<div class="card-body">
                                    <p class="text-muted">Posted on %s by %s %s</p>
                                    <p>%s</p>
                                </div>', $comment_date, $comment_first, $comment_last, $comment);
                    }
                }

                //close query for all comments
                $query_all_comments->close();
            ?>

        </div>
    </body>

</html>