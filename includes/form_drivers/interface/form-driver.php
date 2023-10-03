<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Interface FormDriver
 *
 * This interface defines the contract for form driver classes that handle form submissions.
 */
interface LEADEE_FormDriver {
	/**
	 * Run the form driver to handle form submissions.
	 *
	 * @return int Returns an integer value indicating the result of form submission handling.
	 */
	public function run();
}
