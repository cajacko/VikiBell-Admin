<?php

	function vikibell_is_pdf() {
		if( isset( $_GET[ 'action' ] ) && 'pdf' == $_GET[ 'action' ] ) {
			return true;
		} else {
			return false;
		}
	}

	function vikibell_is_pdf_front() {
		if( isset( $_GET[ 'type' ] ) && 'front' == $_GET[ 'type' ] ) {
			return true;
		} else {
			return false;
		}
	}

	function vikibell_process_pdf() {
		set_time_limit( 100000000000000000 );

		putenv('PATH=C:\Program Files\wkhtmltopdf\bin' . PATH_SEPARATOR . 'C:\Program Files (x86)\PDFtk Server\bin' . PATH_SEPARATOR . '/usr/local/bin');
		chdir ( 'C:/Data/Personal/vikibell/blog/live/wp-content/themes/vikibell/media' );

		$args = array(
			'posts_per_page' => -1,
			'author_name' => 'viki',
		);

		$posts = get_posts( $args );
		$count = 1;

		foreach( $posts as $post ) {
			vikibell_post_to_pdf( $post->ID, $count );
			//vikibell_post_to_pdf( 686, $count );
			$count++;
		}

		exit;
	}

	function vikibell_post_to_pdf( $id = 924, $count = 0 ) {
		vikibell_create_pdf( 'front', $id, $count );
		vikibell_create_pdf( 'content', $id );
		vikibell_combine_pdf( $count );
	}

	function vikibell_create_pdf( $type = 'content', $id = 924, $count = false ) {

		$command = 'wkhtmltopdf --dpi 600 --image-dpi 600 --margin-left 0mm --margin-right 0mm --page-height 210mm --page-width 148mm --no-outline ';

		if( 'front' == $type ) {
			$command .= '--margin-bottom 0mm --margin-top 0mm ' . escapeshellarg( 'vikibell.local.com/?action=pdf&type=front&post=' . $id . '&count=' . $count ) . ' front.pdf';
		} else {
			$command .= '--margin-bottom 15mm --margin-top 15mm ' . escapeshellarg( 'vikibell.local.com/?action=pdf&type=content&post=' . $id ) . ' content.pdf';
		}

		echo $command . '<br><br>';

		shell_exec( $command );

		//sleep( 1 );

	}

	function vikibell_combine_pdf( $count = 1 ) {

		$command = 'pdftk content.pdf cat 1-r2 output temp.pdf';
		shell_exec( $command );

		//sleep( 1 );

		if( 1 == $count ) {
			@unlink( 'vikibell.pdf' );
			$command = 'pdftk front.pdf temp.pdf cat output concat.pdf';
			shell_exec( $command );
		} else {
			$command = 'pdftk vikibell.pdf front.pdf temp.pdf cat output concat.pdf';
			shell_exec( $command );
		}

		//sleep( 1 );

		

		@unlink( 'temp.pdf' );
		unlink( 'front.pdf' );
		unlink( 'content.pdf' );
		@unlink( 'vikibell.pdf' );

		rename("concat.pdf", "vikibell.pdf");
		//sleep( 1 );
	}

	function vikibell_replace_images( &$content, $matches, &$urls ) {

		foreach( $matches[0] as $match ) {

			preg_match ( '/src="(.+?)"/' , $match, $url );
			$urls[] = $url[ 1 ];

			$content = str_replace( $match, '', $content );
		}
	}

	function vikibell_pdf_process_regex( $regex, &$content, &$urls ) {
		preg_match_all( $regex , $content, $matches );
		vikibell_replace_images( $content, $matches, $urls );
	}

	function vikibell_pdf_pull_post_images( $return_content = true ) {
		$content = vikibell_get_the_content_with_formatting();
		$urls = array();

		preg_match_all( '/<div class="embed-responsive.+?<\/div>/s' , $content, $matches );

		foreach( $matches[0] as $match ) {
			preg_match ( '/src="(.+?)"/' , $match, $url );
			$content = str_replace( $match, '<p class="pdf-link">Visit: ' . $url[ 1 ] . '</p>', $content );
		}


		$content = str_replace( '<div style="color: rgba(0, 0, 0, 0.701961); font-family: UICTFontTextStyleBody; -webkit-tap-highlight-color: rgba(26, 26, 26, 0.301961); -webkit-composition-fill-color: rgba(130, 98, 83, 0.0980392); text-decoration: -webkit-letterpress;">', '<div>', $content );
		$content = str_replace( '<div>', '<p>', $content );
		$content = str_replace( '</div>', '</p>', $content );
		$content = str_replace( "<p></p>", '', $content );
		
		
		vikibell_pdf_process_regex( "/<p><img.*><\/p>/", $content, $urls );
		vikibell_pdf_process_regex( "/<p><a.+?><img.+?><\/a><\/p>/s", $content, $urls );
		vikibell_pdf_process_regex( "/<div><a.+?><img.+?><\/a><\/div>/s", $content, $urls );
		vikibell_pdf_process_regex( "/<img.+?>/", $content, $urls );

		array_unique( $urls );

		if( $return_content ) {
			return $content;
		} else {
			return $urls;
		}
	}

	if( isset( $_GET[ 'action' ] ) && 'process_pdf' == $_GET[ 'action' ] ) {
		vikibell_process_pdf();
	}

	if( isset( $_GET[ 'action' ] ) && 'pdf' == $_GET[ 'action' ] && isset( $_GET[ 'post' ] ) && is_numeric( $_GET[ 'post' ] ) )  {
		$post = get_post( $_GET[ 'post' ] );
		setup_postdata( $post ); 

		if( isset( $_GET[ 'type' ] ) && 'front' == $_GET[ 'type' ] ) {
			get_template_part( 'post-formats/pdf-front' );
		} else {
			get_template_part( 'post-formats/pdf-content' );
		}

		wp_reset_postdata();

		exit;
	}