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
			<a id="top-of-page"></a>
			
			<section id="banner" style="background-image: url('http://placehold.it/2500x400');">
				<div id="banner-wrap" class="embed-responsive">
					<div class="embed-responsive-item"></div>
				</div>
				<img id="banner-img" class="embed-responsive-item hide-without-javascript" src="http://placehold.it/2500x400" height="400" width="2500">
			</section>
			
			<header id="site-navigation" class="<?php vikibell_the_site_nav_classes(); ?>">
				
				<?php get_template_part( 'sections/site-navigation' ); ?>
				
			</header>
			
			<main id="<?php vikibell_the_main_id(); ?>">
				
				<div id="main-wrap" class="wrap clearfix">