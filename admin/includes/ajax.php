<?php 

require_once('init.php');

$user = new User();
$photo = new Photo();
if(isset($_POST['image_name'])){

	$user->ajaxSaveUserImage($_POST['image_name'], $_POST['user_id']);


}

if(isset($_POST['photo_id'])){

	Photo::displaySidebar($_POST['photo_id']);
}