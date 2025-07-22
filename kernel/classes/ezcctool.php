<?php
// 
// $Id: ezcctool.php 6200 2001-07-19 11:33:57Z jakobn $
//
// Definition of eZCCTool class
//
// Created on: <07-Feb-2001 10:59:41 ce>
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

//! Provide utility functions for CreditCards.
/*!
*/

class eZCCTool
{
    function checkCC( $CCNumber,$month,$year )
    {
        $ret = false;
        
        if ( $CCNumber != false )
        {
            // Set the current date
            $date = time();
            $currentYear = date( "Y", $date );
            $currentMonth = date( "m", $date );

            if ( $month < $currentMonth && $year == $currentYear )
            {
                return false;
            }
            else
            {
                $ret = true;
            }

            $CCNumberLength = strlen ( $CCNumber );

            if ( $CCNumberLength != 13 && $CCNumberLength != 15 && $CCNumberLength != 16 )
            {
                return false;
            }
            else
            {
                $ret = true;
            }


            if ( ( $CCNumberLength % 2 ) == 0 )
            {
                $pair = 0;
            }
            else
            {
                $pair = 2;
            }

            // Get each number of the card and put it in an array
            for ( $i=0; $i != $CCNumberLength; $i++ )
            {
                $numberArray[$i] = substr( $CCNumber, $i, 1 );
            }

            // Algorithm Mod10 modificated (by me :-)
            for ( $nume = ($p/2); $nume != $CCNumberLength-$pair; $nume = $nume + 2)
            {
                $numberArray[$nume] = $numberArray[$nume] * 2;
                if ( $numberArray[$nume] >= 10 )
                {
                    $X1 = substr( $numberArray[$nume],0,1 );
                    $X2 = substr( $numberArray[$nume],1,1 );
                    $numberArray[$nume] = substr( $numberArray[$nume],0,1 ) + substr( $numberArray[$nume],1,1 );
                }
            }
        
            // Sums each value of the modificated array
            // Check the result of the algorithm
            for( $i=0; $i != $CCNumberLength; $i++ )
            {
                $val = $val + $numberArray[$i];
            }

            // Please note: if the second number of the result of algorithm is not 0
            // then the card is NOT valid
            if ( $val == 0 )
            {
                return false;
            }

            if ( substr( $val,1,1 ) != 0)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }
}

?>