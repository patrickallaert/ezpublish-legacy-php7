<?php
//
// Definition of eZNotificationTransport class
//
// Created on: <13-May-2003 12:01:34 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.10.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file eznotificationtransport.php
*/

/*!
  \class eZNotificationTransport eznotificationtransport.php
  \brief The class eZNotificationTransport does

*/
include_once( 'kernel/classes/notification/ezmailnotificationtransport.php' );
class eZNotificationTransport
{
    /*!
     Constructor
    */
    function eZNotificationTransport()
    {
    }

    static function instance( $transport = false, $forceNewInstance = false )
    {
        $ini = eZINI::instance( 'notification.ini' );
        if ( $transport == false )
        {
            $transport = $ini->variable( 'TransportSettings', 'DefaultTransport' );
        }
        $transportImpl =& $GLOBALS['eZNotificationTransportGlobalInstance_' . $transport ];
        $class = strtolower( get_class( $transportImpl ) );

        $fetchInstance = false;
        if ( !preg_match( '/.*?transport/', $class ) )
            $fetchInstance = true;

        if ( $forceNewInstance  )
        {
            $fetchInstance = true;
        }

        if ( $fetchInstance )
        {
            $extraPluginPathArray = $ini->variable( 'TransportSettings', 'TransportPluginPath' );
            $pluginPathArray = array_merge( array( 'kernel/classes/notification/' ),
                                            $extraPluginPathArray );
            foreach( $pluginPathArray as $pluginPath )
            {
                $transportFile = $pluginPath . $transport . 'notificationtransport.php';
                if ( file_exists( $transportFile ) )
                {
                    include_once( $transportFile );
                    $className = $transport . 'notificationtransport';
                    $impl = new $className( );
                    break;
                }
            }
        }
        if ( $impl === null )
        {
            $impl = new eZNotificationTransport();
            $debug = eZDebug::instance();
            $debug->writeError( 'Transport implementation not supported: ' . $transport, 'eZNotificationTransport::instance' );
        }
        return $impl;
    }

    function send( $address = array(), $subject, $body, $transportData = null )
    {
        return true;
    }
}

?>
