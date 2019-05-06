 <?php
session_start();
		require 'connect.php';
		$story_id=$_POST["story_id"];
		if(isset($_POST["story_title"]) & isset($_POST["story_text"])){
                //insert story into MySQL db
                $query_edited_story = $connect->prepare("update stories 
														set stories.title=?,
														stories.story=?
														where story_id=?");
                if(!$query_edited_story){
                    printf("Query Prep Failed: %s \n", $connect->error);
                    exit;
                }
            
                //bind parameters to input values
                $query_edited_story->bind_param("ssi", $title, $story, $story_id);
					$title = $_POST["story_title"];
                    $story = $_POST["story_text"];
                //execute query
                $query_edited_story->execute();

                //close query
                $query_edited_story->close();

            //redirect to index page
            header("Location: http://ec2-18-191-196-37.us-east-2.compute.amazonaws.com/~kaitlinaclark/news/index.php");
        }		
?>