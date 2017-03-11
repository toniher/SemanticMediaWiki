<?php

namespace SMW\Importer;

use ArrayIterator;

/**
 * @license GNU GPL v2+
 * @since 2.5
 *
 * @author mwjames
 */
class JsonImportContentsIterator implements ImportContentsIterator {

	/**
	 * @var JsonImportContentsFileDirReader
	 */
	private $jsonImportContentsFileDirReader;

	/**
	 * @var string
	 */
	private $description = '';

	/**
	 * @since 2.5
	 *
	 * @param JsonImportContentsFileDirReader $jsonImportContentsFileDirReader
	 */
	public function __construct( JsonImportContentsFileDirReader $jsonImportContentsFileDirReader ) {
		$this->jsonImportContentsFileDirReader = $jsonImportContentsFileDirReader;
	}

	/**
	 * @since 2.5
	 *
	 * @param string $description
	 */
	public function setDescription( $description ) {
		$this->description = $description;
	}

	/**
	 * @since 2.5
	 *
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @see IteratorAggregate::getIterator
	 *
	 * @since 2.5
	 *
	 * @return Iterator
	 */
	public function getIterator() {
		return new ArrayIterator( $this->jsonImportContentsFileDirReader->getContents() );
	}

}
