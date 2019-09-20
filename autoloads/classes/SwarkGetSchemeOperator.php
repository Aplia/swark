<?php
//
// Swark - extension for eZ Publish
// Author: Ruben Bratsberg <rb@aplia.no> 
// Copyright (C) 2019 Aplia, https://www.aplia.no/
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

class SwarkGetSchemeOperator extends SwarkOperator
{
    function __construct()
    {
        parent::__construct('get_scheme');
    }

    static function execute($operatorValue, $namedParameters)
    {
        if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) {
            return 'https://';
        }
        else {
            return 'http://';
        }
    }
}
