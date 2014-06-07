<?php namespace kshabazz\d3a;
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
		<script type="text/javascript" src="/js/jquery-2.0.0.min.js"></script>
		<script type="text/javascript" src="/js/skill-script.js"></script>
	</head>
	<body>
		<pre class="pre">{
	"active": <span class="active"></span>,
	"passive": <span class="passive"></span>
}</pre>
	</body>
</html>