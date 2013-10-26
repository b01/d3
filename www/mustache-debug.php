<?php
	$mustache = new \Mustache_Engine();
	$model = new StdClass();
	$item = new StdClass();
	$item->id = 1;
	$item->name = "Sword Fork";
	$item->desc = "A sword shaped like a fork";
	$model->name = "Khalifah";
	$model->items = [
		"head" => $item,
		"legs" => $item,
		"feet" => $item
	];
	$model->test = function($text, Mustache_LambdaHelper $helper) {
	print_r($helper);
		return $text;
	};
	$d3a->templateFilter( $model, $mustache );
?>
{{name}}
{{#test}}
	item id: {{head.id}}
{{/test}}