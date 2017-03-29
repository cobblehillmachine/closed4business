<?php get_header(); ?>
	
  <?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>  
    
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>
    
    <?php endwhile; ?>
                    
	<?php else : ?>            
        
        <br /><br />
        <center><h1>Page Not Found</h1></center>
        <br /><br />
    
    <?php endif; ?>
 
<?php get_footer(); ?>