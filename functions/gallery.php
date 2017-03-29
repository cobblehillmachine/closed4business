<?php global $wpdb; ?>

<?php 
// INSERT IMAGE
if (isset($_POST['add_image'])){

		if (!empty($_POST['link'])) {
			//insert the url not a file	
			$caption = htmlspecialchars($_POST['caption']);
			$link = $_POST['link'];
			$query = "INSERT INTO cfb_gallery SET caption='$caption', link='$link'"; 
			$result = mysql_query($query) or die(mysql_error());
			echo "<div id='message' class='updated'><p>Image successfully added</p></div>";
		}
		else {
			//insert the file not a url	
				if ($_FILES['file']['type']=='image/gif' || $_FILES['file']['type']=='image/jpeg' || $_FILES['file']['type']=='image/png') {
					
					$caption = htmlspecialchars($_POST['caption']);
					$file = $_POST['file'];
					$time = time();
									
					/* upload the file */
						$target_path = "../gallery_files/";
						$target_path = $target_path.$time."_".basename( $_FILES['file']['name']); 
						
						if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
							$message = "<div id='message' class='updated'><p>Image ".basename( $_FILES['file']['name'])." successfully added</p></div>";
						} else{
							$message = "<div id='message' class='error'><p>There was an error uploading the image, please try again!</div>";
						}
					/* end upload file */
					$filename = $time."_".basename( $_FILES['file']['name']);
					$query = "INSERT INTO cfb_gallery SET caption='$caption', file='$filename'"; 
					$result = mysql_query($query) or die(mysql_error());
					echo $message;
			
				} 
		
				 else {
						
						echo "<div id='message' class='error'><p>Please select a valid file! Only image (.jpg, .jpeg, .gif, .png) up to 5mb are allowed.</p></div>";	
						
				}
		
		}//end else insert the image not the url
	
}

// DELETE IMAGE
if (isset($_GET['action']) && ($_GET['action'] == 'delete')) {

	$id = $_GET['id'];
	
	$query = "SELECT * FROM cfb_gallery WHERE id='$id'";
	$results = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($results);
	$file = $row['file'];
	
	if ($file != '') {
		$folder = "../gallery_files/";
		chdir($folder);
		unlink($file);
	}
	
	$query = "DELETE FROM cfb_gallery WHERE id=$id"; 
	$result = mysql_query($query) or die(mysql_error());

	echo "<div id='message' class='updated'><p>Image successfully deleted</p></div>";

}

// EDIT IMAGE
if (isset($_POST['edit_image'])) {

		$id = $_POST['id'];
		$caption = htmlspecialchars($_POST['caption']);
		
		$query = "UPDATE cfb_gallery SET caption='$caption' WHERE id=$id"; 
		$result = mysql_query($query) or die(mysql_error());
				
	echo "<div id='message' class='updated'><p>Image successfully updated</p></div>";

}

?>

<div class="wrap">
<h2>Manage Gallery</h2>

<?php 
// ADD AN IMAGE
if (isset($_GET['action']) && ($_GET['action'] == 'add')) { 
																
?>

	<form enctype="multipart/form-data" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_gallery" method="post">
    <p>
    <table>
    	<tr>
        	<td colspan="2"><h3>Add Image</h3></td>
        </tr>
        <tr>
        	<td valign="top">Caption</td>
            <td><input type="text" name="caption" style="width:463px;" /></td>
       </tr>
       <tr>
        	<td valign="top">File</td>
            <td><input type="hidden" name="MAX_FILE_SIZE" value="5242880" /><input name="file" type="file" />
            <br /><span style="color:#999; font-style:italic; font-size:10px;">Images should be exactly 950 pixels wide by 468 pixels high</span></td>
       </tr>
       <tr>
        	<td valign="top">or URL</td>
            <td><input type="text" name="link" style="width:463px;" />
            <br /><span style="color:#999; font-style:italic; font-size:10px;">Please include http:// at the beginning of your image url</span>
            </td>
       </tr>
    </table>
   
       <table>
       <tr>
       		<td><p class="submit"><input type="hidden" name="add_image" /><input type="submit" value="Add Image" /> <input type="button" value="Cancel" onclick="location.href='<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_gallery';"></p></td>
       </tr>
   </table>
   </p>
   
   </form>
  
<?php } ?>


<?php 
// EDIT IMAGE
if (isset($_GET['action']) && ($_GET['action'] == 'edit')) { 

$id = $_GET['id'];
//general info
$query = "SELECT * FROM cfb_gallery WHERE id='$id'";
$results = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($results);
?>
	<form action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_gallery" method="post">
    <p>
    <table>
    	<tr>
        	<td colspan="2"><h3>Edit Image</h3></td>
        </tr>
        <tr>
        	<td valign="top">Caption</td>
            <td><input type="text" name="caption" value="<?php echo $row['caption']; ?>" style="width:463px;" /></td>
       </tr>
   </table>
   
       
       <table>
           <tr>
                <td><p class="submit"><input type="hidden" name="edit_image" /><input type="hidden" name="id" value="<?php echo $row['id']; ?>" /><input type="submit" value="Update Image" /> <input type="button" value="Cancel" onclick="location.href='<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_gallery';"></p></td>
           </tr>
       </table>
   </p>
   </form>
<?php } ?>



<?php 
		// DISPLAY IMAGES
		function list_images(){
			global $wpdb;
			$query = "SELECT * FROM cfb_gallery ORDER BY id ASC";
			$result = mysql_query($query);
			while($row = mysql_fetch_assoc($result)){ ?>
            
				<div class='entry'>
                	<table width="100%">
                    	<tr>
                        	<?php if ($row['link']) { ?>
                            	<td width="50"><a href="<?php echo $row['link']; ?>" target="_blank"><img src="<?php echo $row['link']; ?>" border="0" width="100" height="50" style="padding-right:10px;" /></a></td>
                            <?php } else { ?>
                            	<td width="50"><a href="<?php bloginfo('url'); ?>/gallery_files/<?php echo $row['file']; ?>" target="_blank"><img src="<?php bloginfo('url'); ?>/gallery_files/<?php echo $row['file']; ?>" border="0" width="100" height="50" style="padding-right:10px;" /></a></td>
                            <?php } ?>
                            <td><strong><?php echo $row['caption']; ?></strong></td>
                            <td align="right"><a href='<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_gallery&action=edit&id=<?php echo $row['id']; ?>'>edit</a> <a href='<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_gallery&action=delete&id=<?php echo $row['id']; ?>' onclick='return confirm("Are you sure you want to delete?")'>delete</a>
               				</td>
                        </tr>   
  					</table>             
               </div>
                
			<?php } mysql_free_result($result);	
		}
		?>
        
<style>
<!--
.entry {
	background-color:#e3e3e3;
	padding:3px;
	border:1px solid #ccc;
	margin-bottom:3px;	
	color:#666;
}
.green {
	color:#060;	
}
.red {
	color:#f00;	
}
-->
</style>
<h3>Gallery</h3>
<p><strong><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_gallery&action=add">Add an Image &raquo;</a></strong></p>
<p><?php list_images(); ?></p>        
<p><strong><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_gallery&action=add">Add an Image &raquo;</a></strong></p>
</div><!-- end wrap -->