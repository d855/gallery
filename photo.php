<?php include("includes/header.php"); ?>

<?php require_once('admin/includes/init.php'); ?>

<?php


if(empty($_GET['id'])){
    redirect('index.php');
}
$photo = Photo::findById($_GET['id']);

if(isset($_POST['submit'])){
    $author = trim($_POST['author']);
    $body = trim($_POST['body']);

    $newcomment = Comment::createComment($photo->id, $author, $body);

    // var_dump($newcomment);
    if($newcomment && $newcomment->save()){
        redirect('photo.php?id='.$photo->id);
    }else {
        $message = 'There was an error';
    }
}else {
    $author = '';
    $body = '';

 }

$comments = Comment::findComments($photo->id);


?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Post Content Column -->
            <div class="col-lg-12">

                <!-- Blog Post -->

                <!-- Title -->
                <h1><?php echo $photo->title; ?></h1>

                <!-- Author -->
                <p class="lead">
                    by <a href="#">Start Bootstrap</a>
                </p>

                <hr>

                <!-- Date/Time -->
                <p><span class="glyphicon glyphicon-time"></span> Posted on August 24, 2013 at 9:00 PM</p>

                <hr>

                <!-- Preview Image -->
                <img class="img-responsive photo_photo" src="admin/<?php echo $photo->picturePath(); ?>" alt="">

                <hr>

                <!-- Post Content -->
                <p class="lead"><?php echo $photo->caption; ?></p>
                <p><?php echo $photo->description; ?></p>

                <hr>

                <!-- Blog Comments -->

                <!-- Comments Form -->


                <div class="well">
                    <h4>Leave a Comment:</h4>
                    <form role="form" method="post">
                        <div class="form-group">
                            <label>Enter your name</label>
                            <input type="text" name="author" class="form-control">
                        </div>
                        <div class="form-group">
                            <textarea name="body" class="form-control" rows="3"></textarea>
                        </div>
                        <input type="hidden">
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>

                <hr>

                <!-- Posted Comments -->

                <?php foreach($comments as $comment) : ?>

                    <!-- Comment -->
                    <div class="media">
                        <a class="pull-left" href="#">
                            <img class="media-object" src="http://placehold.it/64x64" alt="">
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading"><?php echo $comment->author; ?>
                            </h4>
                            <?php echo $comment->body; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
                <!-- Comment -->
                

            </div>
        <?php include("includes/footer.php"); ?>



</body>

</html>
