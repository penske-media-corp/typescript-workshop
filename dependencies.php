<?php
/**
 * Load dependent plugins.
 *
 * @package pmc-sponsored-posts
 */

wpcom_vip_load_plugin( 'pmc-global-functions', 'pmc-plugins' );

// Load only if Fieldmanager not already loaded.
if ( ! class_exists( 'Fieldmanager_Field', false ) ) {
	pmc_load_plugin( 'fieldmanager' );
}
pmc_load_plugin( 'fm-zones', 'pmc-plugins' );
