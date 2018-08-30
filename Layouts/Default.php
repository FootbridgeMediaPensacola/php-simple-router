<?php
	global $headContents;
	global $bodyContents;
?>
<html lang="en">
	<head>
		<link rel="stylesheet" href="/Assets/styles/theme.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script defer src="/Assets/js/bootstrap.bundle.min.js"></script>
		<?= $headContents; ?>
	</head>
	<body>
		<div id="header">
			<?php include(__DIR__  . "/../Partials/header.php"); ?>
		</div>
		<div id="navigation">
		<?php include(__DIR__  . "/../Partials/navigation.php"); ?>
		</div>
		<div id="page">
			<?= $bodyContents; ?>
		</div>
	</body>
</html>
