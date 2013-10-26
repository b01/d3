<?php namespace kshabazz\d3a;
/**
* Diablo 3 Assistant core load script.
*
*/
// Get the attribute map file.
$d3a = new Application( $settings );
$attrMapFile = loadAttributeMap( $settings['ATTRIBUTE_MAP_FILE'] );
$controller = convertToClassName( $_SERVER['URL'] );
$d3a->store( 'attribute_map', $attrMapFile );
$d3a->store( 'controller', $controller );

// Load controller
$controller = __NAMESPACE__ . '\\Controller\\' . $d3a->retrieve( 'controller' );
if (class_exists($controller) )
{
	$ctrlr = new $controller( $d3a );
	$model = $ctrlr->getModel();
	$mustache = new \Mustache_Engine();
	$model = new StdClass();
	$model->name = "Khalifah";
	$d3a->templateFilter( $model, $mustache );
	print_r($model);
}
// unset any undesired global variables made in this script.
unset(
	$attrMapFile,
	$controller,
	$ctrlr,
	$nameSpace
);
?>