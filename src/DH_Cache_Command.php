<?php

/**
 * Page cache detection and WP Super Cache configuration.
 */
class DH_Cache_Command {

	/**
	 * Detects presence of a page cache.
	 *
	 * Page cache detection happens in two steps:
	 *
	 * 1. If the `WP_CACHE` constant is true and `advanced-cache.php` exists,
	 * then `page_cache=enabled`. However, if `advanced-cache.php` is missing,
	 * then `page_cache=broken`.
	 * 2. Scans `active_plugins` options for known page cache plugins, and
	 * reports them if found.
	 *
	 * See 'Examples' section for demonstrations of usage.
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : Render output in a specific format.
	 * ---
	 * default: table
	 * options:
	 *   - table
	 *   - json
	 *   - yaml
	 * ---
	 *
	 * ## EXAMPLES
	 *
	 *     # WP Super Cache detected.
	 *     $ wp cache-detect
	 *     +-------------------+----------------+
	 *     | key               | value          |
	 *     +-------------------+----------------+
	 *     | page_cache        | enabled        |
	 *     | page_cache_plugin | wp-super-cache |
	 *     +-------------------+----------------+
	 *
	 *     # Page cache detected but plugin is unknown.
	 *     $ wp cache-detect
	 *     +-------------------+---------+
	 *     | key               | value   |
	 *     +-------------------+---------+
	 *     | page_cache        | enabled |
	 *     | page_cache_plugin | unknown |
	 *     +-------------------+---------+
	 *
	 *     # No page cache detected.
	 *     $ wp cache-detect
	 *     +-------------------+----------+
	 *     | key               | value    |
	 *     +-------------------+----------+
	 *     | page_cache        | disabled |
	 *     | page_cache_plugin |          |
	 *     +-------------------+----------+
	 *
	 * @subcommand detect
	 */
	public function detect( $_, $assoc_args ) {

		$status = array(
			'page_cache'        => 'disabled',
			'page_cache_plugin' => '',
		);

		if ( WP_CACHE ) {
			if ( is_readable( WP_CONTENT_DIR . '/advanced-cache.php' ) ) {
				$status['page_cache'] = 'enabled';
			} else {
				$status['page_cache'] = 'broken';
			}
			$status['page_cache_plugin'] = 'unknown';
		}

		$plugins = self::detect_page_cache_plugins();
		if ( ! empty( $plugins ) ) {
			$status['page_cache_plugin'] = implode( ',', $plugins );
		}

		self::format_items( $assoc_args['format'], $status );
	}

	/**
	 * Detects any active page cache plugins.
	 *
	 * @return array
	 */
	private static function detect_page_cache_plugins() {
		$page_cache_plugins        = array(
			'comet-cache/comet-cache.php',
			'comet-cache-pro/comet-cache-pro.php',
			'wp-fast-cache/wp-fast-cache.php',
			'quick-cache/quick-cache.php',
			'simple-cache/simple-cache.php',
			'wp-cache/wp-cache.php',
			'wp-fastest-cache-premium/wpFastestCachePremium.php',
			'wp-fastest-cache/wpFastestCache.php',
			'w3-total-cache/w3-total-cache.php',
			'wp-super-cache/wp-cache.php',
		);
		$active_plugins            = get_option( 'active_plugins' );
		$active_page_cache_plugins = array_intersect( $page_cache_plugins, $active_plugins );

		return array_map( function( $plugin ) {
			$bits = explode( '/', $plugin );
			return $bits[0];
		}, $active_page_cache_plugins );
	}

	/**
	 * Modified version of WP_CLI\Formatter to accommodate
	 * for a single array of data.
	 *
	 * @param string $format Format to display data as.
	 * @param array  $items  Single array of data.
	 */
	private static function format_items( $format, $items ) {
		switch ( $format ) {
			case 'json':
				echo json_encode( $items );
				break;
			case 'table':
				$table = new \cli\Table();
				$enabled = \cli\Colors::shouldColorize();
				if ( $enabled ) {
					\cli\Colors::disable( true );
				}

				$table->setHeaders( array( 'key', 'value' ) );

				foreach ( $items as $key => $value ) {
					$table->addRow( array( $key, $value ) );
				}

				foreach ( $table->getDisplayLines() as $line ) {
					\WP_CLI::line( $line );
				}

				if ( $enabled ) {
					\cli\Colors::enable( true );
				}
				break;
			case 'yaml':
				echo Mustangostang\Spyc::YAMLDump( $items, 2, 0 );
				break;
		}
	}

}
