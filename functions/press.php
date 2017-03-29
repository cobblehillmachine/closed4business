<?php global $wpdb; ?>

<?php function dateselector($inname, $useDate=0) 
    { 
        /* create array so we can name months */ 
        $monthname = array(1=> "January", "February", "March", 
            "April", "May", "June", "July", "August", 
            "September", "October", "November", "December"); 
 
        /* if date invalid or not supplied, use current time */ 
        if($useDate == 0) 
        { 
            $useDate = time(); 
        } 
 
        /* make month selector */ 
        echo "<select name=" . $inname . "month>\n"; 
        for($currentMonth = 1; $currentMonth <= 12; $currentMonth++) 
        { 
            echo "<option value=\""; 
            echo intval($currentMonth); 
            echo "\""; 
            if(intval(date( "m", $useDate))==$currentMonth) 
            { 
                echo " selected"; 
            } 
            echo ">" . $monthname[$currentMonth] . "\n"; 
        } 
        echo "</select>"; 
 
        /* make day selector */ 
        echo "<select name=" . $inname . "day>\n"; 
        for($currentDay=1; $currentDay <= 31; $currentDay++) 
        { 
            echo "<option value=\"$currentDay\""; 
            if(intval(date( "d", $useDate))==$currentDay) 
            { 
                echo " selected"; 
            } 
            echo ">$currentDay\n"; 
        } 
        echo "</select>"; 
 
        /* make year selector */ 
        echo "<select name=" . $inname . "year>\n"; 
        $startYear = date( "Y", $useDate); 
        for($currentYear = $startYear - 5; $currentYear <= $startYear+5;$currentYear++) 
        { 
            echo "<option value=\"$currentYear\""; 
            if(date( "Y", $useDate)==$currentYear) 
            { 
                echo " selected"; 
            } 
            echo ">$currentYear\n"; 
        } 
        echo "</select>"; 
 
    } 
?> 



<?php 
// INSERT PRESS
if (isset($_POST['add_press'])){
	
	$day = $_POST['press_day'];
	$month = $_POST['press_month'];
	$year = $_POST['press_year'];
	$datestamp = date("Ymd", strtotime($year."-".$month."-".$day));

		if ($_POST['description'] != '' && $_POST['link'] != '' && $_POST['source'] != '') {
	
			$link = $_POST['link'];
			$description = nl2br(htmlspecialchars($_POST['description']));
			$source = $_POST['source'];
			
			$query = "INSERT INTO cfb_press SET day='$day', month='$month', year='$year', link='$link', description='$description', source='$source', datestamp='$datestamp'"; 
			$result = mysql_query($query) or die(mysql_error());
			echo "<div id='message' class='updated'><p>Press successfully created</p></div>";
	
		} else {
			echo "<div id='message' class='error'><p>You must select a source and enter at least a link and description!</p></div>";	
		}
	

}

// DELETE PRESS
if (isset($_GET['action']) && ($_GET['action'] == 'delete')) {

	$id = $_GET['id'];
	$query = "DELETE FROM cfb_press WHERE id=$id"; 
	$result = mysql_query($query) or die(mysql_error());
	echo "<div id='message' class='updated'><p>Press successfully deleted</p></div>";

}

// EDIT PRESS
if (isset($_POST['edit_press'])) {

	$id = $_POST['id'];
	$day = $_POST['press_day'];
	$month = $_POST['press_month'];
	$year = $_POST['press_year'];
	$description = nl2br(htmlspecialchars($_POST['description']));
	$link = $_POST['link'];
	$source = $_POST['source'];	

	$datestamp = date("Ymd", strtotime($year."-".$month."-".$day));
	
	$query = "UPDATE cfb_press SET day='$day', month='$month', year='$year', link='$link', description='$description', source='$source', datestamp='$datestamp' WHERE id=$id"; 
	$result = mysql_query($query) or die(mysql_error());
	echo "<div id='message' class='updated'><p>Press successfully updated</p></div>";

}


?>

<div class="wrap">

<h2>Manage Press</h2>

<?php 
// ADD PRESS
if (isset($_GET['action']) && ($_GET['action'] == 'add')) { ?>
	<form action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_press" method="post">
    <p>
    <table width="100%">
    	<tr>
        	<td colspan="2"><h3>Add a Press Item</h3></td>
        </tr>
       <tr>
            <td>Source</td>
            <td>
            <select name="source">
            	<option value="">-- Select One --</option>
            	<?php 				
				global $wpdb;
				$query = "SELECT * FROM cfb_sources ORDER BY title ASC";
                $sources = mysql_query($query);

                while($source = mysql_fetch_assoc($sources)){ 

				?>
                
                    <option value="<?php echo $source['id']; ?>" /><?php echo $source['title']; ?></option>

                <?php } ?> 
            </select>
            </td>
       </tr>
        <tr>
        	<td>Date</td>
			<td><?php dateselector("press_"); ?></td>
        </tr>
        <tr>
        	<td valign="top">Link</td>
            <td><input type="text" name="link" style="width:100%;" />
            <br /><span style="color:#999; font-style:italic; font-size:10px;">Please include http:// at the beginning of your url</span>
            </td>
       </tr>
       <tr>
        	<td valign="top">Description</td>
            <td><textarea id="description" name="description" style="height:150px; width:100%;"></textarea></td>
       </tr>
       <tr>
       		<td colspan="2"><p class="submit"><input type="hidden" name="add_press" /><input type="submit" value="Add Press" /> <input type="button" value="Cancel" onclick="location.href='<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_press';"></p></td>
       </tr>
   </table>
   </p>
   </form>
  
<?php } ?>


<?php 
// EDIT PRESS
if (isset($_GET['action']) && ($_GET['action'] == 'edit')) { 

$id = $_GET['id'];
$query = "SELECT * FROM cfb_press WHERE id='$id'";
$results = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($results);
?>
	<form action="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_press" method="post">
    <p>
    <table width="100%">
    	<tr>
        	<td colspan="2"><h3>Edit Press</h3></td>
        </tr>
        <tr>
            <td>Source</td>
            <td>
            	<select name="source">
            	<?php 
				
				$source = $row['source'];
				
				global $wpdb;
				$query = "SELECT * FROM cfb_sources ORDER BY title ASC";
                $sources = mysql_query($query);

                while($source = mysql_fetch_assoc($sources)){ 
				
				?>
                
                    <option value="<?php echo $source['id']; ?>"<?php if ($source['id'] == $row['source']) { echo ' selected="selected"'; } ?> /> <?php echo $source['title']; ?></option>
                  
                <?php } ?>  
                </select>
            </td>
       </tr>
        <tr>
        	<td>Date</td>
        	<td>            
            
        <?php
		$inname="press_";
	/* create array so we can name months */ 
        $monthname = array(1=> "January", "February", "March", 
            "April", "May", "June", "July", "August", 
            "September", "October", "November", "December"); 
 
         /* if date invalid or not supplied, use current time */ 
        if($useDate == 0) 
        { 
            $useDate = time(); 
        } 
		
        /* make month selector */ 
        echo "<select name=" . $inname . "month>\n"; 
        for($currentMonth = 1; $currentMonth <= 12; $currentMonth++) 
        { 
            echo "<option value=\""; 
            echo intval($currentMonth); 
            echo "\""; 
            if(intval($row['month']==$currentMonth)) 
            { 
                echo " selected"; 
            } 
            echo ">" . $monthname[$currentMonth] . "\n"; 
        } 
        echo "</select>"; 
 
        /* make day selector */ 
        echo "<select name=" . $inname . "day>\n"; 
        for($currentDay=1; $currentDay <= 31; $currentDay++) 
        { 
            echo "<option value=\"$currentDay\""; 
            if(intval($row['day']==$currentDay)) 
            { 
                echo " selected"; 
            } 
            echo ">$currentDay\n"; 
        } 
        echo "</select>"; 
 
        /* make year selector */ 
        echo "<select name=" . $inname . "year>\n"; 
        $startYear = date( "Y", $useDate); 
        for($currentYear = $startYear - 5; $currentYear <= $startYear+5;$currentYear++) 
        { 
            echo "<option value=\"$currentYear\""; 
            if($row['year']==$currentYear) 
            { 
                echo " selected"; 
            } 
            echo ">$currentYear\n"; 
        } 
        echo "</select>"; 

		?>
           
            
            </td>
        </tr>
        <tr>
        	<td valign="top">Link</td>
            <td><input type="text" name="link" value="<?php echo $row['link']; ?>" style="width:100%;" />
            <br /><span style="color:#999; font-style:italic; font-size:10px;">Please include http:// at the beginning of your url</span>
            </td>
       </tr>
       <tr>
      		<td valign="top">Description</td>
       		<td>
				<textarea name="description" style="height:150px; width:100%;"><?php echo str_replace("<br />","",$row['description']); ?></textarea>
			</td>
       </tr>
       <tr>
       		<td colspan="2"><p class="submit"><input type="hidden" name="edit_press" /><input type="hidden" name="id" value="<?php echo $row['id']; ?>" /><input type="submit" value="Update Press" /> <input type="button" value="Cancel" onclick="location.href='<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_press';"></p></td>
       </tr> 
   </table>
   </p>
   </form>
<?php } ?>

<?php 
		// DISPLAY PRESS
		function list_press(){	

			global $wpdb;	
			$myrows = $wpdb->get_results("SELECT year, month FROM cfb_press GROUP BY year, month ORDER BY year DESC, month DESC");
			foreach ($myrows as $row) { ?>
				     <p><?php echo date('F', mktime(0,0,0,$row->month,1))." ".$row->year; ?></p>
                    
                    <?php $currentYear = $row->year;
						  $currentMonth = $row->month;
						// query press within this year and month
						
						$mypress = $wpdb->get_results("SELECT * FROM cfb_press WHERE year='$currentYear' AND month='$currentMonth' ORDER BY day DESC");
						foreach ($mypress as $press) {
							
							$source_id = $press->source; 
							$query = "SELECT * FROM cfb_sources WHERE id='$source_id'";
							$results = mysql_query($query) or die(mysql_error());
							$row = mysql_fetch_array($results);
							$source = $row['title'];
							?>
													
							<div class='entry'>
                            	<table width="100%">
									<tr>
                                    	<td><a href="<?php echo $press->link; ?>" target="_blank"><?php echo $source; ?></a> - <strong><?php echo $press->description; ?></strong></td>
                                        <td align="right" width="90"><a href='<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_press&action=edit&id=<?php echo $press->id; ?>'>edit</a> <a href='<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_press&action=delete&id=<?php echo $press->id;?>' onclick='return confirm("Are you sure you want to delete?")'>delete</a>
                                        </td>
                                    </tr>
                               </table>
                          </div>    
							
							<?php 
							
						}
						
					?>
  
				<?php } 
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
<h3>Manage Press</h3>
<p><strong><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_press&action=add">Add a Press Item &raquo;</a></strong></p>
<p><?php list_press(); ?></p>        
<p><strong><a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=cfb_press&action=add">Add a Press Item &raquo;</a></strong></p>
</div><!-- end wrap -->