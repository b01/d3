<?php namespace kshabazz\d3a;
/**
* Starts up output buffering, then process the buffer when it's flushed.
*/
class PostProcess
{
	/**
	*
	* @param Model $pModel
	* @param $parser Whatever template parser you decided on, all it needs is a render method that takes the buffer and model.
	*/
	public function __construct( $pModel, $pParser )
	{
		$this->model = $pModel;
		$this->parser = $pParser;
		// Start buffering output now.
		// the model object was not preserved at the time the processBuffer method was called.
		ob_start([ $this, 'processBuffer' ]);
	}

	/**
	* Fill in tokens within the buffer.
	*
	* @param string $pBuffer
	* @return string
	*/
	public function processBuffer( $pBuffer )
	{
		$returnValue = FALSE;
		$lastError = error_get_last();

		if ( $lastError === NULL )
		{
			$returnValue = $this->parser->render( $pBuffer, $this->model );
		}

		return $returnValue;
	}
}
?>