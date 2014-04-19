<?php

require dirname( __FILE__ ) . '/rapportive.php';

$rap = new WeDevs_Rapportive( 'tareq@wedevs.com' );
var_dump( $rap->get_data() );