<?php
/**
 * @copyright Copyright (C) 7x. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 * @package kernel
 */

// Set a default time zone if none is given to avoid "It is not safe to rely
// on the system's timezone settings" warnings. The time zone can be overridden
// in config.php or php.ini.
if ( !ini_get( "date.timezone" ) )
{
    date_default_timezone_set( "UTC" );
}
else
{
    // 7x: Move into autoload.php and config.php configuration file
    date_default_timezone_set( 'America/Los_Angeles' );
}

ignore_user_abort( true );
error_reporting ( E_ALL );

ini_set( 'display_errors', 'On' );
// ini_set('max_execution_time', '300');
// phpinfo();

// Include composer based autoloads (new in 2.4.0.0)
require __DIR__ . '/vendor/autoload.php';

$kernel = new ezpbKernel( new ezpbKernelWeb() );
echo $kernel->run()->getContent();

?>