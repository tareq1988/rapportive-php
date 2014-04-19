# Rapportive PHP wrapper class

A simple way to fetch user information from rapportive API

Details: [See here](http://wp.me/p1uizq-pF)

## Using
```php
require dirname( __FILE__ ) . '/rapportive.php';

$rapp = new WeDevs_Rapportive( 'tareq@wedevs.com' );
var_dump( $rapp->get_data() );
```

## Author
[Tareq Hasan](http://tareq.wedevs.com)