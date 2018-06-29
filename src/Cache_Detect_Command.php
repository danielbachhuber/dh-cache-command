<?php

/**
 * Detects presence of a page cache.
 */
class Cache_Detect_Command {

	/**
	 * Detects presence of a page cache.
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
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
	 *     # No page cache detected.
	 *     $ wp cache-detect
	 *     +-------------------+----------+
	 *     | key               | value    |
	 *     +-------------------+----------+
	 *     | page_cache        | disabled |
	 *     | page_cache_plugin |          |
	 *     +-------------------+----------+
	 */
	public function __invoke( $_, $assoc_args ) {

		$status = array(
			'page_cache'        => 'disabled',
			'page_cache_plugin' => '',
		);

		self::format_items( $assoc_args['format'], $status );
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
