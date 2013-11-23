<?php namespace kshabazz\d3a\Abstracts;

/**
 * Diablo 3 Assistant License is under The MIT License (MIT)
 * [OSI Approved License]. Please read LICENSE.txt, included with this
 * software for the full licensing information. If no LICENSE.txt accompanied
 * this software, then no license is granted.
 *
 * @package kshabazz\d3a\Controller
 * @copyright (c) 2012-2013 Khalifah K. Shabazz
 */
/**
 * Class Application
 *
 * @package kshabazz\d3a\Abstracts
 * @abstract
 */
abstract class Application
{
    protected
        $models;

    /**
     * Get a specific model by class name.
     * @param string $pKey name of model to retrieve.
     * @return mixed
     */
	public function getModel( $pKey )
	{
		return $this->models[ $pKey ];
	}

    /**
     * Set a model on the application
     *
     * @param object $pModel
     * @return \kshabazz\d3a\Application
     */
    public function addModel( $pModel )
    {
	    $key = \get_class( $pModel );
        if ( \array_key_exists($key, $this->models))
        {
            unset( $this->models[$key] );
        }

        if ( \is_object($pModel) )
        {
            $this->model[ $key ] = $pModel;
        }

        return $this;
    }

	/**
	*
	*/
	public function getView()
	{
		return $this->view;
	}

	/**
	*
	*/
	public function setView( $pView = NULL )
	{
		$this->view = $pView;
	}
}
// DO NOT WRITE BELOW THIS LINE, NOT EVEN WHITE-SPACE CHARS
?>