 <?php
session_start();
require 'connect.php';

$comment_id= (int) $_POST["comment_id"];
if(isset($_POST["comment_text"])){
    $query_add_comment = $connect->prepare("update comments set comment=?
                                                                where comment_id=?");
    if(!$query_add_comment){
        printf("Query Prep Failed: %s \n", $connect->error);
        exit;
    }

    $query_add_comment->bind_param("si", $comment, $comment_id);
                    $comment = $_POST["comment_text"];

    $query_add_comment->execute();
    $query_add_comment->close();

    header('Location: http://ec2-18-191-196-37.us-east-2.compute.amazonaws.com/~kaitlinaclark/news/index.php');
}
?>