<?php

namespace Elgg\Bookmarks;

use Elgg\DefaultPluginBootstrap;

/**
 * Bootstraps the plugin
 *
 * @since 4.0
 * @internal
 */
class Bootstrap extends DefaultPluginBootstrap {
	
	/**
	 * {@inheritDoc}
	 */
	public function init() {
		elgg_register_notification_event('object', 'bookmarks', ['create']);
	}
}