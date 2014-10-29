<?php
/**
 * 获取地图上两点之间的距离
 * 
 */
class GeoHelper {
	/**
	 *
	 * @param int $lat1        	
	 * @param int $lon1        	
	 * @param int $lat2        	
	 * @param int $lon2        	
	 * @param string $unit        	
	 * @return
	 *
	 */
	public static function distance($lat1, $lon1, $lat2, $lon2, $unit = "K") {
		$theta = $lon1 - $lon2;
		$dist = sin ( deg2rad ( $lat1 ) ) * sin ( deg2rad ( $lat2 ) ) + cos ( deg2rad ( $lat1 ) ) * cos ( deg2rad ( $lat2 ) ) * cos ( deg2rad ( $theta ) );
		$dist = acos ( $dist );
		$dist = rad2deg ( $dist );
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper ( $unit );
		
		if ($unit == "K") {
			return ($miles * 1.609344);
		} else if ($unit == "N") {
			return ($miles * 0.8684);
		} else { // mi
			return $miles;
		}
	}
	
	/**
	 *
	 * @param string $address        	
	 * @param string $apikey        	
	 * @return array [1]:lat [0]:lng
	 */
	public static function getLatLng($address, $apikey) {
		$find = array (
				"\\n",
				"\\r",
				" " 
		);
		$replace = array (
				"",
				"",
				"+" 
		);
		$address = str_replace ( $find, $replace, $address );
		$url = 'http://maps.google.com/maps/geo?q=' . $address . '&key=' . $apikey . '&sensor=false&output=xml&oe=utf8';
		$response = self::xml2array ( $url );
		$coordinates = $response ['kml'] ['Response'] ['Placemark'] ['Point'] ['coordinates'];
		if (! empty ( $coordinates )) {
			$point_array = split ( ",", $coordinates );
			return $point_array;
		}
	}
}