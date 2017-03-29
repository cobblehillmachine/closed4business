<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><?php global $wp_query; $postid = $wp_query->post->ID; ?>
<meta property="og:title" content="Closed for Business" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php echo the_permalink($postid); ?>" />
<meta property="og:image" content="http://closed4business.com/wp-content/themes/c4b/images/facebook-thumbnail.jpg" />
<meta property="og:site_name" content="closed4business.com"/>
<meta property="og:description" content="<?php if (get_settings('c4b_meta_desc') != '' ) { echo get_settings('c4b_meta_desc'); } ?>" />
<title><?php bloginfo('name'); ?> <?php if (is_front_page()) { echo " - ".get_bloginfo('description'); } else { wp_title(" &raquo; ",true); } ?></title>
<link rel="icon" href="<?php bloginfo('url'); ?>/favicon.ico" type="image/x-icon" />
<link rel="shortcut icon" href="<?php bloginfo('url'); ?>/favicon.ico" type="image/x-icon" /> 
<?php if (get_settings('c4b_meta_desc') != '' ) { echo "<meta name=\"description\" content=\"".get_settings('c4b_meta_desc')."\" />\n"; } ?>
<?php if (get_settings('c4b_meta_keywords') != '' ) { echo "<meta name=\"keywords\" content=\"".get_settings('c4b_meta_keywords')."\" />\n"; } ?>
   
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/style.css" type="text/css" media="screen" />

<?php if (is_front_page()) { ?>
<style type="text/css">
@font-face {
    font-family: 'LeagueGothicRegular';
    src: url('<?php bloginfo('template_directory'); ?>/images/oktoberfest/fonts/League_Gothic-webfont.eot');
    src: url('<?php bloginfo('template_directory'); ?>/images/oktoberfest/fonts/League_Gothic-webfont.eot?#iefix') format('embedded-opentype'),
         url('<?php bloginfo('template_directory'); ?>/images/oktoberfest/fonts/League_Gothic-webfont.woff') format('woff'),
         url('<?php bloginfo('template_directory'); ?>/images/oktoberfest/fonts/League_Gothic-webfont.ttf') format('truetype'),
         url('<?php bloginfo('template_directory'); ?>/images/oktoberfest/fonts/League_Gothic-webfont.svg#LeagueGothicRegular') format('svg');
    font-weight: normal;
    font-style: normal;
}
#oktoberfest {
	font:1em/1.5em "Helvetica Neue", Arial, Helvetica, Geneva, sans-serif;
	text-align:center;
	width:970px;
	height:118px;
	margin:14px 0 40px 4px;
	background:url(<?php bloginfo('template_directory'); ?>/images/oktoberfest/banner.jpg) 0 0 no-repeat;
	color:#f9da09;
	position:relative;
}
#oktoberfest a {
	display:block;
	position:absolute;
	cursor:pointer;
	text-align:left;
	text-indent:-9999em;
}
	#oktoberfest a.cfb {
		width:77px;
		height:77px;
		top:15px;
		left:37px;
	}
	#oktoberfest a.tra {
		width:89px;
		height:54px;
		top:39px;
		left:195px;
	}
#oktoberfest span {
	display:block;
	width:62px;
	height:71px;
	position:absolute;
	top:14px;
	font:56px "LeagueGothicRegular";
	text-align:center;
	line-height:71px;
}
#oktoberfest div.line {
	width:60px;
	height:71px;
	background:url(<?php bloginfo('template_directory'); ?>/images/oktoberfest/line.png) 0 0 no-repeat;
	position:absolute;
	top:14px;
}
	#oktoberfest span.ml-countdown.days, #oktoberfest div.line.days {
		left:599px;
	}
	#oktoberfest span.ml-countdown.hours, #oktoberfest div.line.hours {
		left:714px;
	}
	#oktoberfest span.ml-countdown.minutes, #oktoberfest div.line.minutes {
		left:834px;
	}
	#oktoberfest span.ml-countdown.seconds {
		display:none;
	}
</style>
<?php } ?>

<script src="<?php bloginfo('template_directory'); ?>/js/jquery-1.4.3.min.js" type="text/javascript"></script>

<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/js/prettyPhoto.css" type="text/css" media="screen" />
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.prettyPhoto.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.nestedAccordion.js" type="text/javascript"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.cycle.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.slideshow').cycle({
		fx: 'fade',
		timeout: 4000,
		prev: '.prev',
		next: '.next',
		after: function() {
			$('.mid').html(this.alt);
		}        
	});						   
});
</script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.svTruncate.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
<!--						   
	<?php if (is_front_page()){ ?>
	function mlCountdown() {

		var eventTime = '1380988800'; // Unix Event Time - Get your stamp from http://www.unixtimestamp.com/index.php
				
		var currentTime = Math.round(new Date().getTime() / 1000); // Grabs current time in seconds
		var timeLeft = eventTime - currentTime;			 
	
			var dayLeft = Math.floor(timeLeft / 60 / 60 / 24);
			var hrLeft1 = dayLeft * 24 * 60 * 60; // days left in seconds
			var hrLeft2 = timeLeft - hrLeft1;
			var hrLeft = Math.floor(hrLeft2 / 60 / 60);
			var minLeft1 = hrLeft * 60 * 60; // hours left in seconds
			var minLeft2 = timeLeft - hrLeft1 - minLeft1;
			var minLeft = Math.floor(minLeft2 / 60);
			var scLeft1 = minLeft * 60; // minutes left in seconds
			var scLeft2 = timeLeft - hrLeft1 - minLeft1 - scLeft1;
			var scLeft = Math.floor(scLeft2);
				
			$(".ml-countdown.days").html(dayLeft);
			$(".ml-countdown.hours").html(hrLeft);
			$(".ml-countdown.minutes").html(minLeft);
			$(".ml-countdown.seconds").html(scLeft);

	}
	
	window.onload=mlCountdown;
	window.setInterval( mlCountdown, 1000);
	<?php } ?>
-->						   
	$("#bulletins div.bulletin:last-child").css("background-image","none"); 
	$('.menublock a').svTruncate({maxTextLen:195});
	$("#months").accordion({ // see the website http://www.whatssocialmedia.co.uk/maclennanwordpress/news-room
		initShow: "ul.current",
		showSpeed: 200,
		hideSpeed: 200,
	});
	$("a[rel^='prettyPhoto']").prettyPhoto({
		show_title: false,
		overlay_gallery: false
	});
	

	$('#events_list .event:last').css('background','none');	
	
	<?php if (is_page('friends')) { ?>
	$("#rect_poes").hover(
	  function () {
		$("#friend_poes").addClass("hover");
	  }, function() {
		$("#friend_poes").removeClass("hover"); 
	  }
	);
	$("#rect_taco").hover(
	  function () {
		$("#friend_taco").addClass("hover");
	  }, function() {
		$("#friend_taco").removeClass("hover"); 
	  }
	);
	$("#rect_monza").hover(
	  function () {
		$("#friend_monza").addClass("hover");
	  }, function() {
		$("#friend_monza").removeClass("hover"); 
	  }
	);
	$("#rect_tra").hover(
	  function () {
		$("#friend_tra").addClass("hover");
	  }, function() {
		$("#friend_tra").removeClass("hover"); 
	  }
	);
	<?php } ?>
	
});
</script>

<!--[if IE 6]>
<script type="text/javascript"> 
    var IE6UPDATE_OPTIONS = {
        icons_path: "<?php bloginfo('template_directory'); ?>/js/ie6/images/"
    }
</script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/ie6/ie6update.js"></script>
<![endif]-->
<?php if (get_settings('c4b_google_analytics') != '' ) { echo get_settings('c4b_google_analytics')."\n"; } ?>
<link rel="alternate" type="application/rss+xml" title="RSS" href="http://closed4business.com/feed" />
<link rel="EditURI" type="application/rsd+xml" title="RSD" href="http://www.closed4business.com/xmlrpc.php?rsd" />
<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="http://www.closed4business.com/wp-includes/wlwmanifest.xml" /> 
<link rel='index' title='Closed for Business' href='http://www.closed4business.com/' />
<link rel='canonical' href='http://www.closed4business.com/' />
<?php wp_head(); ?>
</head>
<body>
<div id="wrapper">
	
    <?php if ($post->ID == '19') { $navclass = ' class="home"'; } ?>
    <?php if ($post->ID == '9') { $navclass = ' class="gallery"'; } ?>
    <?php if ($post->ID == '5') { $navclass = ' class="menus"'; } ?>
    <?php if ($post->ID == '7') { $navclass = ' class="press"'; } ?>
    <?php if ($post->ID == '14') { $navclass = ' class="friends"'; } ?>  
      

	<div id="nav"<?php echo $navclass; ?>>
    	<a href="<?php bloginfo('url'); ?>/menu" class="nav menu" title="Menu"></a>
        <a href="<?php bloginfo('url'); ?>/press" class="nav press" title="Press"></a>
        <a href="<?php bloginfo('url'); ?>/gallery" class="nav gallery" title="Gallery"></a>
        <a href="<?php bloginfo('url'); ?>" class="nav home" title="Closed for Business"></a>
        <a href="<?php bloginfo('url'); ?>/bulletins" class="nav bulletins" title="Bulletins"></a>
        <a href="<?php bloginfo('url'); ?>/friends" class="nav friends" title="Friends"></a>
    </div>
    
    
    
    