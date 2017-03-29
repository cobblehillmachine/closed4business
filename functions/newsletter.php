<?php global $wpdb; ?>

<?php 
// INSERT SUBSCRIBER
if (isset($_POST['add_subscriber'])){

		
	if(trim($_POST['email']) === '')  {
		$emailError = 'Please enter an email address.';
		$hasError = true;
	} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
		$emailError = 'Please enter a valid email address!';
		$hasError = true;
	} else {
		$email = trim($_POST['email']);
	}

	if(!isset($hasError)) {
	
	//dupe check
	$query = "SELECT * FROM cfb_newsletter WHERE email LIKE '$email'";
	$dupes = mysql_query($query);
	$num_results = mysql_num_rows($dupes);

		if ($num_results == 0){
			
			//insert into db if no dupe is found
			$datestamp = date("Ymd");
			$wpdb->query("INSERT INTO cfb_newsletter(email, datestamp) VALUES ('$email', '$datestamp')");	
			
		}
		
		echo "<div id='message' class='updated'><p>Subscriber successfully added</p></div>";
			
	}
	else { 
		echo "<div id='message' class='error'><p>".$emailError."</p></div>";
	}
	

}

// DELETE SUBSCRIBER
if (isset($_GET['action']) && ($_GET['action'] == 'delete')) {

	$id = $_GET['id'];
	$query = "DELETE FROM cfb_newsletter WHERE id=$id"; 
	$result = mysql_query($query) or die(mysql_error());
	echo "<div id='message' class='updated'><p>Subscriber successfully deleted</p></div>";

}

// EDIT SUBSCRIBER
if (isset($_POST['edit_subscriber'])) {

		$id = $_POST['id'];
		$email = $_POST['email'];
		
		$query = "UPDATE cfb_newsletter SET email='$email' WHERE id=$id"; 
		$result = mysql_query($query) or die(mysql_error());
				
	echo "<div id='message' class='updated'><p>Subscriber successfully updated</p></div>";

}

?>

<div class="wrap">
<h2>Manage Subscribers</h2>

<?php 
// ADD A SUBSCRIBER
if (isset($_GET['action']) && ($_GET['action'] == 'add')) { 
																
?>

	<form action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_newsletter" method="post">
    <p>
    <table>
    	<tr>
        	<td colspan="2"><h3>Add Subscriber</h3></td>
        </tr>
        <tr>
        	<td valign="top">Email</td>
            <td><input type="text" name="email" style="width:463px;" /></td>
       </tr>
    </table>
   
       <table>
       <tr>
       		<td><p class="submit"><input type="hidden" name="add_subscriber" /><input type="submit" value="Add Subscriber" /> <input type="button" value="Cancel" onclick="location.href='<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_newsletter';"></p></td>
       </tr>
   </table>
   </p>
   
   </form>
  
<?php } ?>


<?php 
// EDIT SUBSCRIBER
if (isset($_GET['action']) && ($_GET['action'] == 'edit')) { 

$id = $_GET['id'];
//general info
$query = "SELECT * FROM cfb_newsletter WHERE id='$id'";
$results = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($results);
?>
	<form action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_newsletter" method="post">
    <p>
    <table>
    	<tr>
        	<td colspan="2"><h3>Edit Subscriber</h3></td>
        </tr>
        <tr>
        	<td valign="top">Email</td>
            <td><input type="text" name="email" value="<?php echo $row['email']; ?>" style="width:463px;" /></td>
       </tr>
   </table>
   
       
       <table>
           <tr>
                <td><p class="submit"><input type="hidden" name="edit_subscriber" /><input type="hidden" name="id" value="<?php echo $row['id']; ?>" /><input type="submit" value="Update Subscriber" /> <input type="button" value="Cancel" onclick="location.href='<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_newsletter';"></p></td>
           </tr>
       </table>
   </p>
   </form>
<?php } ?>



<?php 
		// DISPLAY SUBSCRIBERS
		function list_subscribers(){
			global $wpdb;
			$query = "SELECT * FROM cfb_newsletter ORDER BY email ASC";
			$result = mysql_query($query);
			while($row = mysql_fetch_assoc($result)){ ?>
            
				<div class='entry'>
                	<table width="100%">
                    	<tr>
                            <td><strong><?php echo $row['email']; ?></strong></td>
                            <td align="right"><a href='<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_newsletter&action=edit&id=<?php echo $row['id']; ?>'>edit</a> <a href='<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_newsletter&action=delete&id=<?php echo $row['id']; ?>' onclick='return confirm("Are you sure you want to delete?")'>delete</a>
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
	padding:3px 3px 3px 6px;
	border:1px solid #ccc;
	margin-bottom:3px;	
	color:#666;
}
-->
</style>
<h3>Subscribers</h3>
<p><strong><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_newsletter&action=add">Add a Subscriber &raquo;</a></strong></p>
<p><?php list_subscribers(); ?></p>        
<p><strong><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_newsletter&action=add">Add a Subscriber &raquo;</a></strong></p>
</div><!-- end wrap -->