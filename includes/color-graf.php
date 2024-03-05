<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Class ColorGraf.
 */

class LEADEE_ColorGraf {

	/**
	 * Calculate and retrieve color-coded date ranges based on input parameters.
	 *
	 * This method calculates date ranges and assigns colors based on the number of days in the range.
	 *
	 * @param array $param An array of parameters including 'from', 'to', 'timezone', and 'out_date_format'.
	 * @param int   $day_plus Additional days to be included in the date range.
	 *
	 * @return array An array of color-coded date ranges with their corresponding colors.
	 */
	public function leadee_color_date( $param, $day_plus = 1 ) {
		$range  = array(
			'day'   => array(
				'from' => 1,
				'to'   => 31,
			),
			'week'  => array(
				'from' => 32,
				'to'   => 84,
			),
			'month' => array(
				'from' => 85,
				'to'   => 10000,
			),
		);
		$colors =
			array(
				'day'   => array( '#F04438', '#FCC02A', '#F9ED37', '#8AC44B', '#1BBDD4', '#478ECC', '#913E98' ),
				'week'  => array( '#F04438', '#F8981D', '#8AC44B', '#478ECC', '#F04438', '#F8981D', '#8AC44B', '#478ECC', '#F04438', '#F8981D', '#8AC44B', '#478ECC' ),
				'month' => array( '#478ECC', '#2B74B9', '#ADD57F', '#8AC44B', '#69A042', '#FBF376', '#F9ED37', '#FCC02A', '#E77373', '#F04438', '#D62D30', '#70B2E2' ),
			);

		if ( empty( $param['timezone'] ) ) {
			$param['timezone'] = 'GMT';
		}
		if ( empty( $param['out_date_format'] ) ) {
			$param['out_date_format'] = 'Y-m-d H:i:s';
		}
		if ( $param['from'] > 1000000000000 ) {
			$param['from'] = $param['from'] / 1000;
		}
		if ( $param['to'] > 1000000000000 ) {
			$param['to'] = $param['to'] / 1000;
		}

		$days_from = strtotime( gmdate( 'Y-m-d 00:00:00', intval( $param['from'] ) ) );
		$days_to   = strtotime( gmdate( 'Y-m-d 23:59:59', intval( $param['to'] ) ) );

		$day_start   = (int) gmdate( 'd', $days_from );
		$month_start = (int) gmdate( 'm', $days_from );
		$year_start  = (int) gmdate( 'Y', $days_from );
		$month_end   = (int) gmdate( 'm', $days_to );
		$year_end    = (int) gmdate( 'Y', $days_to );
		$month_all   = ( $year_end - $year_start ) * 12 + ( $month_end - $month_start ) + 1;
		$diff        = (array) date_diff( date_create( gmdate( 'Y-m-d', $days_from ) ), date_create( gmdate( 'Y-m-d', $days_to ) ) );
		$days_all    = $diff['days'] + $day_plus;

		$days_range   = '';
		$colors_range = array();

		foreach ( $range as $key => $value ) {
			if ( $days_all >= $value['from'] && $days_all <= $value['to'] ) {
				$days_range = $key;
				break;
			}
		}

		if ( 'day' === $days_range ) {
			$day_from = $days_from;
			for ( $i = 0; $i < $days_all; $i++ ) {
				$day_to         = strtotime( '+1 day -1 second', $day_from );
				$day_of_week    = (int) gmdate( 'N', $day_from ) - 1;
				$colors_range[] = array(
					'range' => array(
						'from' => gmdate( $param['out_date_format'], $day_from ),
						'to'   => gmdate( $param['out_date_format'], $day_to ),
					),
					'color' => $colors[ $days_range ][ $day_of_week ],
				);
				$day_from       = strtotime( '+1 day', $day_from );
			}
		} elseif ( 'week' === $days_range ) {
			$day_from  = $days_from;
			$day_start = (int) gmdate( 'N', $days_from ) - 1;
			for ( $i = 0; $i < $days_all; $i += 7 ) {
				$step        = round( $i / 7, 1 );
				$shift_start = ( 0 === (int) $step ? 0 : ( ( 7 - $day_start ) + ( $i - 7 ) ) );
				$shift_end   = ( 0 === (int) $step ? ( 7 - $day_start ) : 7 );

				$day_from = strtotime( '+' . $shift_start . ' day', $days_from );
				$day_to   = strtotime( '+' . $shift_end . ' day -1 second', $day_from );
				if ( $day_to > $days_to ) {
					$day_to = $days_to;
				}
				$colors_range[] = array(
					'range' => array(
						'from' => gmdate( $param['out_date_format'], $day_from ),
						'to'   => gmdate( $param['out_date_format'], $day_to ),
					),
					'color' => $colors[ $days_range ][ $step ],
				);
			}
		} elseif ( 'month' === $days_range ) {

			$y = $year_start;
			$m = $month_start;
			for ( $i = 0; $i < $month_all; $i++ ) {
				if ( $m > 12 ) {
					$m -= 12;
					++$y;
				}
				$m_next   = 12 === (int) $m ? 1 : $m + 1;
				$y_next   = 12 === (int) $m ? $y + 1 : $y;
				$d        = 0 === (int) $i ? $day_start : 1;
				$day_from = strtotime( $y . '-' . $m . '-' . $d . ' 00:00:00' );
				$day_to   = strtotime( $y_next . '-' . $m_next . '-01 00:00:00' ) - 1;
				if ( $day_to > $days_to ) {
					$day_to = $days_to;
				}
				$colors_range[] = array(
					'range' => array(
						'from' => gmdate( $param['out_date_format'], $day_from ),
						'to'   => gmdate( $param['out_date_format'], $day_to ),
					),
					'color' => $colors[ $days_range ][ $m - 1 ],
				);
				++$m;
			}
		}

		return $colors_range;
	}
}
