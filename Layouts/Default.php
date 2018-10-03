<?php
	require_once(__DIR__ . "/../Classes/Internals.php");

	global $headContents;
	global $bodyContents;
?>
<html lang="en">
	<head>
		<link rel="stylesheet" href="/Assets/styles/theme.css" />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script defer src="/Assets/js/bootstrap.bundle.min.js"></script>
		<script defer src="/Assets/js/image-uploader.min.js"></script>
		<?= $headContents; ?>
	</head>
	<body class="">
		<div class="">
			<div id="header">
				<?php include(__DIR__  . "/../Partials/header.php"); ?>
			</div>
			<div id="navigation">
				<?php include(__DIR__  . "/../Partials/navigation.php"); ?>
			</div>
			<div id="page" class="mx-auto">
				<div class="container-fluid my-4">
					<?= $bodyContents; ?>
				</div>
			</div>
		</div>
		<?php include(__DIR__  . "/../Partials/footer.php"); ?>
	</body>
</html>
