<?php namespace kshabazz\d3a;

?>
<?php if ( $item instanceof Item ): ?>
<?php if ( $showExtra ): ?>
<!DOCTYPE html>
<html>
	<head>
		<title><?= $item->name ?></title>
		<meta charset="utf-8" />
		<link rel="stylesheet" rel="stylesheet" href="/css/d3.css" />
		<link rel="stylesheet" rel="stylesheet" href="/css/site.css" />
		<link rel="stylesheet" rel="stylesheet" href="/css/tooltips.css" />
		<link rel="stylesheet" rel="stylesheet" href="/css/item.css" />
	</head>
	<body>
<?php endif; ?>
		<?php include( 'templates/item.php'); ?>
		<?php endif ?>
<?php if ( $showExtra ): ?>
		<pre class="json-data scroll"><?= $item; ?></pre>
	</body>
</html>
<?php endif; ?>