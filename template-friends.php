<?php
/*
Template Name: Friends
*/
?>
<?php get_header(); ?>
	
  <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>  
    
   <div id="friendsHeader"></div>
    <div id="friends">
	  <div id="friendsLeft">
        <img src="<?php bloginfo('template_directory'); ?>/images/friendsLogos.jpg" id="friend_logos" alt="" border="0" usemap="#Map" />
       	<div id="friendText">
            <img src="<?php bloginfo('template_directory'); ?>/images/friends-subhead.png" border="0" alt="We're Pretty Tight" id="friends_title" />
            <?php echo get_settings('c4b_content_friends'); ?>
          </div>
        </div>
        <div id="friendsRight">
			<style type="text/css">
            #friends_links {
				width:582px;
				height:507px;
                position:relative;
            }
            </style>
            <div id="friends_links">
               <img src="<?php bloginfo('template_directory'); ?>/images/friendsBig.jpg" alt="" border="0" usemap="#MapBig" />
            </div>
        </div>
    </div>
    <map name="Map" id="Map"></map>
    	<map name="MapBig" id="MapBig">
    	<area shape="poly" coords="5,2,572,1,5,399" href="http://littlejackstavern.com/" target="blank" />
		<area shape="poly" coords="17,405,579,12,579,403" href="http://leonsoystershop.com/" target="blank" />
		<area shape="poly" coords="3,411,579,413,579,504,5,501" href="http://monzapizza.com/" target="blank" />
		</map>
    

    <?php endwhile; ?>
                    
	<?php else : ?>            
        
        <br /><br />
        <center><h1>Page Not Found</h1></center>
        <br /><br /> 		 
    
    <?php endif; ?>
 
<?php get_footer(); ?>