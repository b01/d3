<?php
	define( "JS_COMPILER_CMD", "java -jar C:\\inetpub\\extensions\\closure-compiler\\compiler.jar --js %s  --js_output_file %s" );
	define( "COMPILE_CSS", "compass compile" );

	/**
	* Takes a directory and minifies *.js files.
	*
	* @param $pDirectory Directory to look for *.js files.
	* @return bool TRUE if any files were minified.
	*/
	function minifyJsFiles( $pDirectory )
	{
		$returnValue = FALSE;

		if ( file_exists($pDirectory) )
		{
			buildJsMinifyCmd( $pDirectory );
			concatMinifiedJsFile();
		}
		else
		{
			echo "Please privide a path to JavaScript files to minify.";
		}

		return $returnValue;
	}

	/**
	* Build the JS minify command by concatenating all the files in one command.
	* TODO: compile directory of files. Currently expects a single file path.
	*
	* @return a list of all the minified *.js files.
	*/
	function buildJsMinifyCmd( $pDirectory )
	{
		$consoleOutput = NULL;
		$programStatus = NULL;
		$consoleCommand = '';
		// $jsFiles = g( $pDirectory . '*.js' );
		// foreach ($jsFiles as $file)
		// {
			// // minified file name.
			// $jsMinifiedFile = str_replace( ".js", "-min.js", $file );
			// // append the file to the command.
			// $consoleCommand .= sprintf( JS_COMPILER_CMD, $file, $jsMinifiedFile );
		// }
		// exec( $consoleCommand, $consoleOutput, $programStatus );
		// Display status
		// if ( $programStatus === 0 )
		// {
			// echo "{$pFile} compiled\n";
		// }
	}


	/**
	* Concatenate all the minified JS files.
	* TODO: compile directory of files. Currently expects a single file path.
	*
	*/
	function concatMinifiedJsFile( $pDirectory )
	{
		// Incomplete.
	}
	if ( isset($argv[1]) && file_exists($argv[1]) )
	{
		minifyJsFiles( $argv[1] );
	}
	else
	{
		echo "please privide the name of a site to compile";
	}

	exit( 0 );
?>
