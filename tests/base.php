<?php
/**
 * Define common test base class
 *
 * @package pmc-sponsored-tests
 */

namespace PMC\Sponsored_Posts\Tests;

use PMC\Unit_Test\Utility;

/**
 * Abstract Base Class
 *
 * Define as abstract class to prevent test suite from scanning for test method.
 */
abstract class Base extends \PMC\Unit_Test\Base {

	/**
	 * Load_Plugin
	 *
	 * Load additional plugins.
	 */
	protected function _load_plugin() {
		pmc_load_plugin( 'fieldmanager' );
		pmc_load_plugin( 'fm-zones', 'pmc-plugins' );
		pmc_load_plugin( 'pmc-sponsored-posts', 'pmc-plugins' );

		/*
		Need to run this again from `fm-zones` as it's hooked into
		after_theme_setup which has run already at this point.
		 */
		fmz_load_fieldmanager_zone_field();
	}

	/**
	 * Helper to add Sponsored Posts data to Global Curation.
	 *
	 * @param array $data Options for global curation.
	 *
	 * @return array
	 */
	protected function _add_sponsored_posts( array $data = [] ): array {
		$option = [
			'tab_pmc_sponsored_posts' => [
				'pmc_sponsored_posts' => $data,
			],
		];

		update_option( 'global_curation', $option );

		return $option;
	}

}
