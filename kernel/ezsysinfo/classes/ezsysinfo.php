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
// This source file is part of eZ publish, publishing software.
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
        if ( $fp = fopen('/proc/sys/kernel/hostname','r') )
        {
            $result = trim( fgets( $fp, 4096 ) );
            fclose( $fp );
            $result = gethostbyaddr( gethostbyname( $result ) );
        }
        else
        {
            $result = "N.A.";
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

        if ( $fd = @fopen("/proc/pci", "r") )
        {
            while ( $buf = fgets( $fd, 4096 ) )
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

        $handle = @opendir( "/proc/ide" );
        if( $handle !== false )
        {
            while ( $file = readdir($handle) )
            {
                if ( preg_match( "/^hd/", $file ) )
                {
                    $results["$file"] = array();

                    if ( file_exists( "/proc/ide/$file/model" ) )
                        if ( $fd = fopen("/proc/ide/$file/model", "r") )
                        {
                            $results["$file"]["model"] = trim( fgets($fd, 4096) );
                            fclose( $fd );
                        }
                    if ( file_exists( "/proc/ide/$file/capacity" ) )
                        if ( $fd = fopen("/proc/ide/$file/capacity", "r") )
                        {
                            $results["$file"]["capacity"] = trim( fgets($fd, 4096) );
                            fclose( $fd );
                        }
                }
            }
            closedir($handle);
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

        if ( file_exists( "/proc/scsi/scsi" ) and $fd = fopen("/proc/scsi/scsi", "r") )
        {
            while ( $buf = fgets($fd, 4096))
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

        if ( $fd = fopen("/proc/meminfo", "r") )
        {
            while ( $buf = fgets( $fd, 4096 ) )
            {
                if ( preg_match("/Mem:\s+(.*)$/", $buf, $ar_buf ) )
                {
                    $ar_buf = preg_split("/\s+/", $ar_buf[1], 6);

                    $results['ram'] = array();

                    @$results['ram']['total'] = $ar_buf[0] / 1024;
                    @$results['ram']['used'] = $ar_buf[1] / 1024;
                    @$results['ram']['free'] = $ar_buf[2] / 1024;
                    @$results['ram']['shared'] = $ar_buf[3] / 1024;
                    @$results['ram']['buffers'] = $ar_buf[4] / 1024;
                    @$results['ram']['cached'] = $ar_buf[5] / 1024;

                    @$results['ram']['t_used'] = $results['ram']['used'] - $results['ram']['cached'] - $results['ram']['buffers'];
                    @$results['ram']['t_free'] = $results['ram']['total'] - $results['ram']['t_used'];
                    @$results['ram']['percent'] = round( ($results['ram']['t_used'] * 100) / $results['ram']['total']);
                }

                if ( preg_match("/Swap:\s+(.*)$/", $buf, $ar_buf ) )
                {
                    $ar_buf = preg_split("/\s+/", $ar_buf[1], 3);

                    $results['swap'] = array();

                    @$results['swap']['total'] = $ar_buf[0] / 1024;
                    @$results['swap']['used'] = $ar_buf[1] / 1024;
                    @$results['swap']['free'] = $ar_buf[2] / 1024;
                    @$results['swap']['percent'] = round( ($ar_buf[1] * 100) / $ar_buf[0] );
	
                    break;
                }
            }            
            fclose( $fd );
            
        }
        else
        {
            @$results['ram'] = array();
            @$results['swap'] = array();
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

        if ( $fd = fopen("/proc/net/dev", "r") )
        {
            while ( $buf = fgets( $fd, 4096 ) )
            {
                if ( preg_match( "/:/", $buf ) )
                {
                    list( $dev_name, $stats_list ) = preg_split( "/:/", $buf, 2 );
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
        if ( $fd = fopen("/proc/version", "r") )
        {
            $buf = fgets( $fd, 4096 );
            fclose( $fd );

            if ( preg_match( "/version (.*?) /", $buf, $ar_buf ) )
            {
                $result = $ar_buf[1];

                if ( preg_match("/SMP/", $buf) )
                {
                    $result .= " (SMP)";
                }
            }
            else
            {
                $result = "N.A.";
            }
        }
        else
        {
            $result = "N.A.";
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
        if ( $fd = fopen("/proc/loadavg", "r") )
        {
            $results = preg_split( "/[|\s:]/", fgets( $fd, 4096 ) );
            fclose( $fd );
        }
        else
        {
            $results = array("N.A.","N.A.","N.A.");
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
        $result = false;
        $fd = fopen("/proc/uptime", "r");
        $ar_buf = preg_split( "/[|\s:]/", fgets( $fd, 4096 ) );
        fclose( $fd );

        $sys_ticks = trim( $ar_buf[0] );

        $min   = (int) $sys_ticks / 60;
        $hours = $min / 60;
        $days  = floor( $hours / 24 );
        $hours = floor( $hours - ($days * 24) );
        $min   = floor( $min - ($days * 60 * 24) - ($hours * 60) );
    
        if ( $days != 0 )
        {
            $result = "$days days, ";
        }  
        if ( $hours != 0 )
        {
            $result .= "$hours hours, ";
        }
        $result .= "$min minutes";
    
        return $result;
    }


    /*!
      \static
      Returns the number of users currently logged in.
    */      
    static public function users()
    {
        $result = trim( `who | wc -l` );

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
        $ar_buf = array();
	
        if ( $fd = fopen("/proc/cpuinfo", "r") )
        {
            while ( $buf = fgets( $fd, 4096 ) )
            {
                list($key, $value) = array_pad( preg_split("/\s+:\s+/", trim($buf), 2), 2, null );


                // All of the tags here are highly architecture dependant.
                // the only way I could reconstruct them for machines I don't
                // have is to browse the kernel source.  So if your arch isn't
                // supported, tell me you want it written in. <infinite@sigkill.com>
                switch ( $key ) {
                    case "model name":
                        @$results['model'] = $value;
					break;
                    case "cpu MHz":
                        @$results['mhz'] = sprintf("%.2f", $value );
					break;
                    case "clock": // For PPC arch (damn borked POS)
                        @$results['mhz'] = sprintf("%.2f", $value );
					break;
                    case "cpu": // For PPC arch (damn borked POS)
                        @$results['model'] = $value;
					break;
                    case "revision": // For PPC arch (damn borked POS)
                        @$results['model'] .= " ( rev: " . $value . ")";
					break;
                    case "cache size":
                        @$results['cache'] = $value;
					break;
                    case "bogomips":
                        @$results['bogomips'] += $value;
					break;
                    case "processor":
                        @$results['cpus'] += 1;
					break;
                }	
            }
            fclose($fd);
        }

        $keys = eZSysinfo::compat_array_keys( $results );
        $keys2be = array( "model", "mhz", "cache", "bogomips", "cpus" );

        foreach ($keys2be as $key => $ar_buf)
        {
            if (! eZSysinfo::compat_in_array( $ar_buf[1], $keys ) ) $results[$ar_buf[1]] = 'N.A.';
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
        $df = `/bin/df -kP`;
        $mounts = preg_split( "/(\n)/", $df );
        $fstype = array();

        if ( $fd = fopen("/proc/mounts", "r") )
        {
            while ( $buf = fgets( $fd, 4096 ) )
            {
                list($dev, $mpoint, $type ) = preg_split("/\s+/", trim($buf), 4);
                $fstype[$mpoint] = $type;
                $fsdev[$dev] = $type;
            }
            fclose($fd);
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
            ($fstype[$ar_buf[5]]) ? $results[$i - 1]['fstype'] = $fstype[$ar_buf[5]] : $results[$i - 1]['fstype'] = $fsdev[$ar_buf[0]];
        }

        return $results;
    }
    
}

?>