<?php
namespace PMC\Sponsored_Posts\Tests;

use PMC\Unit_Test\Utility;
use PMC\Sponsored_Posts\Admin;
use PMC\Sponsored_Posts\Utility as SP_Utility;

/**
 * Class Test_Admin
 *
 * @coversDefaultClass \PMC\Sponsored_Posts\Admin
 */
class Test_Admin extends Base {

	/**
	 * @covers ::_setup_hooks
	 * @covers ::__construct
	 */
	public function test__setup_hooks() : void {
		$instance = Admin::get_instance();
		$hooks    = [
			[
				'type'     => 'action',
				'name'     => 'pmc_core_global_curation_modules',
				'priority' => 9,
				'listener' => [ $instance, 'global_curation_modules' ],
			],
			[
				'type'     => 'action',
				'name'     => 'widgets_init',
				'priority' => 10,
				'listener' => [ $instance, 'register_widget' ],
			],
			[
				'type'     => 'action',
				'name'     => 'init',
				'priority' => 10,
				'listener' => [ $instance, 'register_post_option' ],
			],
		];

		$this->assert_hooks( $hooks, $instance );
	}

	/**
	 * @covers ::register_post_option
	 */
	public function test_register_post_option() : void {
		$instance = Admin::get_instance();
		$utility  = SP_Utility::get_instance();

		$term = get_term_by( 'slug', $utility->get_post_option()['slug'], '_post-options' );

		wp_delete_term( $term->term_id, '_post-options' );

		$term = get_term_by( 'slug', $utility->get_post_option()['slug'], '_post-options' );

		$this->assertFalse( $term );

		$instance->register_post_option();

		$term = get_term_by( 'slug', $utility->get_post_option()['slug'], '_post-options' );

		$this->assertIsObject( $term );
	}

	/**
	 * Data provider for test_is_main_group_empty.
	 *
	 * @return array[]
	 */
	public function data_is_main_group_empty() : array {
		return [
			[
				[
					'post_data' => []
				],
				true
			],
			[
				[
					'post_data' => [
						[
							'sponsored_post' => [ 0 ],
						]
					]
				],
				true
			],
			[
				[
					'post_data' => [
						[
							'sponsored_post' => [ 0 ],
						],
						[
							'sponsored_post' => [ 123 ],
						],
					]
				],
				false
			],
			[
				[
					'post_data' => [
						[
							'sponsored_post' => [ 123 ],
						],
						[
							'sponsored_post' => [ 0 ],
						],
					]
				],
				false
			],
		];
	}

	/**
	 * @covers ::is_main_group_empty
	 * @dataProvider data_is_main_group_empty
	 */
	public function test_is_main_group_empty( $values, $expected ) : void {
		$instance = Admin::get_instance();

		$this->assertSame( $expected, $instance->is_main_group_empty( $values ) );
	}

	/**
	 * Data provider for test_is_post_group_empty.
	 *
	 * @return array[]
	 */
	public function data_is_post_group_empty() : array {
		return [
			[
				[
					'sponsored_post' => []
				],
				true
			],
			[
				[
					'sponsored_post' => [ 0 ]
				],
				true
			],
			[
				[
					'sponsored_post' => [ 123 ]
				],
				false
			],
		];
	}

	/**
	 * @covers ::is_post_group_empty
	 * @dataProvider data_is_post_group_empty
	 */
	public function test_is_post_group_empty( $values, $expected ) : void {
		$instance = Admin::get_instance();

		$this->assertSame( $expected, $instance->is_post_group_empty( $values ) );
	}

	/**
	 * @covers ::global_curation_modules
	 * @covers ::_sponsored_posts_module
	 */
	public function test_global_curation_modules() : void {
		$instance = Admin::get_instance();
		$default  = [
			'unit_test' => [
				'label'    => 'Unit Test',
				'children' => []
			],
		];

		$modules = $instance->global_curation_modules( $default );

		$this->assertSame( 'Unit Test', $modules['unit_test']['label'] );
		$this->assertSame( 'Sponsored Posts', $modules['pmc_sponsored_posts']['label'] );
		$this->assertSame( 'tab_pmc_sponsored_posts', $modules['pmc_sponsored_posts']['name'] );
		$this->assertIsArray( $modules['pmc_sponsored_posts']['children'] );

		$post_option = $modules['pmc_sponsored_posts']['children']['pmc_sponsored_posts']->children['post_data']->children['sponsored_post']->query_args['tax_query'][0];
		$expects     = [
			'taxonomy' => '_post-options',
			'field'    => 'slug',
			'terms'    => 'sponsored-content',
		];

		$this->assertSame( $post_option, $expects );
	}

	/**
	 * @covers ::register_widget
	 */
	public function test_register_widget() : void {
		global $wp_widget_factory;

		$instance = Admin::get_instance();
		$instance->register_widget();

		$this->assertInstanceOf( '\PMC\Sponsored_Posts\Widget', $wp_widget_factory->widgets[ 'PMC\Sponsored_Posts\Widget' ] );
	}

}
