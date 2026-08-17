<?php
// 
// $Id: ezsysinfo.php 6231 2001-07-20 11:30:53Z jakobn $
//
// This class is based on code from
// http://phpsysinfo.sourceforge.net/ 
// Written by:
//   Uriah Welcome (precision@valinux.com)
//   Matthew Snelham (infinite@valinux.com)
//
// Created on: <21-Apr-2001 12:11:59 bf>
//
// This source file is part of Exponential Basic, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

//!! eZCommon
//! The eZ sysinfo provides system information.
/*!
  This class does currently only handle system information
  for Linux systems.   
*/

class eZSysinfo
{
    /*!
      \static
      Function that emulate the compat_array_keys function of PHP4
      for PHP3 compatability
    */
    static public function compat_array_keys( $arr )
    {
        $result=array();

        foreach ($arr as $key => $val)
        {
            $result[] = $key;
        }
        return $result;
    }


    /*!
      \static
      Function that emulates the compat_in_array function of PHP4
      for PHP3 compatability
    */
    static public function compat_in_array( $value, $arr )
    {

        foreach ($arr as $val)
        {
            if ( $value == $val )
            {
                return true;
            }
        }
        return false;
    }
    
    /*!
      \static
      A helper function, when passed a number representing KB,
      and optionally the number of decimal places required,
      it returns a formated number string, with unit identifier.
    */
    static public function format_bytesize( $kbytes, $dec_places = 2 )
    {   
        if ( $kbytes > 1048576 )
        {
            $result  = sprintf("%." . $dec_places . "f", $kbytes / 1048576 );
            $result .= ' GB';            
        }
        elseif ( $kbytes > 1024 )
        {
            $result  = sprintf("%." . $dec_places . "f", $kbytes / 1024 );
            $result .= ' MB';
        }
        else
        {
            $result  = sprintf("%." . $dec_places . "f", $kbytes );
            $result .= ' KB';   
        }
        
        return $result;
    }


    /*!
      \static
      Reads a /proc pseudo-file, falling back to shell_exec when open_basedir
      or file permissions prevent direct fopen().
    */
    static public function read_proc( $path )
    {
        $content = false;
        if ( $fd = @fopen( $path, "r" ) )
        {
            $content = '';
            while ( $buf = fgets( $fd, 4096 ) )
            {
                $content .= $buf;
            }
            fclose( $fd );
        }
        elseif ( ( $output = shell_exec( 'cat ' . escapeshellarg( $path ) . ' 2>/dev/null' ) ) !== null )
        {
            $content = $output;
        }
        return $content;
    }


    /*!
      \static
      Returns the virtual hostname accessed.
    */
    static public function vhostname()
    {
        if ( !( $result = getenv( 'SERVER_NAME' ) ) )
        {
            $result = "N.A.";
        }

        return $result;
    }


    /*!
      \static
      Returns the Cannonical machine hostname.
    */
    static public function chostname()
    {
        $result = "N.A.";
        if ( function_exists( 'gethostname' ) && ( $hostname = gethostname() ) )
        {
            $result = $hostname;
        }
        elseif ( ( $hostname = trim( shell_exec( 'hostname -f 2>/dev/null' ) ) ) )
        {
            $result = $hostname;
        }
        return $result;
    }

    /*!
      \static
      Returns the IP address that the request was made on.
    */
    static public function ip_addr()
    {
        if ( !( $result = getenv( 'SERVER_ADDR' ) ) )
        {
            $result = gethostbyname( eZSysinfo::chostname() );
        }

        return $result;
    } 


    /*!
      \static
      Returns an array of all meaningful devices      
      on the PCI bus.
    */
    static public function pcibus()
    {
        $results = array();

        $content = eZSysinfo::read_proc("/proc/pci");
        if ( $content !== false )
        {
            $lines = preg_split( "/(\r\n|\n)/", $content );
            foreach ( $lines as $buf )
            {
                if ( preg_match( "/Bus/", $buf ) )
                {
                    $device = 1;
                    continue;
                }

                if ( $device )
                {
                    list($key, $value) = preg_split(": ", $buf, 2);

                    if ( !preg_match( "/bridge/i", $key ) && !preg_match( "/USB/i", $key ) )
                    {
                        $results[] = preg_replace("/\([^\)]+\)\.$/", "", trim( $value ) );
                    }
                    $device = 0;
                }
            }
        }

        if ( count( $results ) == 0 && ( $lspci = trim( shell_exec( 'lspci -m 2>/dev/null' ) ) ) )
        {
            $lines = preg_split( "/(\r\n|\n)/", $lspci );
            foreach ( $lines as $buf )
            {
                $parts = preg_split( '/"\s*,\s*"/', $buf );
                if ( count( $parts ) >= 4 )
                {
                    $vendor = trim( $parts[2], '"' );
                    $device = trim( $parts[3], '"' );
                    if ( $vendor && $device && !preg_match( "/bridge/i", $device ) )
                    {
                        $results[] = "$vendor $device";
                    }
                }
            }
        }

        return $results;
    }


    /*!
      \static
      Returns an array of all ide devices attached
      to the system, as determined by the aliased
      shortcuts in /proc/ide
    */
    static public function idebus()
    {
        $results = array();

        if ( ( $listing = shell_exec( 'ls -1 /proc/ide 2>/dev/null' ) ) )
        {
            $files = preg_split( "/(\r\n|\n)/", trim( $listing ) );
            foreach ( $files as $file )
            {
                if ( preg_match( "/^hd/", $file ) )
                {
                    $results["$file"] = array();

                    $model = eZSysinfo::read_proc( "/proc/ide/$file/model" );
                    if ( $model !== false )
                    {
                        $results["$file"]["model"] = trim( $model );
                    }
                    $capacity = eZSysinfo::read_proc( "/proc/ide/$file/capacity" );
                    if ( $capacity !== false )
                    {
                        $results["$file"]["capacity"] = trim( $capacity );
                    }
                }
            }
        }

        if ( count( $results ) == 0 && ( $lsblk = trim( shell_exec( 'lsblk -b -d -n -o NAME,MODEL,SIZE 2>/dev/null' ) ) ) )
        {
            $lines = preg_split( "/(\r\n|\n)/", $lsblk );
            foreach ( $lines as $buf )
            {
                $parts = preg_split( "/\s+/", trim( $buf ), 3 );
                if ( count( $parts ) >= 2 )
                {
                    $name = $parts[0];
                    $model = $parts[1] == '-' ? 'N/A' : $parts[1];
                    $size = count( $parts ) >= 3 ? $parts[2] : '';
                    $results[$name] = array( 'model' => $model );
                    if ( is_numeric( $size ) && $size > 0 )
                    {
                        $results[$name]['capacity'] = (int) $size / 512;
                    }
                }
            }
        }

        return $results;
    }


    /*
      \static
      Returns an array of all meaningful devices 
      on the SCSI bus.
    */
    static public function scsibus()
    {
        $results = array();
        $dev_vendor = "";
        $dev_model = "";
        $dev_rev = "";
        $dev_type = "";
        $get_type = false;

        $content = eZSysinfo::read_proc( "/proc/scsi/scsi" );
        if ( $content !== false )
        {
            $lines = preg_split( "/(\r\n|\n)/", $content );
            foreach ( $lines as $buf )
            {
                if ( preg_match( "/Vendor/", $buf ) )
                {
                    preg_match("/Vendor: (.*) Model: (.*) Rev: (.*)/i", $buf, $dev );
                    list($key, $value) = preg_split("/: /", $buf, 2);
                    $dev_str = $value;
                    $get_type = 1;
                    continue;
                }

                if ( $get_type )
                {
                    preg_match("/Type:\s+(\S+)/i", $buf, $dev_type );
                    $results[] = "$dev[1] $dev[2] ( $dev_type[1] )";
                    $get_type = 0;
                }
            }
        }

        if ( count( $results ) == 0 && ( $lsscsi = trim( shell_exec( 'lsscsi 2>/dev/null' ) ) ) )
        {
            $lines = preg_split( "/(\r\n|\n)/", $lsscsi );
            foreach ( $lines as $buf )
            {
                if ( preg_match( "/\[(\d+):(\d+):(\d+):(\d+)\]\s+(\S+)\s+(.*)/", $buf, $matches ) )
                {
                    $results[] = $matches[5] . " " . $matches[6];
                }
            }
        }

        return $results;
    }

    /*!
      \static
      Returns an associative array of two associative
      arrays, containg the memory statistics for RAM and swap
    */
    static public function meminfo()
    {
        $results = array();
        $results['ram'] = array();
        $results['swap'] = array();

        $content = eZSysinfo::read_proc( "/proc/meminfo" );
        if ( $content !== false )
        {
            $lines = preg_split( "/(\r\n|\n)/", $content );
            foreach ( $lines as $buf )
            {
                if ( preg_match("/^MemTotal:\s+(\d+)\s+kB/i", $buf, $ar_buf ) )
                {
                    $results['ram']['total'] = $ar_buf[1] / 1024;
                }
                if ( preg_match("/^MemFree:\s+(\d+)\s+kB/i", $buf, $ar_buf ) )
                {
                    $results['ram']['free'] = $ar_buf[1] / 1024;
                }
                if ( preg_match("/^Buffers:\s+(\d+)\s+kB/i", $buf, $ar_buf ) )
                {
                    $results['ram']['buffers'] = $ar_buf[1] / 1024;
                }
                if ( preg_match("/^Cached:\s+(\d+)\s+kB/i", $buf, $ar_buf ) )
                {
                    $results['ram']['cached'] = $ar_buf[1] / 1024;
                }
                if ( preg_match("/^SwapTotal:\s+(\d+)\s*kB/i", $buf, $ar_buf ) )
                {
                    $results['swap']['total'] = $ar_buf[1] / 1024;
                }
                if ( preg_match("/^SwapFree:\s+(\d+)\s*kB/i", $buf, $ar_buf ) )
                {
                    $results['swap']['free'] = $ar_buf[1] / 1024;
                }
            }

            if ( isset( $results['ram']['total'] ) )
            {
                $free = isset( $results['ram']['free'] ) ? $results['ram']['free'] : 0;
                $buffers = isset( $results['ram']['buffers'] ) ? $results['ram']['buffers'] : 0;
                $cached = isset( $results['ram']['cached'] ) ? $results['ram']['cached'] : 0;

                $results['ram']['t_used'] = $results['ram']['total'] - $free - $buffers - $cached;
                $results['ram']['t_free'] = $free + $buffers + $cached;
                $results['ram']['percent'] = round( ( $results['ram']['t_used'] * 100 ) / $results['ram']['total'] );
            }

            if ( isset( $results['swap']['total'] ) && $results['swap']['total'] > 0 )
            {
                $used = $results['swap']['total'] - ( isset( $results['swap']['free'] ) ? $results['swap']['free'] : 0 );
                $results['swap']['used'] = $used;
                $results['swap']['percent'] = round( ( $used * 100 ) / $results['swap']['total'] );
            }
        }

        return $results;
    }


    /*!
      \static
      Returns an array of all network devices 
      and their tx/rx stats.
    */
    static public function netdevs()
    {
        $results = array();

        $content = eZSysinfo::read_proc( "/proc/net/dev" );
        if ( $content !== false )
        {
            $lines = preg_split( "/(\r\n|\n)/", $content );
            foreach ( $lines as $buf )
            {
                if ( preg_match( "/:/", $buf ) )
                {
                    list( $dev_name, $stats_list ) = preg_split( "/:/", $buf, 2 );
                    $dev_name = trim( $dev_name );
                    $stats = preg_split( "/\s+/", trim($stats_list) );
                    $results[$dev_name] = array();

                    $results[$dev_name]['rx_bytes'] = $stats[0];
                    $results[$dev_name]['rx_packets'] = $stats[1];
                    $results[$dev_name]['rx_errs'] = $stats[2];
                    $results[$dev_name]['rx_drop'] = $stats[3];

                    $results[$dev_name]['tx_bytes'] = $stats[8];
                    $results[$dev_name]['tx_packets'] = $stats[9];
                    $results[$dev_name]['tx_errs'] = $stats[10];
                    $results[$dev_name]['tx_drop'] = $stats[11];

                    $results[$dev_name]['errs'] = $stats[2] + $stats[10];
                    $results[$dev_name]['drop'] = $stats[3] + $stats[11];
                }
            }
        }

        return $results;
    }

    /*!
      \static
      Returns a string equivilant to `uname --release`)
    */
    static public function kernel()
    {
        $result = "N.A.";
        $release = function_exists( 'php_uname' ) ? php_uname( 'r' ) : trim( shell_exec( 'uname -r' ) );
        if ( $release )
        {
            $result = $release;
            $version = function_exists( 'php_uname' ) ? php_uname( 'v' ) : shell_exec( 'uname -v' );
            if ( $version && strpos( $version, 'SMP' ) !== false )
            {
                $result .= " (SMP)";
            }
        }

        return $result;
    }

    /*!
      \static
      Returns a 1x3 array of load avg's in
      standard order and format.
    */
    static public function loadavg()
    {
        $results = array("N.A.","N.A.","N.A.");
        if ( $fd = fopen("/proc/loadavg", "r") )
        {
            $line = fgets( $fd, 4096 );
            fclose( $fd );
            $results = preg_split( "/[|\s:]/", $line );
        }
        elseif ( ( $line = trim( shell_exec( 'cat /proc/loadavg 2>/dev/null' ) ) ) )
        {
            $results = preg_split( "/[|\s:]/", $line );
        }

        return $results;
    }


    /*!
      \static
      Returns a formatted english string,
      enumerating the uptime verbosely.
    */
    static public function uptime()
    {
        $result = "N.A.";
        $line = false;
        if ( $fd = fopen("/proc/uptime", "r") )
        {
            $line = fgets( $fd, 4096 );
            fclose( $fd );
        }
        elseif ( ( $line = shell_exec( 'cat /proc/uptime 2>/dev/null' ) ) )
        {
            $line = trim( $line );
        }

        if ( $line )
        {
            $ar_buf = preg_split( "/[|\s:]/", $line );
            $sys_ticks = trim( $ar_buf[0] );

            $min   = (int) $sys_ticks / 60;
            $hours = $min / 60;
            $days  = floor( $hours / 24 );
            $hours = floor( $hours - ($days * 24) );
            $min   = floor( $min - ($days * 60 * 24) - ($hours * 60) );

            $result = "";
            if ( $days != 0 )
            {
                $result = "$days days, ";
            }
            if ( $hours != 0 )
            {
                $result .= "$hours hours, ";
            }
            $result .= "$min minutes";
        }

        return $result;
    }


    /*!
      \static
      Returns the number of users currently logged in.
    */      
    static public function users()
    {
        $result = trim( shell_exec( 'who | wc -l' ) );

        return $result;
    }


    /*!
      \static
      Returns an associative array containing all
      relevant info about the processors in the system.
    */
        static public function cpu()
    {
        $results = array();
        $results['model'] = 'N.A.';
        $results['mhz'] = 'N.A.';
        $results['cache'] = 'N.A.';
        $results['bogomips'] = 'N.A.';
        $results['cpus'] = 'N.A.';

        $content = eZSysinfo::read_proc( "/proc/cpuinfo" );
        if ( $content !== false )
        {
            $lines = preg_split( "/(\r\n|\n)/", $content );
            foreach ( $lines as $buf )
            {
                list($key, $value) = array_pad( preg_split("/\s+:\s+/", trim($buf), 2), 2, null );

                switch ( $key ) {
                    case "model name":
                    case "cpu":
                        $results['model'] = $value;
                        break;
                    case "cpu MHz":
                    case "clock":
                        $results['mhz'] = sprintf("%.2f", $value );
                        break;
                    case "revision":
                        $results['model'] .= " ( rev: " . $value . ")";
                        break;
                    case "cache size":
                        $results['cache'] = $value;
                        break;
                    case "bogomips":
                        $results['bogomips'] = isset( $results['bogomips'] ) && is_numeric( $results['bogomips'] ) ? $results['bogomips'] + $value : $value;
                        break;
                    case "processor":
                        $results['cpus'] = isset( $results['cpus'] ) && is_numeric( $results['cpus'] ) ? $results['cpus'] + 1 : 1;
                        break;
                }
            }
        }

        return $results;
    }


    /*!
      \static
      Returns an array of associative arrays
      containing information on every mounted partition.
    */
        static public function fsinfo()
    {
        $results = array();
        $df = shell_exec( '/bin/df -kP' );
        $mounts = preg_split( "/(\n)/", $df );
        $fstype = array();
        $fsdev = array();

        $content = eZSysinfo::read_proc( "/proc/mounts" );
        if ( $content !== false )
        {
            $lines = preg_split( "/(\r\n|\n)/", $content );
            foreach ( $lines as $buf )
            {
                list($dev, $mpoint, $type ) = preg_split("/\s+/", trim($buf), 4);
                $fstype[$mpoint] = $type;
                $fsdev[$dev] = $type;
            }
        }

        for ( $i = 1; $i < sizeof($mounts) - 1; $i++ )
        {
            $ar_buf = preg_split("/\s+/", $mounts[$i], 6);

            $results[$i - 1] = array();

            $results[$i - 1]['disk'] = $ar_buf[0];
            $results[$i - 1]['size'] = $ar_buf[1];
            $results[$i - 1]['used'] = $ar_buf[2];
            $results[$i - 1]['free'] = $ar_buf[3];
            $results[$i - 1]['percent'] = $ar_buf[4];
            $results[$i - 1]['mount'] = $ar_buf[5];
            $results[$i - 1]['fstype'] = isset( $fstype[$ar_buf[5]] ) ? $fstype[$ar_buf[5]] : ( isset( $fsdev[$ar_buf[0]] ) ? $fsdev[$ar_buf[0]] : 'N.A.' );
        }

        return $results;
    }
}

?>