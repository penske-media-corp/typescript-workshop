<?php
// Require the pmc bootstrap from env var
// VIP classic: PMC_PHPUNIT_BOOTSTRAP=/var/www/html/wp-content/themes/vip/pmc-plugins/pmc-unit-test/bootstrap.php
// VIP GO: PMC_PHPUNIT_BOOTSTRAP=/var/www/html/wp-content/plugins/pmc-plugins/pmc-unit-test/bootstrap.php
require_once getenv( 'PMC_PHPUNIT_BOOTSTRAP' );

// need to use enclosure function here to avoid function name conflict when unit test are reference from root
tests_add_filter(
	'after_setup_theme',
	static function(): void {
		// suppress warning and only reports errors
		error_reporting( E_CORE_ERROR | E_COMPILE_ERROR | E_ERROR | E_PARSE | E_USER_ERROR | E_RECOVERABLE_ERROR ); // phpcs:ignore

		// we need to remove this filter to allow manually plugin loading
		remove_all_filters( 'pmc_do_not_load_plugin' );
	}
);

PMC\Unit_Test\Bootstrap::get_instance()->start();
