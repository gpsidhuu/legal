<?php
/*
 * Template Name: [Social Login  Callback]
 */
include_once INC_PATH . 'autoload.php';
session_start();
$_SESSION['src'] = $_GET['auth_done'];
Hybrid_Endpoint::process();
