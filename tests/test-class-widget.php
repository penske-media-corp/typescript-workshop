<?php
namespace PMC\Sponsored_Posts\Tests;

use PMC\Unit_Test\Utility;
use PMC\Sponsored_Posts\Widget;
use PMC\Sponsored_Posts\Utility as SP_Utility;
use PMC\Sponsored_Posts\Admin;

/**
 * Class Test_Widget
 *
 * @coversDefaultClass \PMC\Sponsored_Posts\Widget
 */
class Test_Widget extends Base {

	/**
	 * @covers ::__construct
	 */
	public function test__construct() : void {
		global $wp_widget_factory;

		unset( $wp_widget_factory->widgets[ 'PMC\Sponsored_Posts\Widget' ] );

		register_widget( Widget::class );

		$widget = $wp_widget_factory->widgets[ 'PMC\Sponsored_Posts\Widget' ];

		$this->assertSame( 'pmc_sponsored_posts_widget', $widget->id_base );
		$this->assertSame( 'Sponsored Posts', $widget->name );
	}

	/**
	 * @covers ::widget
	 */
	public function test_widget() : void {
		$widget  = new Widget();
		$post_id = $this->factory->post->create();
		$output  = Utility::buffer_and_return( [ $widget, 'widget' ], [ [], [] ] );
		$cache   = new \PMC_Cache( SP_Utility::CACHE_KEY );
		$now     = time();

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

		$this->assertEmpty( $output );

		add_filter( 'pmc_sponsored_posts_template', [ $this, 'widget_template' ], 10, 2 );

		$output = Utility::buffer_and_return( [ $widget, 'widget' ], [ [], [] ] );

		$this->assertNotEmpty( $output );

		remove_filter( 'pmc_sponsored_posts_template', [ $this, 'widget_template' ] );

		$cache->invalidate();
	}

	/**
	 * Filter to set widget sponsored post template.
	 *
	 * @param $template
	 * @param $context
	 *
	 * @return string
	 */
	public function widget_template( $template, $context ) : string {
		if ( 'widget' === $context )  {
			return __DIR__ . '/templates/sponsored-post.php';
		}

		return $template;
	}

}
