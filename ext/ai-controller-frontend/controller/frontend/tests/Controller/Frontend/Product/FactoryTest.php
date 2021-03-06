<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2018
 */


namespace Aimeos\Controller\Frontend\Product;


class FactoryTest extends \PHPUnit\Framework\TestCase
{
	public function testCreateController()
	{
		$target = '\\Aimeos\\Controller\\Frontend\\Product\\Iface';

		$controller = \Aimeos\Controller\Frontend\Product\Factory::create( \TestHelperFrontend::getContext() );
		$this->assertInstanceOf( $target, $controller );

		$controller = \Aimeos\Controller\Frontend\Product\Factory::create( \TestHelperFrontend::getContext(), 'Standard' );
		$this->assertInstanceOf( $target, $controller );
	}


	public function testCreateControllerInvalidImplementation()
	{
		$this->expectException( '\\Aimeos\\MW\\Common\\Exception' );
		\Aimeos\Controller\Frontend\Product\Factory::create( \TestHelperFrontend::getContext(), 'Invalid' );
	}


	public function testCreateControllerInvalidName()
	{
		$this->expectException( '\\Aimeos\\Controller\\Frontend\\Exception' );
		\Aimeos\Controller\Frontend\Product\Factory::create( \TestHelperFrontend::getContext(), '%^' );
	}


	public function testCreateControllerNotExisting()
	{
		$this->expectException( '\\Aimeos\\Controller\\Frontend\\Exception' );
		\Aimeos\Controller\Frontend\Product\Factory::create( \TestHelperFrontend::getContext(), 'notexist' );
	}
}
