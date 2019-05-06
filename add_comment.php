<?php
session_start();
require 'connect.php';
if(isset($_POST["comment_text"])){
    $query_add_comment = $connect->prepare("insert into comments set user_id=?,
                                                                story_id=?,
                                                                comment=?,
                                                                date_posted=?");
    if(!$query_add_comment){
        printf("Query Prep Failed: %s \n", $connect->error);
        exit;
    }

    $query_add_comment->bind_param("iiss", $user_id, $story_id, $comment, $date_posted);
        //story_id already set above
        $user_id = $_SESSION["user_id"];
        $story_id = $_SESSION["story_id"];
        $comment = $_POST["comment_text"];
        $date_posted = date("Y-m-d");

    $query_add_comment->execute();
    $query_add_comment->close();

    header('Location: http://ec2-18-191-196-37.us-east-2.compute.amazonaws.com/~kaitlinaclark/news/view_story.php?Story='.$story_id.'');
}
?>