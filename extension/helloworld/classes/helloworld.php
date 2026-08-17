<?php
//
// extension/helloworld/classes/helloworld.php
//
// Sample PHP class for the helloworld extension. It is picked up by the
// autoload generator and mapped in var/autoload/ezp_extension.php.

class eZHelloWorld
{
    static function greeting()
    {
        return 'Hello from the eZHelloWorld class!';
    }
}
