<?php

class xsConfig {
	/**
	 * @return array
	 */
	public static function getSocialConfig() {
		return array(
			"base_url"   => SURL . '/social-callback/',
			"providers"  => array(
				"Google"   => array(
					"enabled" => TRUE,
					"scope"   => "https://www.googleapis.com/auth/userinfo.email", // optional
					"keys"    => array(
						"id"     => "762748493597-jbbiku65vv3dt90bkqjdtcd89pji81o6.apps.googleusercontent.com",
						"secret" => "dKXFHMUuJE2hCnXp9x21vl-o"
					),
				),
				"Facebook" => array(
					"enabled"        => TRUE,
					"keys"           => array(
						"id"     => "1041351295889619",
						"secret" => "e91638cc94bf6839cc1967ec0ac851ee"
					),
					"scope"          => "email", // optional
					"trustForwarded" => FALSE
				),
				"Twitter"  => array(
					"enabled"      => TRUE,
					"keys"         => array(
						"key"    => "UklbYcoSBA1L2GmJlbpXEVVYe",
						"secret" => "InwOGiFyS2F2m65vfcOnJwrAbn8DDIhHWA4wjsJ4j2lXcZAzra"
					),
					"includeEmail" => FALSE
				),
			),
			"debug_mode" => FALSE,
			"debug_file" => "",
		);
	}


}