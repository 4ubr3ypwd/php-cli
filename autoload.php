<?php

if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {

	require dirname( __FILE__ ) . '/vendor/autoload.php';

} elseif ( file_exists( dirname( __FILE__ ) . '/../../../autoload.php' ) ) {

	require dirname( __FILE__ ) . '/../../../autoload.php';

} else {

	echo "Please run composer install.";

	exit;
}