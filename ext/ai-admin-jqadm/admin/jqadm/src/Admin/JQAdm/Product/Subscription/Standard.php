<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018
 * @package Admin
 * @subpackage JQAdm
 */


namespace Aimeos\Admin\JQAdm\Product\Subscription;

sprintf( 'subscription' ); // for translation


/**
 * Default implementation of product subscription JQAdm client.
 *
 * @package Admin
 * @subpackage JQAdm
 */
class Standard
	extends \Aimeos\Admin\JQAdm\Common\Admin\Factory\Base
	implements \Aimeos\Admin\JQAdm\Common\Admin\Factory\Iface
{
	/** admin/jqadm/product/subscription/name
	 * Name of the subscription subpart used by the JQAdm product implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Admin\Jqadm\Product\Subscription\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the JQAdm class name
	 * @since 2018.04
	 * @category Developer
	 */


	/**
	 * Copies a resource
	 *
	 * @return string|null HTML output
	 */
	public function copy() : ?string
	{
		$view = $this->getView();

		$view->subscriptionData = $this->toArray( $view->item, true );
		$view->subscriptionBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->subscriptionBody .= $client->copy();
		}

		return $this->render( $view );
	}


	/**
	 * Creates a new resource
	 *
	 * @return string|null HTML output
	 */
	public function create() : ?string
	{
		$view = $this->getView();
		$siteid = $this->getContext()->getLocale()->getSiteId();
		$data = $view->param( 'subscription', [] );

		foreach( $view->value( $data, 'product.lists.id', [] ) as $idx => $value ) {
			$data['product.lists.siteid'][$idx] = $siteid;
		}

		$view->subscriptionData = $data;
		$view->subscriptionBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->subscriptionBody .= $client->create();
		}

		return $this->render( $view );
	}


	/**
	 * Returns a single resource
	 *
	 * @return string|null HTML output
	 */
	public function get() : ?string
	{
		$view = $this->getView();

		$view->subscriptionData = $this->toArray( $view->item );
		$view->subscriptionBody = '';

		foreach( $this->getSubClients() as $client ) {
			$view->subscriptionBody .= $client->get();
		}

		return $this->render( $view );
	}


	/**
	 * Saves the data
	 *
	 * @return string|null HTML output
	 */
	public function save() : ?string
	{
		$view = $this->getView();

		try
		{
			$this->fromArray( $view->item, $view->param( 'subscription', [] ) );
			$view->subscriptionBody = '';

			foreach( $this->getSubClients() as $client ) {
				$view->subscriptionBody .= $client->save();
			}

			return null;
		}
		catch( \Aimeos\MShop\Exception $e )
		{
			$error = array( 'product-item-subscription' => $this->getContext()->getI18n()->dt( 'mshop', $e->getMessage() ) );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}
		catch( \Exception $e )
		{
			$error = array( 'product-item-subscription' => $e->getMessage() . ', ' . $e->getFile() . ':' . $e->getLine() );
			$view->errors = $view->get( 'errors', [] ) + $error;
			$this->logException( $e );
		}

		throw new \Aimeos\Admin\JQAdm\Exception();
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Admin\JQAdm\Iface Sub-client object
	 */
	public function getSubClient( string $type, string $name = null ) : \Aimeos\Admin\JQAdm\Iface
	{
		/** admin/jqadm/product/subscription/decorators/excludes
		 * Excludes decorators added by the "common" subscription from the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This subscription allows you to remove a decorator added via
		 * "admin/jqadm/common/decorators/default" before they are wrapped
		 * around the JQAdm client.
		 *
		 *  admin/jqadm/product/subscription/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Admin\JQAdm\Common\Decorator\*") added via
		 * "admin/jqadm/common/decorators/default" to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2018.04
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/subscription/decorators/global
		 * @see admin/jqadm/product/subscription/decorators/local
		 */

		/** admin/jqadm/product/subscription/decorators/global
		 * Adds a list of globally available decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This subscription allows you to wrap global decorators
		 * ("\Aimeos\Admin\JQAdm\Common\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/subscription/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Admin\JQAdm\Common\Decorator\Decorator1" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2018.04
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/subscription/decorators/excludes
		 * @see admin/jqadm/product/subscription/decorators/local
		 */

		/** admin/jqadm/product/subscription/decorators/local
		 * Adds a list of local decorators only to the product JQAdm client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This subscription allows you to wrap local decorators
		 * ("\Aimeos\Admin\JQAdm\Product\Decorator\*") around the JQAdm client.
		 *
		 *  admin/jqadm/product/subscription/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Admin\JQAdm\Product\Decorator\Decorator2" only to the JQAdm client.
		 *
		 * @param array List of decorator names
		 * @since 2018.04
		 * @category Developer
		 * @see admin/jqadm/common/decorators/default
		 * @see admin/jqadm/product/subscription/decorators/excludes
		 * @see admin/jqadm/product/subscription/decorators/global
		 */
		return $this->createSubClient( 'product/subscription/' . $type, $name );
	}


	/**
	 * Returns the available attribute items of type "interval"
	 *
	 * @return \Aimeos\MShop\Attribute\Item\Iface[] Associative list of attribute IDs as keys and items as values
	 */
	protected function getIntervalItems()
	{
		$manager = \Aimeos\MShop::create( $this->getContext(), 'attribute' );

		$search = $manager->createSearch();
		$expr = [
			$search->compare( '==', 'attribute.type', 'interval' ),
			$search->compare( '==', 'attribute.domain', 'product' ),
		];
		$search->setConditions( $search->combine( '&&', $expr ) );
		$search->setSortations( [$search->sort( '+', 'attribute.code' )] );

		return $manager->searchItems( $search );
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of JQAdm client names
	 */
	protected function getSubClientNames() : array
	{
		/** admin/jqadm/product/subscription/standard/subparts
		 * List of JQAdm sub-clients rendered within the product subscription section
		 *
		 * The output of the frontend is composed of the code generated by the JQAdm
		 * clients. Each JQAdm client can consist of serveral (or none) sub-clients
		 * that are responsible for rendering certain sub-parts of the output. The
		 * sub-clients can contain JQAdm clients themselves and therefore a
		 * hierarchical tree of JQAdm clients is composed. Each JQAdm client creates
		 * the output that is placed inside the container of its parent.
		 *
		 * At first, always the JQAdm code generated by the parent is printed, then
		 * the JQAdm code of its sub-clients. The order of the JQAdm sub-clients
		 * determines the order of the output of these sub-clients inside the parent
		 * container. If the configured list of clients is
		 *
		 *  array( "subclient1", "subclient2" )
		 *
		 * you can easily change the order of the output by reordering the subparts:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1", "subclient2" )
		 *
		 * You can also remove one or more parts if they shouldn't be rendered:
		 *
		 *  admin/jqadm/<clients>/subparts = array( "subclient1" )
		 *
		 * As the clients only generates structural JQAdm, the layout defined via CSS
		 * should support adding, removing or reordering content by a fluid like
		 * design.
		 *
		 * @param array List of sub-client names
		 * @since 2018.04
		 * @category Developer
		 */
		return $this->getContext()->getConfig()->get( 'admin/jqadm/product/subscription/standard/subparts', [] );
	}


	/**
	 * Creates new and updates existing items using the data array
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object without referenced domain items
	 * @param array $data Data array
	 * @return \Aimeos\MShop\Product\Item\Iface Modified product item
	 */
	protected function fromArray( \Aimeos\MShop\Product\Item\Iface $item, array $data ) : \Aimeos\MShop\Product\Item\Iface
	{
		$context = $this->getContext();

		$attrManager = \Aimeos\MShop::create( $context, 'attribute' );
		$listManager = \Aimeos\MShop::create( $context, 'product/lists' );

		$listItems = $item->getListItems( 'attribute', 'config', 'interval', false );

		foreach( $data as $idx => $entry )
		{
			if( !array_key_exists( 'attribute.id', $entry ) ) {
				continue;
			}

			if( $entry['attribute.id'] == '' || ( $listItem = $item->getListItem( 'attribute', 'config', $entry['attribute.id'], false ) ) === null ) {
				$listItem = $listManager->createItem()->setType( 'config' );
			}

			if( $entry['attribute.id'] == '' || ( $refItem = $listItem->getRefItem() ) === null )
			{
				$refItem = $attrManager->createItem()->setType( 'interval' );
				$refItem->fromArray( $entry, true );
			}

			unset( $listItems[$listItem->getId()] );

			$item->addListItem( 'attribute', $listItem->setPosition( $idx ), $refItem );
		}

		return $item->deleteListItems( $listItems );
	}


	/**
	 * Constructs the data array for the view from the given item
	 *
	 * @param \Aimeos\MShop\Product\Item\Iface $item Product item object including referenced domain items
	 * @param bool $copy True if items should be copied, false if not
	 * @return string[] Multi-dimensional associative list of item data
	 */
	protected function toArray( \Aimeos\MShop\Product\Item\Iface $item, bool $copy = false ) : array
	{
		$data = $map = [];
		$siteId = $this->getContext()->getLocale()->getSiteId();

		foreach( $item->getListItems( 'attribute', 'config', 'interval', false ) as $listItem ) {
			$map[$listItem->getRefId()] = $listItem;
		}

		foreach( $this->getIntervalItems() as $attrId => $attrItem )
		{
			$list = $attrItem->toArray( true );

			if( isset( $map[$attrId] ) && $copy !== true )
			{
				$list['product.lists.siteid'] = (string) $map[$attrId]->getSiteId();
				$list['product.lists.id'] = (string) $map[$attrId]->getId();
			}
			else
			{
				$list['product.lists.siteid'] = $siteId;
				$list['product.lists.id'] = '';
			}

			$matches = [];
			$list['Y'] = $list['M'] = $list['W'] = $list['D'] = 0;

			if( preg_match( '/^P([0-9]+)Y([0-9]+)M([0-9]+)W([0-9]+)D$/', $list['attribute.code'], $matches ) === 1 )
			{
				$list['Y'] = $matches[1];
				$list['M'] = $matches[2];
				$list['W'] = $matches[3];
				$list['D'] = $matches[4];
			}

			$data[] = $list;
		}

		return $data;
	}


	/**
	 * Returns the rendered template including the view data
	 *
	 * @param \Aimeos\MW\View\Iface $view View object with data assigned
	 * @return string|null HTML output
	 */
	protected function render( \Aimeos\MW\View\Iface $view ) : string
	{
		/** admin/jqadm/product/subscription/template-item
		 * Relative path to the HTML body template of the subscription subpart for products.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the templates directory (usually in admin/jqadm/templates).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "default" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "default"
		 * should be replaced by the name of the new class.
		 *
		 * @param string Relative path to the template creating the HTML code
		 * @since 2018.04
		 * @category Developer
		 */
		$tplconf = 'admin/jqadm/product/subscription/template-item';
		$default = 'product/item-subscription-standard';

		return $view->render( $view->config( $tplconf, $default ) );
	}
}
