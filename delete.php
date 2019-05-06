 <?php
session_start();
		require 'connect.php';
		
			if(isset($_GET['Story'])){
				
			$story_id = (int) $_GET['Story'];
			//First delete all comments associated with the story
			$query_all_comments = $connect->prepare("delete from comments where story_id=?");
			if(!$query_all_comments){
				printf("Query Prep Failed: %s \n", $connect->error);
				exit;
			}
			$query_all_comments->bind_param("i", $story_id);
			$query_all_comments->execute();
			$query_all_comments->close();
			
			
			//close query for all comments
			$query_all_comments->close();

			$query_delete_article = $connect->prepare("delete from stories where story_id=?");
			if(!$query_delete_article){
				printf("Query Prep Failed: %s \n", $connect->error);
				exit;
			}
			$query_delete_article->bind_param('i', $story_id);
			$query_delete_article->execute();
			//close query for article
			$query_delete_article->close();
			
			
			//redirect to my stories page
				header("Location: http://ec2-18-191-196-37.us-east-2.compute.amazonaws.com/~kaitlinaclark/news/index.php");
		}
		else if (isset($_GET['Comment'])){
		
			$comment_id = (int) $_GET['Comment'];
			$query_delete_comment = $connect->prepare("delete from comments where comment_id=?");
			if(!$query_delete_comment){
				printf("Query Prep Failed: %s \n", $connect->error);
				exit;
			}
			$query_delete_comment->bind_param("i", $comment_id);
			$query_delete_comment->execute();
			$query_delete_comment->close();
			header("Location: http://ec2-18-191-196-37.us-east-2.compute.amazonaws.com/~kaitlinaclark/news/index.php");
		}
    ?>

