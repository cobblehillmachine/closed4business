<?php
/*
Template Name: Home
*/
?>
<?php
    function currentPageURL() {
    $curpageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {$curpageURL.= "s";}
    $curpageURL.= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
    $curpageURL.= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
    $curpageURL.= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $curpageURL;
    }

if(isset($_POST['newsletter_signup'])) {

	if(trim($_POST['email']) === '')  {
		$emailError = 'Please enter your email address.';
		$hasError = true;
	} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
		$emailError = 'Please enter a valid email address!';
		$hasError = true;
	} else {
		$email = trim($_POST['email']);
	}

	if(!isset($hasError)) {
	
	//dupe check
	global $wpdb;
	$query = "SELECT * FROM cfb_newsletter WHERE email LIKE '$email'";
	$dupes = mysql_query($query);
	$num_results = mysql_num_rows($dupes);

		if ($num_results == 0){
			
			//insert into db if no dupe is found
			$datestamp = date("Ymd");
			global $wpdb;
			$wpdb->query("INSERT INTO cfb_newsletter(email, datestamp) VALUES ('$email', '$datestamp')");
			
		} 
		
		/* BEGIN CONSTANT CONTACT STUFF */
		$UN = "REVFood";
$PW = "cfb2011";
$Key = "58f00fa3-bea9-45c6-a636-5029f654b5bc";

$formemail = $email;

$request ="https://api.constantcontact.com/ws/customers/".$UN."/contacts?email=".$formemail."";
$session = curl_init($request);
$userNamePassword = $Key . '%' . $UN . ':' . $PW ;

curl_setopt($session, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($session, CURLOPT_USERPWD, $userNamePassword);
curl_setopt($session, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($session, CURLOPT_HEADER, false); // Do not return headers
curl_setopt($session, CURLOPT_RETURNTRANSFER, 1); // If you set this to 0, it will take you to a page with the http response

$response = curl_exec($session);
curl_close($session);

/*print_r($response);*/

if (isset($response)){

	$data = simplexml_load_string($response);
	if ($data->entry) { //if there is an entry for this email address then we know that it exists
		
		foreach ($data->entry as $item){
						
			/*$email = $item->content->Contact->EmailAddress;*/

			$att = 'href';
			$contactID = $item->link->attributes()->$att;
			/*print_r($response);*/
			
			// THIS EMAIL EXISTS SO JUST UPDATE THE CONTACT
			//Credentials here
			define("USERNAME", "REVFood");
			define("PASSWORD", "cfb2011");
			define("APIKEY", "58f00fa3-bea9-45c6-a636-5029f654b5bc");
			
			// Method to send http requests
			function httpRequest($request, $type = 'GET', $parameter = '')
			{
					$ch = curl_init();
				
					curl_setopt($ch, CURLOPT_URL, $request);
					curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
					curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
					curl_setopt($ch, CURLOPT_USERPWD, APIKEY."%".USERNAME.":".PASSWORD);
					curl_setopt($ch, CURLOPT_HEADER, false);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type:application/atom+xml", 'Content-Length: ' . strlen($parameter)));
					curl_setopt($ch, CURLOPT_FAILONERROR, 1);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $parameter);
					
					
					$return['xml'] = curl_exec($ch);
					/*$return['info'] = curl_getinfo($ch);
					$return['error'] = curl_error($ch);*/
					
					return $return;
					
			} //end httpRequest function
			
			// Get the current contact details
			$ans = httpRequest('https://api.constantcontact.com'.$contactID, 'GET');
			$xml = simplexml_load_string($ans['xml']);
			
			
			if ($xml->content->Contact->ContactLists) {
			
				// Add the new contact list id to the existing XML
				$lists = $xml->content->Contact->ContactLists->addChild("ContactList");
				$lists->addAttribute("id", 'https://api.constantcontact.com/ws/customers/'.USERNAME.'/lists/10');
				$xml = $xml->asXML();
			
			} else {
				
				// Need to create the ContactLists node first before we can add a single child ContactList to it
				$Contact = $xml->content->Contact;
				$Contact->addChild("ContactLists","");
				$xmladd = $xml->asXML();
				$addContactLists = httpRequest('https://api.constantcontact.com'.$contactID, 'PUT', $xmladd);
				
				$lists = $xml->content->Contact->ContactLists->addChild("ContactList");
				$lists->addAttribute("id", 'https://api.constantcontact.com/ws/customers/'.USERNAME.'/lists/10');
				$xml = $xml->asXML();
			
			}
			
			//Sent HTTP Put request to update contact
			$response = httpRequest('https://api.constantcontact.com'.$contactID, 'PUT', $xml);
			
			$newHeader = currentPageURL();
			header("Location: ".$newHeader."?signup=true");
						
		} //end foreach
		
	} else {
		
		// THIS EMAIL DOESN'T EXIST YET SO ADD A NEW ONE
		// fill in your Constant Contact login and API key
		$ccuser = 'REVFood';
		$ccpass = 'cfb2011';
		$cckey  = '58f00fa3-bea9-45c6-a636-5029f654b5bc';
		
		// fill in these values 
		$emailAddr  = $formemail;
		
		// represents the contact list identification number(s)
		$contactListId = 10;
		$contactListId = (!is_array($contactListId)) ? array($contactListId) : $contactListId;
		
		$post = new SimpleXMLElement('<entry></entry>');
		$post->addAttribute('xmlns', 'http://www.w3.org/2005/Atom');
		
		$title = $post->addChild('title', "");
		$title->addAttribute('type', 'text');
		
		$post->addChild('updated', date('c'));
		$post->addChild('author', "");
		$post->addChild('id', 'data:,none');
		
		$summary = $post->addChild('summary', 'Contact');
		$summary->addAttribute('type', 'text');
		
		$content = $post->addChild('content');
		$content->addAttribute('type', 'application/vnd.ctct+xml');
		
		$contact = $content->addChild('Contact');
		$contact->addAttribute('xmlns', 'http://ws.constantcontact.com/ns/1.0/');
		
		$contact->addChild('EmailAddress', $emailAddr);
		$contact->addChild('OptInSource', 'ACTION_BY_CUSTOMER');
		
		$contactlists = $contact->addChild('ContactLists');
		
		// loop through each of the defined contact lists
		foreach($contactListId AS $listId) {
			$contactlist = $contactlists->addChild('ContactList');
			$contactlist->addAttribute('id', 'http://api.constantcontact.com/ws/customers/' . $ccuser . '/lists/' . $listId);
		}
		
		$posturl = "https://api.constantcontact.com/ws/customers/{$ccuser}/contacts";
		$authstr = $cckey . '%' . $ccuser . ':' . $ccpass;
		
		/* MULTIPLE CURL */
		// create both cURL resources
		$ch = curl_init();


		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL, $posturl);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, $authstr);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post->asXML());
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type:application/atom+xml"));
		curl_setopt($ch, CURLOPT_HEADER, false); // Do not return headers
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // If you set this to 0, it will take you to a page with the http response
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		
		curl_exec($ch);
		curl_close($ch);

		$newHeader = currentPageURL();
		header("Location: ".$newHeader."?signup=true");

		
		
	}
	
} 
		/* END CONSTANT CONTACT STUFF */
	
	/*$signupSuccess = true;*/
		
	}

} ?><?php get_header(); ?>
	
  <?php if(have_posts()) : ?>
  <?php while(have_posts()) : the_post(); ?>  
    
    <div id="featured">
    	<img src="<?php bloginfo('template_directory'); ?>/images/image-01.jpg" alt="Alt Tag" border="0" width="950" height="282" />
    </div>
    <div id="homeWrap">

		<style type="text/css">
        #homeLeft_new {
            width:557px;
            border-right:2px solid #dcdddd;
            background:url(<?php bloginfo('template_directory'); ?>/images/home-left-bg.jpg) 0 0 no-repeat;
            float:left;
            padding:166px 0 0 0;
            text-align:center;
        }	
		#home_left_text {
			width:447px;
			padding:0 50px 20px 36px;	
			margin:0 0 25px 0;
			border-bottom:2px solid #dcdddd;			
		}
		#home_left_text p {
			font-weight:bold;
			font-size:12px;
			color:#666;
		}
		
        
        #homeRight_new {
            width:316px;
            float:right;
			padding:24px 66px 0 0;
        }
		#home_events_title {
			height:58px 0 0 10px;	
		}
		#home_events_title a {
			display:block;
			float:left;
			background:url(<?php bloginfo('template_directory'); ?>/images/home-title-events.jpg) 0 0 no-repeat;
			width:151px;
			height:16px;
			padding:42px 0 0 74px;
			text-align:left;
			text-indent:-9999em;
			color:#666;
			font-style:italic;
			font-size:10px;
			font-weight:normal;
		}
		#home_events_title a:hover {
			text-indent:0;	
		}
		
		
		#bulletins_new {
			height:58px;
			padding:0 0 0 10px;
		}	
		#bulletins_new a {
			display:block;
			float:left;
			background:url(<?php bloginfo('template_directory'); ?>/images/home-title-bulletins.jpg) 0 0 no-repeat;
			width:190px;
			height:16px;
			padding:42px 0 0 74px;
			text-align:left;
			text-indent:-9999em;
			color:#666;
			font-style:italic;
			font-size:10px;
			font-weight:normal;
		}
		#bulletins_new a:hover {
			text-indent:0;	
		}
		#bulletins_list {
			text-align:left;	
			padding:10px 112px 0 18px;
		}
		#bulletin_div {
			height:3px;
			width:304px;
			clear:both;
			background:url(<?php bloginfo('template_directory'); ?>/images/bulletin-dot.png) 0 0 repeat-x;
		}
        .bulletin_new {
            /*height:53px;*/
            padding:6px 0 6px 0;
            font-weight:bold;
            overflow:hidden;
			font-size:11px;
        }
        .bulletin_new .bulletinDate {
			font-size:11px;
            text-transform:uppercase;
        }
        .bulletin_new .bulletinDate a{
            color:#c96752;	
        }
        .bulletin_new .bulletinDate a:hover {
            color:#c96752;
        }
        .bulletinExcerpt {
            height:14px;
            overflow:hidden;
        }
        .bulletin_new a {
            color:#666;	
        }
        .bulletin_new a:hover {
            color:#000;	
        }
		
		#events_list {
			text-align:left;	
			padding:25px 0 0 8px;
		}
        .event {
            padding:0 0 13px 0;
			margin-bottom:13px;
            font-weight:bold;
            overflow:hidden;
			font-size:11px;
			background:url(<?php bloginfo('template_directory'); ?>/images/bulletin-dot.png) bottom repeat-x;
        }
        .event .eventDate {
			font-size:11px;
			height:16px;
			overflow:hidden;
        }
        .event .eventDate a{
            color:#c96752;	
        }
        .event .eventDate a:hover {
            color:#c96752;
        }
        .eventExcerpt {
			line-height:12px;
            height:24px;
            overflow:hidden;
        }
        .event a {
            color:#666;	
        }
        .event a:hover {
            color:#000;	
        }
		
		#newsletter_div {
			width:387px;
			height:2px; 
			margin-left:-8px;
			background:#dcdddd;
		}
		
		#newsletter_new {
			margin-top:26px;
			padding:44px 0 0 8px;
            background-image:url(<?php bloginfo('template_directory'); ?>/images/home-title-newsletter.jpg);
            background-position:8px 0;
            background-repeat:no-repeat;
        }
        #newsletterForm_new .error {
            font-size:11px;
            color:#b6533e;
            margin-bottom:5px;
        }
        #newsletterForm_new .success {
            font-size:12px;
            font-style:italic;
            font-weight:bold;
            color:#444;
        }
        #newsletterForm_new input[type="text"], #newsletterForm_new button {
            display:block;
            height:24px;
            float:left;
            margin:0;
            padding:0;
            outline:none;
            border:none;
            background-image:url(<?php bloginfo('template_directory'); ?>/images/newsletter-form-new.jpg);
            background-repeat:no-repeat;
            overflow:hidden;
        }
        #newsletterForm_new input[type="text"] {
            width:270px;
            padding-left:4px;
            background-position:0 0;
            font-family:Georgia, "Times New Roman", Times, serif;
            color:#999;
            font-size:13px;
            line-height:24px;
        }
        #newsletterForm_new button {
            width:32px;
            background-position:-274px 0;
            cursor:pointer;
        }
        #newsletterForm_new button:hover {
            background-position:-306px 0;	
        }
		</style>
        <div id="homeLeft_new">  
        	<div id="home_left_text" style="border-bottom:none;">
            	<?php echo get_settings('c4b_content_home'); ?>
            </div>           
        </div><!-- end homeLeft --> 
        <div id="homeRight_new">
            
            <div id="home_events_title"><a href="<?php bloginfo('url'); ?>/category/events/">See All</a></div>
            <div style="clear:both;"></div>
			<div id="events_list"> 
            
            <!-- stickies first -->
            <?php
          	$sticky=get_option('sticky_posts') ;
			if($sticky) {
				$args = array(
					'category__in' => 6,
					'posts_per_page' => 5,
					'post__in'  => get_option('sticky_posts'),
				);
				$testLoop = new WP_Query();
				$testLoop->query($args);
	
				while ($testLoop->have_posts()) : $testLoop->the_post(); ?>
						  
					<div class="event">
						<div class="eventDate"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
						<div class="eventExcerpt"><a href="<?php the_permalink(); ?>"><?php echo strip_tags(get_the_excerpt());?></a></div>
					</div>
						
				<?php endwhile; ?>
				<?php wp_reset_query(); ?> 
            	<?php $sticky_count = $testLoop->post_count; ?>
                
            <?php } else {
				
					$sticky_count = 0;
					
			} ?>

            <!-- non stickies next -->
            
            <?php
			$sticky=get_option('sticky_posts') ;
			$args = array(
				'category__in' => 6,
				'posts_per_page' => (5 - $sticky_count),
				'post__not_in'  => get_option('sticky_posts')
			);
			$testLoop = new WP_Query();
			$testLoop->query($args);

	        while ($testLoop->have_posts()) : $testLoop->the_post(); ?>
                  
                <div class="event">
                    <div class="eventDate"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
                    <div class="eventExcerpt"><a href="<?php the_permalink(); ?>"><?php echo strip_tags(get_the_excerpt());?></a></div>
                </div>
                    
            <?php endwhile; ?>
<!--             <?php wp_reset_query(); ?>  -->
                            
            </div>
            
            </div>
            
        </div><!-- end homeRight -->

        <div style="clear:both;"></div>
    </div> 
    
    <?php endwhile; ?>
                    
	<?php else : ?>            
        
        <br /><br />
        <center><h1>Page Not Found</h1></center>
        <br /><br /> 		 
    
    <?php endif; ?>
 
<?php get_footer(); ?>