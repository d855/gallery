<?php include("includes/header.php"); ?>
<?php 

$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
$items_per_page = 4;
$total_items = Photo::countAll();

$paginate = new Paginate($page, $items_per_page, $total_items);

$sql = "SELECT * FROM photos ";
$sql .= "LIMIT {$items_per_page} ";
$sql .= "OFFSET {$paginate->offset()}";
$photos = Photo::findQuery($sql);


// $photos = Photo::findAll();

?>

<div class="row">

    <!-- Blog Entries Column -->
    <div class="col-md-12">
    	
    
    	<div class="thumbnails row">
		    <?php foreach($photos as $photo) : ?>
	    		<div class="col-xs-6 col-md-3">
	    			<a class="thumbnail" href="photo.php?id=<?php echo $photo->id; ?>"><img class="homepage_photo" src="admin/<?php echo $photo->picturePath();?>"></a>
	    			
	    		</div>
		    <?php endforeach; ?>
    	</div>

    	<div class="row">
    		<ul class="pagination">
    			<?php

    			if($paginate->totalPages() > 1){
    				if($paginate->hasNextPage()){
    					echo "<li class='next'><a href='index.php?page={$paginate->next()}'>Next page</a></li>";
    				}
    			}
    			
    			for($i=1; $i <= $paginate->totalPages(); $i++){
    				if($i == $paginate->current_page){
    					echo "<li class='active'><a href='index.php?page={$i}'>{$i}</a></li>";
    				}else {
    					echo "<li><a href='index.php?page={$i}'>{$i}</a></li>";	
    				}
    			}

    			

    				

    			if($paginate->hasPreviousPage()){
    					echo "<li class='previous'><a href='index.php?page={$paginate->previous()}'>Previous page</a></li>";
    				}

    			 ?>

    			
    			<!-- <li class='previous'><a href="#">Previous page</a></li> -->
    		</ul>
    	</div>
    </div>

    <!-- Blog Sidebar Widgets Column -->
    <!-- <div class="col-md-4"> -->
        <!-- <?php // include("includes/sidebar.php"); ?> -->
   <!-- </div> -->
        <?php include("includes/footer.php"); ?>
