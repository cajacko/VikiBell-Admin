<!DOCTYPE html>
<html lang="en-GB" id="html">

	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/vendors/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/style.min.css">
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/pdf.css">		
	</head>

	<body>

	<article>

		<div class="article-container">

			<?php echo vikibell_pdf_pull_post_images(); ?>

		</div>

	</article>

	</body>
</html>