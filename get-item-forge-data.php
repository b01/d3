<?php namespace D3;
	$itemType = getStr( "type" );
	$itemClass = getStr( "class" );
?><!DOCTYPE html>
<html>
	<head>
		<title><?= $itemClass ?></title>
		<meta name="encoding" content="UTF-8" />
		<link rel="stylesheet" href="/css/site.css" />
		<script type="text/javascript">
			var itemType = "<?= $itemType ?>",
				itemClass = "<?= $itemClass ?>";
		</script>
		<script type="text/javascript" src="/js/jquery-2.0.0.min.js"></script>
		<script type="text/javascript" src="/js/skill-script.js"></script>
		<script type="text/javascript" src="/js/battle-net-url-parsers.js"></script>
		<script type="text/javascript" src="/js/item-forge.js"></script>
	</head>
	<body>
		<pre class="pre"></pre>
	</body>
</html>