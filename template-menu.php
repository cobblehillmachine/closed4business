<?php
/*
Template Name: Menu
*/
?>
<?php get_header(); ?>
	
  <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>  
    
    <div id="menuHeader"></div>
    <div id="menuWrap">
    
	    <div id="menuText">
	    	<div id="menuSubtext">
	    		<img src="<?php bloginfo('template_directory'); ?>/images/preview-our-menus.jpg" border="0" alt="Preview Our Menus" />
	        </div>
	        <?php echo get_settings('c4b_content_menu'); ?>
	    </div>
	    
	    <?php $menus = new WP_query(array('post_type' => 'Menus', 'posts_per_page' => -1));
	    while($menus->have_posts()) : $menus->the_post(); ?>
	        <a href="<?php the_field('menu_file') ?>" target="_blank" class="menublock <?php the_title() ?>"></a>	      	
		<?php endwhile; wp_reset_query(); ?>
	    
	    <div style="width:735px; float:right; text-align:right; margin-top:47px;">Please Note: Our beer selection changes on a daily basis and this menu may not reflect all up-to-the-minute offerings &amp; prices.</div>
	    <div style="clear:both;"></div>	
    </div>
    
    <?php endwhile; ?>
                    
	<?php else : ?>            
        
        <br /><br />
        <center><h1>Page Not Found</h1></center>
        <br /><br /> 		 
    
    <?php endif; ?>
 
<?php get_footer(); ?>