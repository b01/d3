<?php namespace kshabazz\d3a;
/**
* Diablo 3 Assistant core load script.
*
*/
// Get the attribute map file.
$d3a = new Application( $settings );
$attrMapFile = loadAttributeMap( $settings['ATTRIBUTE_MAP_FILE'] );
$routeName = convertToClassName( $_SERVER['URL'] );
$d3a->store( 'attribute_map', $attrMapFile );
$d3a->store( 'routeName', $routeName );

// Load the Route controller.
$controller = __NAMESPACE__ . '\\Controller\\' . $d3a->retrieve( 'routeName' );
if ( class_exists($controller) )
{
	$ctrlr = new $controller( $d3a );
	// Business model
	$model = $ctrlr->getModel();
}

// Processing route view by passing the model to the template engine,
// which in turn, fill in all holes within the view.
if ( $model !== null )
{
	$twigLoader = new \Twig_Loader_String();
	$twig = new \Twig_Environment( $twigLoader );
	$d3a->templateFilter( $model, $twig );

	$twig->addFunction(new \Twig_SimpleFunction('isArray', function ($pVariable) {
		return \kshabazz\d3a\isArray($pVariable);
	}));

	$twig->addFunction(new \Twig_SimpleFunction('sessionTimeLeft', function ($pTime) {
		return \kshabazz\d3a\displaySessionTimer( $pTime );
	}));
}

// unset any undesired global variables made in this script.
unset(
	$attrMapFile,
	$controller,
	$ctrlr,
	$nameSpace
);
?>