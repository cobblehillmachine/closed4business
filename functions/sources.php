<?php global $wpdb; ?>

<?php 
// INSERT SOURCE
if (isset($_POST['add_source'])){

	if ($_POST['title'] != '' && $_POST['image'] != '') {
		$title = htmlspecialchars($_POST['title']);
		$image = $_POST['image'];
		
		$query = "INSERT INTO cfb_sources SET title='$title', image='$image'"; 
		$result = mysql_query($query) or die(mysql_error());				
		
		echo "<div id='message' class='updated'><p>Source successfully added</p></div>";

	} else {
		echo "<div id='message' class='error'><p>Please fill out all fields!</p></div>";	
	}

}

// DELETE SOURCE
if (isset($_GET['action']) && ($_GET['action'] == 'delete')) {

	$id = $_GET['id'];
	$query = "DELETE FROM cfb_sources WHERE id=$id"; 
	$result = mysql_query($query) or die(mysql_error());
	echo "<div id='message' class='updated'><p>Source successfully deleted</p></div>";

}

// EDIT SOURCE
if (isset($_POST['edit_source'])) {

		$id = $_POST['id'];
		$title = htmlspecialchars($_POST['title']);
		$image = $_POST['image'];
		
		$query = "UPDATE cfb_sources SET title='$title', image='$image' WHERE id=$id"; 
		$result = mysql_query($query) or die(mysql_error());
				
	echo "<div id='message' class='updated'><p>Source successfully updated</p></div>";

}

?>

<div class="wrap">
<h2>Manage Sources</h2>

<?php 
// ADD A SOURCE
if (isset($_GET['action']) && ($_GET['action'] == 'add')) { 
																
?>

	<form action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_sources" method="post">
    <p>
    <table>
    	<tr>
        	<td colspan="2"><h3>Add Source</h3></td>
        </tr>
        <tr>
        	<td valign="top">Title</td>
            <td><input type="text" name="title" style="width:463px;" /></td>
       </tr>
       <tr>
        	<td valign="top">Image URL</td>
            <td><input type="text" name="image" style="width:463px;" />
            </td>
       </tr>
    </table>
   
       <table>
       <tr>
       		<td><p class="submit"><input type="hidden" name="add_source" /><input type="submit" value="Add Source" /> <input type="button" value="Cancel" onclick="location.href='<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_sources';"></p></td>
       </tr>
   </table>
   </p>
   
   </form>
  
<?php } ?>


<?php 
// EDIT SOURCE
if (isset($_GET['action']) && ($_GET['action'] == 'edit')) { 

$id = $_GET['id'];
//general info
$query = "SELECT * FROM cfb_sources WHERE id='$id'";
$results = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($results);
?>
	<form action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_sources" method="post">
    <p>
    <table>
    	<tr>
        	<td colspan="2"><h3>Edit Source</h3></td>
        </tr>
        <tr>
        	<td valign="top">Title</td>
            <td><input type="text" name="title" value="<?php echo $row['title']; ?>" style="width:463px;" /></td>
       </tr>
       <tr>
        	<td valign="top">Image URL</td>
            <td><input type="text" name="image" value="<?php echo $row['image']; ?>" style="width:463px;" />
            </td>
       </tr> 
   </table>
   
       
       <table>
           <tr>
                <td><p class="submit"><input type="hidden" name="edit_source" /><input type="hidden" name="id" value="<?php echo $row['id']; ?>" /><input type="submit" value="Update Source" /> <input type="button" value="Cancel" onclick="location.href='<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_sources';"></p></td>
           </tr>
       </table>
   </p>
   </form>
<?php } ?>



<?php 
		// DISPLAY SOURCES
		function list_sources(){
			global $wpdb;
			$query = "SELECT * FROM cfb_sources ORDER BY title ASC";
			$result = mysql_query($query);
			while($row = mysql_fetch_assoc($result)){ ?>
            
				<div class='entry'>
                	<table width="100%">
                    	<tr>
                        	<td width="50"><a href="<?php echo $row['image']; ?>" target="_blank"><img src="<?php echo $row['image']; ?>" border="0" height="25" style="padding-right:10px;" /></a></td>
                            <td><strong><?php echo $row['title']; ?></strong></td>
                            <td align="right"><a href='<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_sources&action=edit&id=<?php echo $row['id']; ?>'>edit</a> <a href='<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_sources&action=delete&id=<?php echo $row['id']; ?>' onclick='return confirm("Are you sure you want to delete?")'>delete</a>
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
<h3>Sources</h3>
<p><strong><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_sources&action=add">Add a Source &raquo;</a></strong></p>
<p><?php list_sources(); ?></p>        
<p><strong><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_sources&action=add">Add a Source &raquo;</a></strong></p>
</div><!-- end wrap -->