<?php session_start(); ?>
<?php if(!isset($_SESSION['username'])){
    header("location: ./login.php");
} ?>   

<?php
    if(isset($_POST['album_submit'])){
        $album_name = $_POST['album_name'];
    }
?>

<?php

    // https://github.com/tschoffelen/db.php
    // https://www.verot.net/php_class_upload_samples.htm

    require "./db.php";
    require "./Vendor/php/class.upload.php";
    //echo date("d-m-Y H:i:s");
    if(isset($_POST['submit'])){
        $album_name = $_POST['album_name'];
        $date = date("Y-m-d H:i:s");
        
        $db->select('albums', ['album_name' => $album_name]);
        if($db->row_array()){
            // get id of existing row
            $album_ID = ($db->row_array()['id']);
        } else {
            // create new row
            $db->insert(
                'albums', [
                    'album_name' => $album_name,
                    'date' => $date
                ]
            );
            
            $album_ID = $db->id();
        }
        
        
        echo "<br>welcome to your album now you can upload images";
        // Image Count
        $total = count($_FILES['image_fields']['name']);
        
        // Loop through each file
        for( $i=0 ; $i < $total ; $i++ ) {
            $handle = new upload($_FILES['image_fields']['tmp_name'][$i]);
            
            
            if ($handle->uploaded) {
                
              /////////////////////////////     
              // Original Image     
              /////////////////////////////
                
              // File Naming    
              $handle->file_new_name_body   = pathinfo($_FILES['image_fields']['name'][$i], PATHINFO_FILENAME );
              $handle->file_new_name_ext    = pathinfo($_FILES['image_fields']['name'][$i], PATHINFO_EXTENSION); 
                  
              // Uploading Path    
              $handle->process('../files/raw/');
                
              /////////////////////////////     
              // Resized Image - Large    
              /////////////////////////////
                
              // File Naming      
              $handle->file_new_name_body   = pathinfo($_FILES['image_fields']['name'][$i], PATHINFO_FILENAME );
              //$handle->file_name_body_add = '_resized';
              $handle->file_new_name_ext    = pathinfo($_FILES['image_fields']['name'][$i], PATHINFO_EXTENSION);
                
              // Resizing of image    
              $handle->image_resize         = true;
              $handle->image_ratio          = true;
              $handle->image_y              = 1920;
              $handle->image_x              = 1920;
                
              // Compression of image 
                
              //https://stackoverflow.com/questions/415801/allowed-memory-size-of-33554432-bytes-exhausted-tried-to-allocate-43148176-byte
              // It will take unlimited memory usage of server     
              ini_set('memory_limit', '-1');
                
              $handle->image_convert = 'jpg';
              $handle->jpeg_quality = 80;  
                  
              // Uploading Path    
              $handle->process('../files/');
                
                
              /////////////////////////////     
              // Resized Image - Thumb Large    
              /////////////////////////////
                
              // File Naming      
              $handle->file_new_name_body   = pathinfo($_FILES['image_fields']['name'][$i], PATHINFO_FILENAME );
              $handle->file_name_body_pre = 'thumb_large_';
              $handle->file_new_name_ext    = pathinfo($_FILES['image_fields']['name'][$i], PATHINFO_EXTENSION);
                
              // Resizing of image    
              $handle->image_resize         = true;
              $handle->image_ratio          = true;
              $handle->image_y              = 800;
              $handle->image_x              = 800;
                
              // Compression of image
              ini_set('memory_limit', '-1');    
              $handle->image_convert = 'jpg';
              $handle->jpeg_quality = 95;  
                  
              // Uploading Path    
              $handle->process('../files/thumb_large');
                
              /////////////////////////////     
              // Resized Image - Thumb Small    
              /////////////////////////////
                
              // File Naming      
              $handle->file_new_name_body   = pathinfo($_FILES['image_fields']['name'][$i], PATHINFO_FILENAME );
              $handle->file_name_body_pre = 'thumb_small_';
              $handle->file_new_name_ext    = pathinfo($_FILES['image_fields']['name'][$i], PATHINFO_EXTENSION);
                
              // Resizing of image    
              $handle->image_resize         = true;
              $handle->image_ratio          = true;
              $handle->image_y              = 400;
              $handle->image_x              = 400;
                
              // Compression of image 
              ini_set('memory_limit', '-1');    
              $handle->image_convert = 'jpg';
              $handle->jpeg_quality = 100;  
                  
              // Uploading Path    
              $handle->process('../files/thumb_small');    
              
              // To increase the excecution time    
              ini_set('max_execution_time', 300);
                
              if ($handle->processed) {
                
                $img_url_raw = "files/raw/" . $_FILES['image_fields']['name'][$i];  
                $img_url = $_FILES['image_fields']['name'][$i];  
                $db->insert(
                    'images', [
                        'img_url_raw' => $img_url_raw,
                        'img_url' => $img_url,
                        'album_id' => $album_ID,
                        'date' => $date
                    ]
                );  
                  
                echo 'Uploading Succesfull !!!';
                $handle->clean();
              } else {
                echo 'error : ' . $handle->error;
              }
            } else {
                echo "Not Uploaded !!!";
            }
        }
    }
    
?>

<?php include('header.php'); ?>
<?php include('sidenav.php'); ?>
 
<div class="main">
    <?php 
        if(isset($album_name)){
            echo "<h1>".$album_name."</h1>";       
    ?>        
        <h1>Upload Images</h1>
        <form enctype="multipart/form-data" method="post" action="album_images.php">
          <input type="file" size="32" name="image_fields[]" value="" multiple>
          <input type="hidden" value="<?php echo $album_name ?>" name="album_name">
          <input type="submit" name="submit" value="upload">
        </form>
    <?php } else {
            header("location: upload_album.php");
        }
    ?>
</div>
    
<!-- To Prevent Data Submission on Refresh -->
<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
    
<?php include('footer.php'); ?>
