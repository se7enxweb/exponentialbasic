<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @package kernel
 */

class eZDesign
{
    public static function file( $file, $design = false )
    {
        $ini = eZINI::instance();

        if ( $design === false )
            $design = $ini->variable( 'site', 'SiteDesign' );

        $standardDesign = $ini->variable( 'DesignSettings', 'StandardDesign' );
        if ( $standardDesign === '' || $standardDesign === null )
            $standardDesign = 'standard';

        $additionalDesignList = $ini->variable( 'DesignSettings', 'AdditionalSiteDesignList' );
        if ( !is_array( $additionalDesignList ) )
            $additionalDesignList = array();

        $designList = array( $design );
        $designList = array_merge( $designList, $additionalDesignList );
        if ( !in_array( $standardDesign, $designList, true ) )
            $designList[] = $standardDesign;

        $extensionList = eZExtension::activeExtensions( false );
        $extensionBase = eZExtension::baseDirectory();

        foreach ( $designList as $designName )
        {
            foreach ( $extensionList as $extensionName )
            {
                $path = "$extensionBase/$extensionName/design/$designName/$file";
                if ( file_exists( $path ) )
                    return $path;
            }

            $corePath = "design/$designName/$file";
            if ( file_exists( $corePath ) )
                return $corePath;
        }

        return false;
    }

    public static function url( $file, $design = false )
    {
        $path = self::file( $file, $design );
        if ( $path === false )
            return false;

        global $GlobalSiteIni;
        $wwwDir = '';
        if ( is_object( $GlobalSiteIni ) && isset( $GlobalSiteIni->WWWDir ) )
            $wwwDir = $GlobalSiteIni->WWWDir;

        if ( $wwwDir !== '' )
            return $wwwDir . '/' . $path;
        return '/' . $path;
    }
}
