<?php
/*
Template Name: Press
*/
?>
<?php get_header(); ?>
	
  <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>  
    
    <div id="pressHeader"></div>
    <div id="pressWrap">
		<div id="pressLeft"></div>
        <div id="pressRight">
   
            <?php $press = new WP_query(array('post_type' => 'Press', 'posts_per_page' => -1));
            while($press->have_posts()) : $press->the_post(); ?>
                
				<div class="pressPiece">	
					<img src="<?php the_field('press_logo'); ?>" style="max-width: 150px">
					<a href="<?php the_field('external_link') ?>" target="_blank"><?php the_content(); ?></a>
					<span>added on <?php the_time('F Y') ?></span>
				</div>
			      	
			<?php endwhile; wp_reset_query(); ?>	              
            
        </div>
        <div style="clear:both;"></div>
    </div><!-- end press -->
    
    <?php endwhile; ?>
                    
	<?php else : ?>            
        
        <br /><br />
        <center><h1>Page Not Found</h1></center>
        <br /><br /> 		 
    
    <?php endif; ?>
 
<?php get_footer(); ?>