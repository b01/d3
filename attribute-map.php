<!DOCTYPE html>
<html>
	<head>
		<title>D3 API Attribute Map</title>
		<link rel="stylesheet" href="/css/site.css" />
	</head>
	<body>
		<?php $map = $settings[ 'ATTRIBUTE_MAP' ]; ?>
		<?php if ( isArray($map) ): ?>
		<ol>
		<?php foreach ( $map as $key => $value ): ?>
		<li><span class="key"><?= $key ?> </span> = <span class="value"><?= $value ?></span></li>
		<?php endforeach; ?>
		</ol>
		<?php endif; ?>
	</body>
</html>