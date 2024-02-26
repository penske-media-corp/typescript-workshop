<?php
namespace PMC\Sponsored_Posts\Tests;

use PMC\Unit_Test\Utility;
use PMC\Sponsored_Posts\Utility as SP_Utility;

/**
 * Class Test_Utility
 *
 * @coversDefaultClass \PMC\Sponsored_Posts\Utility
 */
class Test_Utility extends Base {

	/**
	 * Filter for post option.
	 *
	 * @return array
	 */
	public function filter_post_option() : array {
		return [
			'name' => 'Unit Test',
			'slug' => 'unit-test',
		];
	}

	/**
	 * Filter for malformed post option.
	 *
	 * @return array
	 */
	public function filter_malformed_post_option() : array {
		return [
			'foobar' => 'Unit Test',
		];
	}

	/**
	 * Filter for malformed config.
	 *
	 * @return array
	 */
	public function filter_malformed_config() : string {
		return 'foobar';
	}

	/**
	 * Filter template.
	 *
	 * @param string $template
	 * @param string $context
	 *
	 * @return string
	 */
	public function filter_sponsored_post_template( string $template = '', string $context = '' ) : string {
		return __DIR__ . '/templates/sponsored-post.php';
	}

	/**
	 * Filter template config.
	 *
	 * @param array $config
	 *
	 * @return array
	 */
	public function filter_sponsored_post_config( array $config = [] ) : array {
		return [
			'unit_test' => [
				'template'       => __DIR__ . '/templates/sponsored-post.php',
				'sponsored_text' => 'Unit Test'
			],
			'foobar' => [
				'template'       => __DIR__ . '/templates/sponsored-post.php',
				'sponsored_text' => 'Unit Test'
			],
		];
	}

	/**
	 * Filter template with context.
	 *
	 * @param $template
	 * @param $context
	 *
	 * @return string
	 */
	public function filter_sponsored_post_template_with_context( $template, $context ) : string {
		$config = $this->filter_sponsored_post_config();

		if ( ! empty( $config[ $context ]['template'] ) ) {
			return $config[ $context ]['template'];
		}

		return $template;
	}

	/**
	 * @covers ::_setup_hooks
	 * @covers ::__construct
	 */
	public function test__setup_hooks() : void {
		$instance = SP_Utility::get_instance();
		$hooks    = [
			[
				'type'     => 'action',
				'name'     => 'pmc_sponsored_posts_placement',
				'priority' => 10,
				'listener' => [ $instance, 'display_active_post' ],
			],
			[
				'type'     => 'action',
				'name'     => 'pmc_sponsored_posts_cleanup',
				'priority' => 10,
				'listener' => [ $instance, 'maybe_clean_up_old_posts' ],
			],
			[
				'type'     => 'action',
				'name'     => 'wp_enqueue_scripts',
				'priority' => 10,
				'listener' => [ $instance, 'enqueue_scripts' ],
			],
			[
				'type'     => 'filter',
				'name'     => 'body_class',
				'priority' => 10,
				'listener' => [ $instance, 'rotator_body_class' ],
			],
			[
				'type'     => 'action',
				'name'     => 'init',
				'priority' => 10,
				'listener' => [ $instance, 'init_rotator' ],
			],
		];

		$this->assert_hooks( $hooks, $instance );
	}

	/**
	 * @covers ::_schedule_event
	 * @covers ::_get_time
	 */
	public function test__schedule_event() : void {
		$instance = SP_Utility::get_instance();
		$time     = Utility::invoke_hidden_method( $instance, '_get_time' );

		// Need to unschedule event for coverage.
		wp_unschedule_event( $time, 'pmc_sponsored_posts_cleanup' );

		Utility::invoke_hidden_method( $instance, '_schedule_event' );

		$job = wp_get_scheduled_event( 'pmc_sponsored_posts_cleanup', [] );

		$this->assertSame( 'daily', $job->schedule );
		$this->assertSame( 'pmc_sponsored_posts_cleanup', $job->hook );
		$this->assertIsInt( $job->timestamp );
		$this->assertEquals( 86400, $job->interval );
	}

	/**
	 * @covers ::rotator_body_class
	 */
	public function test_rotator_body_class() : void {
		$instance = SP_Utility::get_instance();

		$this->assertSame( [], $instance->rotator_body_class( [] ) );

		Utility::set_and_get_hidden_property( $instance, '_rotator_enabled', true );

		$this->assertSame( [ 'pmc-sponsored-posts-rotator' ], $instance->rotator_body_class( [] ) );

		Utility::set_and_get_hidden_property( $instance, '_rotator_enabled', false );

		$this->assertSame( [], $instance->rotator_body_class( [] ) );
	}

	/**
	 * @covers ::display_active_post
	 * @covers ::render_active_post
	 * @covers ::_get_config
	 */
	public function test_display_active_post() : void {
		$instance  = SP_Utility::get_instance();
		$output    = Utility::buffer_and_return( [ $instance, 'display_active_post' ] );
		$post_id   = $this->factory->post->create();
		$cache     = new \PMC_Cache( SP_Utility::CACHE_KEY );
		$now       = time();
		$next_week = strtotime( 'next week' );

		$this->assertEmpty( $output );

		$this->_add_sponsored_posts(
			[
				[
					'start_date' => $now,
					'end_date'   => $now,
					'post_data'  => [
						[
							'sponsored_post' => [ $post_id ],
							'sponsored_by'   => 'Unit Test',
						]
					],
				]
			]
		);

		$output = Utility::buffer_and_return( [ $instance, 'display_active_post' ] );

		$this->assertEmpty( $output );

		$output = Utility::buffer_and_return( 'do_action', [ 'pmc_sponsored_posts_placement'] );

		$this->assertEmpty( $output );

		add_filter( 'pmc_sponsored_posts_config', [ $this, 'filter_sponsored_post_config' ] );

		$cache->invalidate();

		$output = Utility::buffer_and_return( [ $instance, 'display_active_post' ], [ 'unit_test' ] );

		$this->assertStringContainsString( '<h1>' . esc_html( get_the_title( $post_id ) ) . '</h1>', $output );
		$this->assertStringContainsString( '<h2>Sponsored By Unit Test</h2>', $output );

		add_filter( 'pmc_sponsored_posts_template', [ $this, 'filter_sponsored_post_template' ] );

		$output = Utility::buffer_and_return( 'do_action', [ 'pmc_sponsored_posts_placement' ] );

		$this->assertStringContainsString( '<h1>' . esc_html( get_the_title( $post_id ) ) . '</h1>', $output );
		$this->assertStringContainsString( '<h2>Sponsored By Unit Test</h2>', $output );

		remove_filter( 'pmc_sponsored_posts_template', [ $this, 'filter_sponsored_post_template' ] );

		$cache->invalidate();

		$this->_add_sponsored_posts(
			[
				[
					'start_date' => $next_week,
					'end_date'   => $next_week,
					'post_data'  => [
						[
							'sponsored_post' => [ $post_id ],
							'sponsored_by'   => 'Unit Test',
						]
					],
				]
			]
		);

		$cache->invalidate();

		$output = Utility::buffer_and_return( [ $instance, 'display_active_post' ] );

		$this->assertEmpty( $output );

		$output = Utility::buffer_and_return( 'do_action', [ 'pmc_sponsored_posts_placement' ] );

		$this->assertEmpty( $output );

		remove_filter( 'pmc_sponsored_posts_template', [ $this, 'filter_sponsored_post_template' ] );

		$cache->invalidate();

		$this->_add_sponsored_posts(
			[
				[
					'start_date' => $now,
					'end_date'   => $now,
					'post_data'  => [
						[
							'sponsored_post' => [ $post_id ],
							'sponsored_by'   => 'Unit Test',
						]
					],
				]
			]
		);

		add_filter( 'pmc_sponsored_posts_template', [ $this, 'filter_sponsored_post_template_with_context' ], 10, 2 );

		$output = Utility::buffer_and_return( 'do_action', [ 'pmc_sponsored_posts_placement' ] );

		$this->assertEmpty( $output );

		$output = Utility::buffer_and_return( 'do_action', [ 'pmc_sponsored_posts_placement', 'unit_test' ] );

		$this->assertNotEmpty( $output );

		remove_filter( 'pmc_sponsored_posts_template', [ $this, 'filter_sponsored_post_template_with_context' ] );

		$this->_add_sponsored_posts();

		$cache->invalidate();
	}

	/**
	 * @covers ::_get_config
	 */
	public function test__get_config() {
		$instance = SP_Utility::get_instance();
		$cache    = new \PMC_Cache( SP_Utility::CACHE_KEY );

		$this->_add_sponsored_posts();

		$cache->invalidate();

		$this->assertSame( [], Utility::invoke_hidden_method( $instance, '_get_config' ) );

		add_filter( 'pmc_sponsored_posts_config', [ $this, 'filter_malformed_config' ] );

		$this->assertSame( [], Utility::invoke_hidden_method( $instance, '_get_config' ) );

		remove_filter( 'pmc_sponsored_posts_config', [ $this, 'filter_malformed_config' ] );
	}

	/**
	 * @covers ::get_post_option
	 */
	public function test_get_post_option() : void {
		$instance = SP_Utility::get_instance();
		$default  = [
			'name' => 'Sponsored Content',
			'slug' => 'sponsored-content',
		];

		$this->assertSame( $default, $instance->get_post_option() );

		add_filter( 'pmc_sponsored_posts_post_option', [ $this, 'filter_post_option' ] );

		$this->assertSame( $this->filter_post_option(), $instance->get_post_option() );

		remove_filter( 'pmc_sponsored_posts_post_option', [ $this, 'filter_post_option' ] );

		$this->assertSame( $default, $instance->get_post_option() );

		add_filter( 'pmc_sponsored_posts_post_option', [ $this, 'filter_malformed_post_option' ] );

		$this->assertSame( $default, $instance->get_post_option() );

		remove_filter( 'pmc_sponsored_posts_post_option', [ $this, 'filter_malformed_post_option' ] );
	}

	/**
	 * @covers ::is_sponsored_post
	 */
	public function test_is_sponsored_post() : void {
		$instance = SP_Utility::get_instance();
		$post     = $this->mock->post([])->get();
		$option   = $instance->get_post_option();

		$this->assertFalse( $instance->is_sponsored_post() );
		$this->assertFalse( $instance->is_sponsored_post( $post ) );

		\PMC\Post_Options\API::get_instance()->register_global_options(
			[
				$option['slug'] => [
					'label' => $option['name']
				],
			]
		);

		wp_set_object_terms( $post->ID, $option['slug'], '_post-options' );

		$this->mock->post([]);

		$this->assertTrue( $instance->is_sponsored_post( $post ) );
		$this->assertFalse( $instance->is_sponsored_post() );

		$post = $this->mock->post()->get();
		wp_set_object_terms( $post->ID, $option['slug'], '_post-options' );

		$this->assertTrue( $instance->is_sponsored_post() );
		$this->assertTrue( $instance->is_sponsored_post( $post ) );

		$this->mock->wp()->reset();
	}

	/**
	 * @covers ::get_active_posts
	 * @covers ::get_active_post
	 * @covers ::get_active_posts_uncached
	 */
	public function test_get_active_posts() : void {
		$instance  = SP_Utility::get_instance();
		$post_id   = $this->factory->post->create();
		$post_id_2 = $this->factory->post->create();
		$post_id_3 = $this->factory->post->create();
		$cache     = new \PMC_Cache( SP_Utility::CACHE_KEY );
		$now       = time();
		$last_week = strtotime( 'last week' );
		$next_week = strtotime( 'next week' );

		$this->_add_sponsored_posts(
			[
				[
					'start_date' => $now,
					'end_date'   => $now,
					'post_data'  => [
						[
							'sponsored_post' => [ $post_id ],
							'sponsored_by'   => '',
							'sponsor_logo'   => '',
						]
					],
				],
				[
					'start_date' => $now,
					'end_date'   => $next_week,
					'post_data'  => [
						[
							'sponsored_post' => [ $post_id_2 ],
							'sponsored_by'   => 'Unit Test',
							'sponsor_logo'   => '',
						]
					],
				],
				[
					'start_date' => $next_week,
					'end_date'   => $next_week,
					'post_data'  => [
						[
							'sponsored_post' => [ $post_id_3 ],
							'sponsored_by'   => 'Unit Test',
							'sponsor_logo'   => '',
						]
					],
				]
			]
		);

		$this->assertEquals(
			[
				[
					'post'         => get_post( $post_id ),
					'sponsor'      => 'Sponsored',
					'sponsor_logo' => '',
				],
				[
					'post'         => get_post( $post_id_2 ),
					'sponsor'      => 'Unit Test',
					'sponsor_logo' => '',
				],
			],
			$instance->get_active_posts()
		);

		$this->assertNotEmpty( $cache->get() );

		$this->assertEquals(
			[
				[
					'post'         => get_post( $post_id ),
					'sponsor'      => 'Sponsored',
					'sponsor_logo' => '',
				],
				[
					'post'         => get_post( $post_id_2 ),
					'sponsor'      => 'Unit Test',
					'sponsor_logo' => '',
				],
			],
			$instance->get_active_posts()
		);

		$cache->invalidate();

		$this->_add_sponsored_posts(
			[
				[
					'start_date' => $next_week,
					'end_date'   => $next_week,
					'post_data'  => [
						[
							'sponsored_post' => [ $post_id ],
							'sponsored_by'   => 'Unit Test',
						]
					],
				]
			]
		);

		$this->assertSame( [], $instance->get_active_posts() );
		$this->assertSame( [], $instance->get_active_post() );

		$cache->invalidate();

		$this->_add_sponsored_posts(
			[
				[
					'start_date' => $now,
					'end_date'   => $next_week,
					'post_data'  => [
						[
							'sponsored_post' => [ $post_id ],
							'sponsored_by'   => 'Unit Test',
							'sponsor_logo'   => 123,
						]
					],
				]
			]
		);

		$this->assertEquals(
			[
				'post'         => get_post( $post_id ),
				'sponsor'      => 'Unit Test',
				'sponsor_logo' => 123,
			],
			$instance->get_active_post()
		);

		$cache->invalidate();

		$this->_add_sponsored_posts(
			[
				[
					'start_date' => $last_week,
					'end_date'   => $next_week,
					'post_data'  => 'foobar',
				]
			]
		);

		$this->assertSame( [], $instance->get_active_posts() );

		$cache->invalidate();

		$this->_add_sponsored_posts();

		$this->assertSame( [], $instance->get_active_posts() );

		$cache->invalidate();

	}

	/**
	 * @covers ::active_dates_contain_today
	 */
	public function test_active_dates_contain_today() : void {
		$instance  = SP_Utility::get_instance();
		$now       = time();
		$next_week = strtotime( 'next week' );
		$last_week = strtotime( 'last week' );

		$this->assertTrue( $instance->active_dates_contain_today( $now, '' ) );
		$this->assertTrue( $instance->active_dates_contain_today( $now, $now ) );
		$this->assertTrue( $instance->active_dates_contain_today( $last_week, $now ) );
		$this->assertTrue( $instance->active_dates_contain_today( $now, $next_week ) );
		$this->assertTrue( $instance->active_dates_contain_today( $last_week, $next_week ) );

		$this->assertFalse( $instance->active_dates_contain_today( '', '' ) );
		$this->assertFalse( $instance->active_dates_contain_today( $last_week, '' ) );
		$this->assertFalse( $instance->active_dates_contain_today( $last_week, $last_week ) );
		$this->assertFalse( $instance->active_dates_contain_today( $next_week, '' ) );
		$this->assertFalse( $instance->active_dates_contain_today( $next_week, $next_week ) );
		$this->assertFalse( $instance->active_dates_contain_today( $next_week, $now ) );
		$this->assertFalse( $instance->active_dates_contain_today( $now, $last_week ) );
	}

	/**
	 * @covers ::active_dates_contain_future_dates
	 */
	public function test_active_dates_contain_future_dates() : void {
		$instance  = SP_Utility::get_instance();
		$now       = time();
		$next_week = strtotime( 'next week' );
		$last_week = strtotime( 'last week' );

		$this->assertTrue( $instance->active_dates_contain_future_dates( $now, '' ) );
		$this->assertTrue( $instance->active_dates_contain_future_dates( $now, $now ) );
		$this->assertTrue( $instance->active_dates_contain_future_dates( $last_week, $now ) );
		$this->assertTrue( $instance->active_dates_contain_future_dates( $now, $next_week ) );
		$this->assertTrue( $instance->active_dates_contain_future_dates( $last_week, $next_week ) );
		$this->assertTrue( $instance->active_dates_contain_future_dates( $next_week, '' ) );
		$this->assertTrue( $instance->active_dates_contain_future_dates( $next_week, $next_week ) );

		$this->assertFalse( $instance->active_dates_contain_future_dates( '', '' ) );
		$this->assertFalse( $instance->active_dates_contain_future_dates( $last_week, '' ) );
		$this->assertFalse( $instance->active_dates_contain_future_dates( $last_week, $last_week ) );
		$this->assertFalse( $instance->active_dates_contain_future_dates( $next_week, $now ) );
		$this->assertFalse( $instance->active_dates_contain_future_dates( $now, $last_week ) );
	}

	/**
	 * @covers ::maybe_clean_up_old_posts
	 */
	public function test_maybe_clean_up_old_posts() : void {
		$instance  = SP_Utility::get_instance();
		$post_id   = $this->factory->post->create();
		$next_week = strtotime( 'next week' );
		$last_week = strtotime( 'last week' );
		$cache     = new \PMC_Cache( SP_Utility::CACHE_KEY );

		delete_option( 'global_curation' );

		$this->assertNull( $instance->maybe_clean_up_old_posts() );

		$sponsored_posts = $this->_add_sponsored_posts(
			[
				[
					'start_date' => $last_week,
					'end_date'   => $next_week,
					'post_data'  => [
						[
							'sponsored_post' => [ $post_id ],
							'sponsored_by'   => 'Unit Test',
						]
					],
				]
			]
		);

		$instance->maybe_clean_up_old_posts();

		$this->assertSame( $sponsored_posts, get_option( 'global_curation' ) );

		$this->_add_sponsored_posts(
			[
				[
					'start_date' => $last_week,
					'end_date'   => '',
					'post_data'  => [
						[
							'sponsored_post' => [ $post_id ],
							'sponsored_by'   => 'Unit Test',
						]
					],
				]
			]
		);

		$instance->maybe_clean_up_old_posts();

		$sponsored_post = get_option( 'global_curation' )['tab_pmc_sponsored_posts']['pmc_sponsored_posts'][0]['post_data'];

		$this->assertNull( $sponsored_post );

		$cache->invalidate();

		$sponsored_posts = $this->_add_sponsored_posts(
			[
				[
					'start_date' => $last_week,
					'end_date'   => $next_week,
					'post_data'  => [
						[
							'sponsored_post' => [ $post_id ],
							'sponsored_by'   => 'Unit Test',
						]
					],
				]
			]
		);

		$instance->maybe_clean_up_old_posts();

		$this->assertSame( $sponsored_posts, get_option( 'global_curation' ) );

		$this->_add_sponsored_posts();

		$cache->invalidate();
	}

	/**
	 * @covers ::enqueue_scripts
	 */
	public function test_enqueue_scripts() : void {
		global $wp_styles, $wp_scripts;

		$instance   = SP_Utility::get_instance();
		$cache      = new \PMC_Cache( SP_Utility::CACHE_KEY );
		$next_week  = strtotime( 'next week' );
		$last_week  = strtotime( 'last week' );
		$post_1  = $this->factory->post->create_and_get();
		$post_2  = $this->factory->post->create_and_get();

		$instance->enqueue_scripts();

		$this->assertNotContains( 'pmc-sponsored-posts-script', $wp_scripts->queue );
		$this->assertNotContains( 'pmc-sponsored-posts-style', $wp_styles->queue );

		$cache->invalidate();

		Utility::set_and_get_hidden_property( $instance, '_rotator_enabled', true );

		$this->_add_sponsored_posts(
			[
				[
					'start_date' => $last_week,
					'end_date'   => $next_week,
					'post_data'  => [
						[
							'sponsored_post' => [ $post_1->ID ],
							'sponsored_by'   => 'Unit Test',
						]
					],
				]
			]
		);

		$cache->invalidate();

		$instance->enqueue_scripts();

		$this->assertNotContains( 'pmc-sponsored-posts-script', $wp_scripts->queue );
		$this->assertNotContains( 'pmc-sponsored-posts-style', $wp_styles->queue );

		$this->_add_sponsored_posts(
			[
				[
					'start_date' => $last_week,
					'end_date'   => $next_week,
					'post_data'  => [
						[
							'sponsored_post' => [ $post_1->ID ],
							'sponsored_by'   => 'Unit Test',
						],
						[
							'sponsored_post' => [ $post_2->ID ],
							'sponsored_by'   => 'Unit Test',
						]
					],
				]
			]
		);

		$cache->invalidate();

		add_filter( 'pmc_sponsored_posts_config', [ $this, 'filter_sponsored_post_config' ] );
		add_filter( 'pmc_sponsored_posts_template', [ $this, 'filter_sponsored_post_template_with_context' ], 10, 2 );

		$instance->enqueue_scripts();

		$this->assertContains( 'pmc-sponsored-posts-script', $wp_scripts->queue );
		$this->assertContains( 'pmc-sponsored-posts-style', $wp_styles->queue );

		$localized_data = $wp_scripts->registered['pmc-sponsored-posts-script']->extra['data'];
		$expected_data  = 'var pmcSponsoredPosts = {"activePosts":[{"unit_test":"<div>\n\t<h1>' . $post_1->post_title . '<\/h1>\n\t<h2>Sponsored By Unit Test<\/h2>\n<\/div>\n","foobar":"<div>\n\t<h1>' . $post_1->post_title . '<\/h1>\n\t<h2>Sponsored By Unit Test<\/h2>\n<\/div>\n"},{"unit_test":"<div>\n\t<h1>' . $post_2->post_title . '<\/h1>\n\t<h2>Sponsored By Unit Test<\/h2>\n<\/div>\n","foobar":"<div>\n\t<h1>' . $post_2->post_title . '<\/h1>\n\t<h2>Sponsored By Unit Test<\/h2>\n<\/div>\n"}]};';

		$this->assertSame( $expected_data, $localized_data );

		$this->_add_sponsored_posts();

		$cache->invalidate();
	}

	/**
	 * @covers ::init_rotator
	 */
	public function test_init_rotator() {
		$instance = SP_Utility::get_instance();
		do_action( 'init' );
		$this->assertFalse( Utility::get_hidden_property( $instance, '_rotator_enabled' ) );
	}

}
