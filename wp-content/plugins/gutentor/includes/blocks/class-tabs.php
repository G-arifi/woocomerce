<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Gutentor_Tabs' ) ) {

	/**
	 * Functions related to Tabs
	 * @package Gutentor
	 * @since 1.0.1
	 *
	 */

	class Gutentor_Tabs extends Gutentor_Block_Base{

		/**
		 * Name of the block.
		 *
		 * @access protected
		 * @since 1.0.1
		 * @var string
		 */
		protected $block_name = 'tabs';

		/**
		 * Gets an instance of this object.
		 * Prevents duplicate instances which avoid artefacts and improves performance.
		 *
		 * @static
		 * @access public
		 * @since 1.0.1
		 * @return object
		 */
		public static function get_instance() {

			// Store the instance locally to avoid private static replication
			static $instance = null;

			// Only run these methods if they haven't been ran previously
			if ( null === $instance ) {
				$instance = new self();
			}

			// Always return the instance
			return $instance;

		}
	}
}
Gutentor_Tabs::get_instance()->run();