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
        $designList = self::designList( $design );

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

    public static function templateFile( $root, $file )
    {
        $root = self::relativePath( $root );

        $kernelPos = strpos( $root, 'kernel/' );
        if ( $kernelPos !== false )
            $root = substr( $root, $kernelPos );

        $module = false;
        $subdir = false;
        if ( preg_match( '#^kernel/([^/]+)/([^/]+)/templates/([^/]+)/?$#', $root, $m ) )
        {
            $module = $m[1];
            $subdir = $m[2];
            $designInRoot = $m[3];
        }

        $designList = self::designList();

        $extensionList = eZExtension::activeExtensions( false );
        $extensionBase = eZExtension::baseDirectory();

        foreach ( $designList as $designName )
        {
            foreach ( $extensionList as $extensionName )
            {
                if ( $module && $subdir )
                {
                    $path = "$extensionBase/$extensionName/design/$designName/templates/$module/$subdir/$file";
                    if ( file_exists( $path ) )
                    {
                        return $path;
                    }
                }
                if ( $module )
                {
                    $path = "$extensionBase/$extensionName/design/$designName/templates/$module/$file";
                    if ( file_exists( $path ) )
                    {
                        return $path;
                    }
                }
                $path = "$extensionBase/$extensionName/design/$designName/templates/$file";
                if ( file_exists( $path ) )
                    return $path;
            }

            if ( $module && $subdir )
            {
                $path = "design/$designName/templates/$module/$subdir/$file";
                if ( file_exists( $path ) )
                    return $path;
            }
            if ( $module )
            {
                $path = "design/$designName/templates/$module/$file";
                if ( file_exists( $path ) )
                    return $path;
            }
            $path = "design/$designName/templates/$file";
            if ( file_exists( $path ) )
                return $path;
        }

        return false;
    }

    protected static function designList( $design = false )
    {
        global $GlobalSiteDesign;
        if ( $design === false && isset( $GlobalSiteDesign ) && $GlobalSiteDesign !== '' )
            $design = $GlobalSiteDesign;

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

        return $designList;
    }

    protected static function relativePath( $path )
    {
        global $GlobalSiteIni;
        if ( is_object( $GlobalSiteIni ) && isset( $GlobalSiteIni->SiteDir ) && $GlobalSiteIni->SiteDir !== '' )
        {
            if ( strpos( $path, $GlobalSiteIni->SiteDir ) === 0 )
                $path = substr( $path, strlen( $GlobalSiteIni->SiteDir ) );
        }
        $path = trim( $path, '/' );
        return $path;
    }
}
