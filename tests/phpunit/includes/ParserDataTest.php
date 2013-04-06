<?php

namespace SMW\Test;

use SMW\ParserData;
use ParserOutput;
use Title;

use SMWDataValueFactory;
use SMWDataItem;

/**
 * Tests for the SMW\ParserData class
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @since 1.9
 *
 * @ingroup SMW
 * @ingroup Test
 *
 * @group SMW
 * @group SMWExtension
 *
 * @licence GNU GPL v2+
 * @author mwjames
 */
class ParserDataTest extends \MediaWikiTestCase {

	/**
	 * Helper method to get title object
	 *
	 * @return Title
	 */
	private function getTitle( $title ){
		return Title::newFromText( $title );
	}

	/**
	 * Helper method to get ParserOutput object
	 *
	 * @return ParserOutput
	 */
	private function getParserOutput(){
		return new ParserOutput();
	}

	/**
	 * Helper method
	 *
	 * @return SMW\ParserData
	 */
	private function getInstance( $titleName, ParserOutput $parserOutput ) {
		return new ParserData( $this->getTitle( $titleName ), $parserOutput );
	}

	/**
	 * Test instance
	 *
	 */
	public function testConstructor() {
		$instance = $this->getInstance( 'Foo', $this->getParserOutput() );
		$this->assertInstanceOf( 'SMW\ParserData', $instance );
	}

	/**
	 * DataProvider
	 *
	 * @return array
	 */
	public function getPropertyValueStringDataProvider() {

		// property, value, errorCount
		return array(
			array( 'Foo'  , 'Bar', 0 ),
			array( '-Foo' , 'Bar', 1 ),
			array( '_Foo' , 'Bar', 1 ),
		);
	}

	/**
	 * Add property / value string
	 *
	 * @covers SMW\ParserData::addPropertyValueString
	 * @dataProvider getPropertyValueStringDataProvider
	 *
	 * @since 1.9
	 */
	public function testAddPropertyValueString( $propertyName, $value, $error ) {
		$instance = $this->getInstance( 'Foo', $this->getParserOutput() );
		$instance->addPropertyValueString( $propertyName, $value );

		// Check the returned instance
		$this->assertInstanceOf( 'SMWSemanticData', $instance->getSemanticData() );
		$this->assertCount( $error, $instance->getErrors() );

		// Check added properties
		foreach ( $instance->getSemanticData()->getProperties() as $key => $diproperty ){

			$this->assertInstanceOf( 'SMWDIProperty', $diproperty );
			$this->assertContains( $propertyName, $diproperty->getLabel() );

			// Check added property values
			foreach ( $instance->getSemanticData()->getPropertyValues( $diproperty ) as $dataItem ){
				$dataValue = SMWDataValueFactory::newDataItemValue( $dataItem, $diproperty );
				if ( $dataValue->getDataItem()->getDIType() === SMWDataItem::TYPE_WIKIPAGE ){
					$this->assertContains( $value, $dataValue->getWikiValue() );
				}
			}
		}
	}

}