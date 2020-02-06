<?php include("includes/header.php"); ?>
<?php if(!$session->isSignedIn()){ redirect('login.php');} ?>
<?php 

if(empty($_GET['id'])){
    redirect('users.php');
}

$user = User::findById($_GET['id']);

if(isset($_POST['update'])){

    if($user){
        $user->username = $_POST['username'];
        $user->password = $_POST['password'];
        $user->first_name = $_POST['first_name'];
        $user->last_name = $_POST['last_name'];

        if(empty($_FILES['user_image'])){
            $user->save();
        }else {
            $user->setFile($_FILES['user_image']);
            $user->saveUserImage();
            $user->save();

            redirect('edit_user.php?id='.$user->id);


        }


        // $user->save();
    }
}



// if(isset($_POST['update'])){
//     echo 'updatejuem';   
// }





?>

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            

            <!-- Top Menu Items -->
            
            <?php include('includes/top_nav.php'); ?>

            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            
            <?php include('includes/side_nav.php'); ?>

            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                           Edit user: <?php echo $user->first_name. ' '. $user->last_name; ?>
                        </h1>
                        
                        <div class="col-md-6">
                            <img class="img-responsive" src="<?php echo $user->imagePlaceholder(); ?>">
                        </div>



                        <form method="post" action="" enctype="multipart/form-data">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Add profile image</label>
                                    <input type="file" name="user_image" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" name="username" class="form-control" value="<?php echo $user->username; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="first_name">First name</label>
                                    <input type="text" name="first_name" class="form-control" value="<?php echo $user->first_name; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="last_name">Last name</label>
                                    <input type="text" name="last_name" class="form-control" value="<?php echo $user->last_name; ?>">
                                </div>

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" class="form-control" value="<?php echo $user->password; ?>">
                                </div>

                                <div class="form-group">
                                    <a class="btn btn-danger pull-right" href="delete_user.php?id=<?php echo $user->id; ?>">Delete user</a>
                                    <input type="submit" name="update" class="btn btn-primary" value="Update user">
                                </div>
                            </div>
                        </form>





                    </div>
                </div>
                <!-- /.row -->

            </div>

            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

  <?php include("includes/footer.php"); ?>