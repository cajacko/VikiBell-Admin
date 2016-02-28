<?php
/*
Plugin Name: Qzzr Shortcode Plugin
Description: Enables shortcode to embed Qzzr quizzes. Usage: <code>[qzzr quiz="123" width="100%" height="auto"]</code>. This code is available to copy and paste directly from the Qzzr share screen.
Version: 1.0.1
License: GPL
Author: Qzzr
Author URI: http://qzzr.co
*/

function createQzzrEmbedJS($atts, $content = null) {
	extract(shortcode_atts(array(
		'quiz'   => '',
		'height'     => 'auto',
		'width'     => '100%',
		'redirect'     => '',
		'offset'     => ''
	), $atts));


	if (!$quiz) {

		$error = "
		<div style='border: 20px solid red; border-radius: 40px; padding: 40px; margin: 50px 0 70px;'>
			<h3>Uh oh!</h3>
			<p style='margin: 0;'>Something is wrong with your Qzzr shortcode. If you copy and paste it from the Qzzr share screen, you should be good.</p>
		</div>";

		return $error;

	} else {

		wp_enqueue_script( 'qzzr', '//dcc4iyjchzom0.cloudfront.net/widget/loader.js',  array(), false, false ); 

		$qzzrHook = "<div class='quizz-container' data-quiz='$quiz' data-width='$width' data-height='$height'";
		
		if (filter_var($redirect, FILTER_VALIDATE_BOOLEAN)){
			$qzzrHook .= " data-auto-redirect='true'";
		}
		
		if ($offset){
			$qzzrHook .= " data-offset='$offset'";
		}

		$qzzrHook .= "></div>";

		return "$qzzrHook";

	}
}

add_shortcode('qzzr', 'createQzzrEmbedJS');

?>
