<?php
//
// Swark - extension for eZ Publish
// Author: Jan Kudlicka <jk@seeds.no>, Jan Borsodi <jb@aplia.no>
// Copyright (C) 2008 Seeds Consulting AS, http://www.seeds.no/
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of version 2.0 of the GNU General
// Public License as published by the Free Software Foundation.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
// MA 02110-1301, USA.
//

class SwarkRedirectOperator extends SwarkOperator
{
    function __construct()
    {
        parent::__construct( 'redirect', 'url', 'status=302', 'type=' );
    }

    static function execute( $operatorValue, $namedParameters )
    {
        $redirectUri = $namedParameters['url'];
        if ( is_null( $redirectUri ) )
        {
            $redirectUri = $operatorValue;
        }

        $type = $namedParameters['type'];
        if ( $type && !in_array( $type, array( 'abs', 'root', 'absroot' ) ) )
        {
            throw new \Exception("Invalid value for parameter `type`: " . var_export( $type, true ) );
        }

        // if $redirectUri does not start with scheme://
        if ( !preg_match( '#^\w+://#', $redirectUri ) )
        {
            $absNoScheme = preg_match( '#^//#', $redirectUri );
            if ( !$absNoScheme && $type !== 'root' && $type !== 'absroot' )
            {
                // path to eZ Publish index, includes path-prefix and siteaccess
                $indexDir = eZSys::indexDir();

                // We need to make sure we have one
                // and only one slash at the concatenation point
                // between $indexDir and $redirectUri.
                $redirectUri = rtrim( $indexDir, '/' ) . '/' . ltrim( $redirectUri, '/' );
            }

            // If the `type` parameter is to a 'abs' value then the url is changed to include scheme and hostname
            // If the url starts with // then it adds the scheme no matter what `type` is set to
            if ( $absNoScheme )
            {
                $schema = eZSys::isSSLNow() ? 'https' : 'http';
                $redirectUri = "$schema:$redirectUri";
            }
            else if ( $type === 'abs' || $type === 'absroot' )
            {
                $schema = eZSys::isSSLNow() ? 'https' : 'http';
                $host = strlen( $_SERVER['HTTP_HOST'] ) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
                $redirectUri = "$schema://$host$redirectUri";
            }
        }

        // Redirect to $redirectUri by returning given status code and exit.
        $status = $namedParameters['status'] ? $namedParameters['status'] : 302;
        eZHTTPTool::redirect( $redirectUri, array(), $status );
        eZExecution::cleanExit();
    }
}

?>
