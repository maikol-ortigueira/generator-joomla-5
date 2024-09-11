<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  WebServices.<%= lExtName %>
 * @author      <%= authorName %> <<%= authorEmail %>>
 * @copyright   Copyright (c) <%= year %> <%= authorName %>
 * @license     <%= license %>; see LICENSE.txt
 */

namespace <%= vendorName %>\Plugin\WebServices\<%= nsExtName %>\Extension;

use Joomla\CMS\Event\Application\BeforeApiRouteEvent;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\SubscriberInterface;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Web Services adapter for com_<%= lExtName %>.
 *
 * @since  4.0.0
 */
final class <%= nsExtName %> extends CMSPlugin implements SubscriberInterface
{
    /**
     * Returns an array of events this subscriber will listen to.
     *
     * @return  array
     *
     * @since   5.1.0
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onBeforeApiRoute' => 'onBeforeApiRoute',
        ];
    }

    /**
     * Registers com_<%= lExtName %>'s API's routes in the application
     *
     * @param   BeforeApiRouteEvent  $event  The event object
     *
     * @return  void
     *
     * @since   4.0.0
     */
    public function onBeforeApiRoute(BeforeApiRouteEvent $event): void
    {
        $router = $event->getRouter();

        $router->createCRUDRoutes(
            'v1/<%= lExtName %>',
            '<%= lExtName %>',
            ['component' => 'com_<%= lExtName %>']
        );

        $router->createCRUDRoutes(
            'v1/<%= lExtName %>/foo',
            'foo',
            ['component' => 'com_<%= lExtName %>']
        );

        $router->createCRUDRoutes(
            'v1/<%= lExtName %>/categories',
            'categories',
            ['component' => 'com_categories', 'extension' => 'com_<%= lExtName %>']
        );
    }
}
