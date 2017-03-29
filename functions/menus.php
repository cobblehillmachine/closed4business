<?php global $wpdb; ?>

<?php 
// INSERT MENU
if (isset($_POST['addmenu'])){
	
			if (($_FILES['file']['type']=='application/pdf' || $_FILES['file']['type']=='application/msword' || $_FILES['file']['type']=='application/rtf' || $_FILES['file']['type']=='text/plain' || $_FILES['file']['type']=='text/richtext') && ($_POST['menu'] != '')) {
			
				$menu = $_POST['menu'];
				$file = $_POST['file'];	
				$datestamp = date('Y-m-d');
				$time = time();
				
				if ($_FILES['file']['type'] == 'application/pdf') { $doctype = "pdf"; }
				if ($_FILES['file']['type'] == 'application/msword') { $doctype = "doc"; }
				if ($_FILES['file']['type'] == 'application/rtf') { $doctype = "doc"; }
				if ($_FILES['file']['type'] == 'text/plain') { $doctype = "doc"; }
				if ($_FILES['file']['type'] == 'text/richtext') { $doctype = "doc"; }
				
				$query = "SELECT id, COUNT(file) FROM cfb_menus WHERE menu='$menu' LIMIT 1";
				
				$results = mysql_query($query) or die(mysql_error());
				$existingMenu = mysql_fetch_array($results);
					 
					if ($existingMenu['COUNT(file)'] > 0) {
						
						$id = $existingMenu['id'];
							
						//first delete the existing menu for this type
						$query = "SELECT * FROM cfb_menus WHERE id='$id'";
						$results = mysql_query($query) or die(mysql_error());
						$row = mysql_fetch_array($results);
						$file = $row['file'];
						if ($file != '') {
							$folder = "../menu_files/";
							chdir($folder);
							unlink($file);
						}
						//and insert the uploaded file in its place
						/* upload the file */
								$target_path = "../menu_files/";
								$target_path = $target_path.$time."_".basename( $_FILES['file']['name']); 
								
								if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
									$message = "<div id='message' class='updated'><p>Menu ".basename( $_FILES['file']['name'])." successfully added</p></div>";
								} else{
									$message = "<div id='message' class='error'><p>There was an error uploading the file, please try again!</div>";
								}
						/* end upload file */
						$filename = $time."_".basename( $_FILES['file']['name']);
						$query = "UPDATE cfb_menus SET menu='$menu', file='$filename', doctype='$doctype', datestamp='$datestamp' WHERE id=$id"; 
						$result = mysql_query($query) or die(mysql_error());
						echo $message;
						
					} else {
							/* upload the file */
								$target_path = "../menu_files/";
								$target_path = $target_path.$time."_".basename( $_FILES['file']['name']); 
								
								if(move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
									$message = "<div id='message' class='updated'><p>Menu ".basename( $_FILES['file']['name'])." successfully added</p></div>";
								} else{
									$message = "<div id='message' class='error'><p>There was an error uploading the file, please try again!</div>";
								}
							/* end upload file */
						$filename = $time."_".basename( $_FILES['file']['name']);
						$query = "INSERT INTO cfb_menus SET menu='$menu', file='$filename', doctype='$doctype', datestamp='$datestamp'"; 
						$result = mysql_query($query) or die(mysql_error());
						echo $message;
					}
		
			} 
	
			 else {
					
					echo "<div id='message' class='error'><p>Please select a valid file and menu type. Pdfs and documents up to 5mb are allowed.</p></div>";	
					
			}
	
}

// DELETE MENU
if (isset($_GET['action']) && ($_GET['action'] == 'delete')) {

	$id = $_GET['id'];
	
	$query = "SELECT * FROM cfb_menus WHERE id='$id'";
	$results = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($results);
	$file = $row['file'];
	
	if ($file != '') {
		$folder = "../menu_files/";
		chdir($folder);
		unlink($file);
	}
	
	$query = "DELETE FROM cfb_menus WHERE id=$id"; 
	$result = mysql_query($query) or die(mysql_error());
	
	echo "<div id='message' class='updated'><p>Menu successfully deleted</p></div>";

}

?>

<div class="wrap">
<h2>Manage Menus</h2>

<?php 
// UPLOAD A MENU
if (isset($_GET['action']) && ($_GET['action'] == 'add')) { ?>
	<form enctype="multipart/form-data" action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_menus" method="post">
    <p>
    <table>
    	<tr>
        	<td colspan="2"><h3>Upload a Menu</h3></td>
        </tr>
        <tr>
        	<td>Select Menu</td>
            <td>
            <select name="menu">
            		<option value=""> -- Select One -- </option>
            <?php $query = "SELECT title FROM cfb_menus_types ORDER BY title ASC";
			$result = mysql_query($query);
				while($row = mysql_fetch_assoc($result)) { ?>
                	
                    <option value="<?php echo $row['title']; ?>"><?php echo $row['title']; ?></option>
                    
            <?php } ?>
            </select>
            </td>
        </tr>
       <tr>
        	<td valign="top">Select File</td>
            <td><input type="hidden" name="MAX_FILE_SIZE" value="5242880" /> <input name="file" type="file" /></td>
       </tr>
       <tr>
       		<td colspan="2"><p class="submit"><input type="hidden" name="addmenu" /><input type="submit" value="Add Menu" /> <input type="button" value="Cancel" onclick="location.href='<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_menus';"></p></td>
       </tr>
   </table>
   </p>
   </form>
  
<?php } ?>



<?php 
            // DISPLAY MENUS
            function display_menus(){

			$query = "SELECT title FROM cfb_menus_types GROUP BY title ORDER BY title ASC";
			$result = mysql_query($query);
				while($row = mysql_fetch_assoc($result)) { ?>
                	
                    <p><?php echo $row['title']; ?></p>
                    
                    <?php $currentMenu = $row['title'];
						// query menu within this type
						$sql = sprintf("SELECT * FROM cfb_menus WHERE menu='$currentMenu'");
						
						$r = mysql_query($sql);
						
						$num_rows = mysql_num_rows($r);
						
						if ($num_rows > 0) {
							while($rs = mysql_fetch_assoc($r)){
								
									
											if ($rs['doctype'] == 'pdf') { $doctype = 'pdf'; }
											if ($rs['doctype'] == 'doc') { $doctype = 'doc'; }
										
											$item = "<div class='entry'><div class='doctype ".$doctype."'></div>Uploaded ".date("F jS, Y", strtotime($rs['datestamp']))." <a href='".get_bloginfo('url')."/wp-admin/admin.php?page=cfb_menus&action=delete&id=".$rs['id']."' onclick='return confirm(\"Are you sure you want to delete?\")'>delete</a> <a href='".get_bloginfo('url')."/menu_files/".$rs['file']."' target='_blank'>view</a></div>";
														
										
											echo $item;
									
		
							} //endwhile
							mysql_free_result( $r );
							
						} else { ?>
                                			<div class="entry">&nbsp;<em>No menu uploaded.</em></div>
                       <?php } ?>
                 
				<?php }  //endwhile
				
			} //endfunction
			?>
         
<style>
<!--
.entry {
	background-color:#e3e3e3;
	padding:3px 3px 3px 3px;
	border:1px solid #ccc;
	margin-bottom:3px;	
	color:#666;
	line-height:26px;
}
.doctype {
	height:26px;
	width:26px;
	float:left;
	padding-right:5px;
	background-position:0 0;
	background-repeat:no-repeat;
}
.doctype.pdf {
	background-image:url(<?php bloginfo('template_directory'); ?>/functions/images/icon-pdf.png);
}
.doctype.doc {
	background-image:url(<?php bloginfo('template_directory'); ?>/functions/images/icon-word.png);
}
-->
</style>
<h3>Manage Menus</h3>
<p><strong><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_menus&action=add">Upload a Menu &raquo;</a></strong></p>
<p><?php display_menus(); ?></p>        
<p><strong><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_menus&action=add">Upload a Menu &raquo;</a></strong></p>
</div><!-- end wrap -->