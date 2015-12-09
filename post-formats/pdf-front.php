<!DOCTYPE html>
<html lang="en-GB" id="html">

	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/vendors/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/style.min.css">
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/pdf.css">		
	</head>

	<body id="pdf-title">
		<?php
		$urls = vikibell_pdf_pull_post_images( false );

		$length = count( $urls ); 

		if( $length > 10 ) {
			$length = 10;
		}

		?>

		<table id="pdf-photos-<?php echo $length; ?>">
			<tr>
				<td>
					<div><?php the_title(); ?></div>
					<div id="pdf-title-date"><?php the_date( 'jS F Y'); ?></div>
				</td>
			</tr>
		</table>

		<?php

		if( $length != 0 ):

			

			if( $length == 1 ) {
				$first_class = 1;
			} elseif( $length == 2 ) {
				$first_class = 2;
			} elseif( $length == 3 ) {
				$first_class = 3;
			} elseif( $length == 4 ) {
				$first_class = 4;
			} elseif( $length == 5 ) {
				$first_class = 2;
				$second_class = 3;
			} elseif( $length == 6 ) {
				$first_class = 3;
				$second_class = 3;
			} elseif( $length == 7 ) {
				$first_class = 2;
				$second_class = 4;
			} elseif( $length == 8 ) {
				$first_class = 4;
				$second_class = 4;
			} elseif( $length == 9 ) {
				$first_class = 4;
				$second_class = 5;
			} elseif( $length == 10 ) {
				$first_class = 5;
				$second_class = 5;
			}
			?>

			<table id="pdf-photos-first" class="pdf-photos pdf-<?php echo $first_class; ?>-columns">
				<tr>
					<?php for ($x = 0; $x < $first_class; $x++): ?>
						<td style="background-image: url( '<?php echo $urls[ $x ]; ?>' );"></td>
					<?php endfor; ?>
				</tr>
			</table>

			<?php 

			if( $length > 4 ): 


			?>

				<table id="pdf-photos-second" class="pdf-photos pdf-<?php echo $second_class; ?>-columns">
					<tr>
						<?php for ($x = $first_class; $x < $length; $x++): ?>
							<td style="background-image: url( '<?php echo $urls[ $x ]; ?>' );"></td>
						<?php endfor; ?>
					</tr>
				</table>

			<?php endif; ?>

		<?php endif; ?>

	</body>
</html>