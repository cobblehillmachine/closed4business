<?php get_header(); ?>

<div id="content">
    	<div id="sidebar">
        	<div id="browse" class="dotBot"><img src="<?php bloginfo('template_directory'); ?>/images/browse.jpg" border="0" alt="Browse" /></div>
            <div id="categories">
            	<img src="<?php bloginfo('template_directory'); ?>/images/by-subject.jpg" border="0" alt="By Subject" />
                
                <ul>
	                <?php wp_list_categories('orderby=name&order=ASC&hide_empty=0&title_li='); ?> 
                </ul>
                
            </div>
            <div id="months" class="dotBot">
            	<img src="<?php bloginfo('template_directory'); ?>/images/by-date.jpg" border="0" alt="By Date" />
                <!-- begin accordion -->                    
                                                
                    <ul class="accordion">
	                    <?php global $month, $year; 
	                    $arc_years = $wpdb->get_col("SELECT DISTINCT YEAR(post_date) FROM $wpdb->posts WHERE post_status = 'publish' ORDER BY post_date DESC LIMIT 3");
	                    foreach($arc_years as $arc_year) : ?>
	                    	<li><a title="open/close" style="display:block;" class="trigger open" href="#"><?php echo $arc_year; ?></a>
	                    
								<ul <?php if (date("Y") == $arc_year) { ?>class="current"<?php } ?>>
								<?php $arc_months = $wpdb->get_col("SELECT DISTINCT MONTH(post_date) FROM $wpdb->posts WHERE YEAR(post_date) = '$arc_year' AND post_status = 'publish' ORDER BY post_date DESC");
									foreach($arc_months as $arc_month): ?>
										<li><a href="<?php echo get_month_link($arc_year, $arc_month); ?> "><?php echo $month[zeroise($arc_month, 2)]; ?></a></li>
									<?php endforeach; // end foreach loop for archive months ?>
								</ul>
							</li>
	                    <?php endforeach; // end foreach loop for archive years ?>
                    </ul>


                <!-- end accordion -->
            </div>

            <div id="menus">
            	<img src="<?php bloginfo('template_directory'); ?>/images/preview-our-menus.jpg" border="0" alt="Preview Our Menus" />		
            	<?php $menus = new WP_query(array('post_type' => 'Menus', 'posts_per_page' => -1, 'orderby' => 'name', 'order' => 'ASC'));
			    while($menus->have_posts()) : $menus->the_post(); ?>
			        <a href="<?php the_field('menu_file') ?>" target="_blank" class=" <?php the_title() ?>"></a>	      	
				<?php endwhile; wp_reset_query(); ?>		
  
            </div>
            
            <div id="tags" style="visibility:hidden;">
            	<?php wp_tag_cloud(''); ?>
            </div>
        
        </div><!-- end sidebar -->
        <div id="main">
        	<div class="header"><img src="<?php bloginfo('template_directory'); ?>/images/header-bulletins.jpg" border="0" /></div>
            <div id="text" class="nopad dotTop">
            	
                <?php 
				global $query_string;
				query_posts($query_string . '&caller_get_posts=1');
				?>
  				<?php if(have_posts()) : ?><?php while(have_posts()) : the_post(); ?>  
  
   				<div class="excerpt">
                    <h1><?php the_title(); ?></h1>
                    <div class="date light">added on <?php the_time('F j'); ?></div>
                    <?php the_content(); ?>
                </div>
                
                <div id="post_tags" style="visibility:hidden;">
	                <?php the_tags(); ?>
                </div>
   				                
    
    <?php endwhile; ?>

                
                <div id="blognav">
                            <div class="blognav left"><?php previous_posts_link('', 0); ?></div><div class="blognav right"><?php next_posts_link('', 0) ?></div>
                </div>
        
           </div><!-- end text -->
        </div><!-- end main -->
        <div style="clear:both;"></div>
    </div><!-- end content -->
                    
	<?php else : ?>            
     
        <br /><br />
        <center><h1>Page Not Found</h1></center>
        <br /><br />		
    
    <?php endif; ?>
 
<?php get_footer(); ?>