<?php
/**
 * The header for the Charlie Jackson theme.
 *
 * Displays all of the <head> section and everything up till <div id="main-wrap">
 *
 * @package Charlie Jackson
 */
 
 	//Used to load posts for an infinite scroll effect
	if( $_GET['action'] == 'load_posts' ){
		get_template_part( 'sections/post-loop' ); //Skip all the head info and load only the posts html
		exit; //Prevent any other html or scripts from rendering
	} 
?>
	<!DOCTYPE html>
	<html lang="en-GB" id="html" data-home-url="<?php echo home_url( '/' ); ?>" class="no-javascript">
	
		<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta name="author" content="<?php echo get_bloginfo( 'name' ); ?>">
			<meta property="og:description" content="<?php bloginfo( 'description' ); ?>" />
			<meta id="less-vars">
			<title><?php wp_title( '|', true, 'right' ); ?> <?php echo get_bloginfo( 'name' ); ?></title>
			<link rel="author" href="<?php echo home_url(); ?>">
			<link rel="profile" href="http://gmpg.org/xfn/11">
			<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/media/favicon/favicon.ico" />
			<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
			<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/vendors/font-awesome/css/font-awesome.min.css">
			<?php wp_head(); ?>		
		</head>
	
		<body>
			<!-- Google Tag Manager -->
			<noscript>
				<iframe src="//www.googletagmanager.com/ns.html?id=GTM-5X2ZMF" height="0" width="0" style="display:none;visibility:hidden"></iframe>
			</noscript>
			<script>
				(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
				new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
				j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
				'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
				})(window,document,'script','dataLayer','GTM-5X2ZMF');
			</script>
			<!-- End Google Tag Manager -->
			
			<a id="top-of-page"></a>
			
			<a id="banner" style="background-image: url('/wp-content/themes/vikibell/media/banner.jpg');" href="<?php echo home_url( '/' ); ?>">
				<div id="banner-wrap" class="embed-responsive">
					<div class="embed-responsive-item"></div>
				</div>
				<img id="banner-img" class="embed-responsive-item hide-without-javascript image-fill-container" src="/wp-content/themes/vikibell/media/banner.jpg" height="400" width="2500">
			</a>
			
			<header id="site-navigation" class="static">
				
				<?php get_template_part( 'sections/site-navigation' ); ?>
				
			</header>
			
			<main id="<?php vikibell_the_main_id(); ?>">
				
				<div id="main-wrap" class="wrap clearfix">