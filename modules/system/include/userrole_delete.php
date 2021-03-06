<?php

/*©lgpl*************************************************************************
*                                                                              *
* This file is part of FRIEND UNIFYING PLATFORM.                               *
* Copyright (c) Friend Software Labs AS. All rights reserved.                  *
*                                                                              *
* Licensed under the Source EULA. Please refer to the copy of the GNU Lesser   *
* General Public License, found in the file license_lgpl.txt.                  *
*                                                                              *
*****************************************************************************©*/

global $SqlDatabase, $Logger, $User;

// Must be admin
if( $level != 'Admin' )
	die( '404' );

if( !isset( $args->args->name ) && !isset( $args->args->id ) )
{
	die( 'fail<!--separate-->{"message":"Please specify the name or id of your role.","response":-1}' );
}
	
$d = new dbIO( 'FUserGroup' );

if( isset( $args->args->id ) )
{
	$d->Load( $args->args->id );
}
else
{
	$d->Type = 'Role';
	$d->Name = trim( $args->args->name );
	$d->Load();
}

if( $d->ID > 0 )
{
	$d->delete();
	die( 'ok<!--separate-->' . json_encode( $d ) );
	die( 'ok<!--separate-->{"message":"Role deleted.","response":1}' );
}

die( 'fail<!--separate-->{"message":"Role not found.","response":-1}' );

?>
