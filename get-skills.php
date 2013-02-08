<?php namespace d3;
	$heroClass = getStr( "class" );
?><!DOCTYPE html>
<html>
	<head>
		<title><?= $heroClass ?> Skills</title>
		<link rel="stylesheet" href="/css/site.css" />
		<style type="text/css">
			textarea {
				height: 80em;
				width: 100%
			}
		</style>
		<script type="text/javascript">
			var heroClass = "<?= $heroClass ?>";
		</script>
		<script type="text/javascript" src="/js/jquery-1.9.0.min.js"></script>
		<script type="text/javascript" src="/js/skill-script.js"></script>
	</head>
	<body>
		<pre class="pre"></pre>
	</body>
</html>