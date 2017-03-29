<?php
/*
Template Name: Gallery
*/
?>
<?php get_header(); ?>
	
  <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>  
    
    <div id="galleryHeader"></div>
    <div id="gallery">
        <div id="vignette"></div>
      	<div id="slideshow" class="slideshow">	
	      	<?php if( have_rows('gallery_images', 589) ):
			    while ( have_rows('gallery_images', 589) ) : the_row(); ?>
			        <div class="slide"><img src="<?php the_sub_field('gallery_image'); ?>" width="950" height="468" border="0" /></div>			
			    <?php endwhile;
			endif; ?>          
        </div>
        <div id="galleryNav">    
        	<div class="gal left"><a href="javascript:void(0)" class="galnav prev"></a></div>
            <div class="gal mid"></div>
            <div class="gal right"><a href="javascript:void(0)" class="galnav next"></a></div>
            <div style="clear:both;"></div>
        </div>
    </div>

    
    <?php endwhile; ?>
                    
	<?php else : ?>            
        
        <br /><br />
        <center><h1>Page Not Found</h1></center>
        <br /><br /> 		
    
    <?php endif; ?>
 
<?php get_footer(); ?>