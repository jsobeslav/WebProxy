<?php
/**
 * Fakes webpage that is shown after posting a HTML form with POST method.
 * That hypothetical form would include file 'image' and some other text fields.
 */
?>

<html>
	<head>
		<title>
			Form action
		</title>
	</head>
	<body>
		<header>
			<h1>Heading</h1>
		</header>
		<main>
			<div class="result">
				<?php

				if (! empty($_FILES['image'])) {
					echo 'File `image` was supplied. ';
				} else {
					echo 'File `image` was NOT supplied. ';
				}

				echo 'POST body: ';
				echo(json_encode($_POST));

				?>
			</div>
		</main>
		<footer>

		</footer>
	</body>
</html>

