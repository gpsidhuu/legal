<?php

class xsConfig {
	static $social_config = array(
		"base_url"   => SURL . '/social-callback/',
		"providers"  => array(
			"Google"   => array(
				"enabled" => TRUE,
				"scope"   => "https://www.googleapis.com/auth/userinfo.email", // optional
				"keys"    => array(
					"id"     => "759671282461-t4fh7uj0dnh8mcgdqsg6mvss0kg721dk.apps.googleusercontent.com",
					"secret" => "ebIFn0Ho2lQO9T1E6EcL8VYL"
				),
			),
			"Facebook" => array(
				"enabled"        => TRUE,
				"keys"           => array(
					"id"     => "1644538185807392",
					"secret" => "22fd28a4bcbf76920871c63206a17f9f"
				),
				"scope"          => "email", // optional
				"trustForwarded" => FALSE
			),
			"Twitter"  => array(
				"enabled"      => TRUE,
				"keys"         => array(
					"key"    => "fzjMLUUHQn22PlLYIAJDHEjP4",
					"secret" => "kTBMcvPgkkfHpjjqH8DPuNOD77vsDOIn1HJgYNlWiJrqNNjArI"
				),
				"includeEmail" => FALSE
			),
		),
		"debug_mode" => FALSE,
		"debug_file" => "",
	);
}