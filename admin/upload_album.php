<?php session_start(); ?>
<?php if(!isset($_SESSION['username'])){
    header("location: ./login.php");
} ?>

<?php include('header.php'); ?>
<?php include('sidenav.php'); ?>
   
<div class="main">
    <h1>Upload Album Here</h1>
    <form action="album_images.php" method="post" enctype="multipart/form-data">
        <input type="text" name="album_name" placeholder="Album Name">
        <input type="submit" name="album_submit" value="Create Album">
    </form>
</div>

<!-- To Prevent Data Submission on Refresh -->
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
    
<?php include('footer.php'); ?>
