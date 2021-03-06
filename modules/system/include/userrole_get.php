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

// What it says!
function getPermissionsForRole( $role )
{
	global $SqlDatabase;
	
	if( $perms = $SqlDatabase->FetchObjects( '
		SELECT 
			p.ID, p.Permission, p.Key, p.Data
		FROM 
			FUserRolePermission p 
		WHERE 
			p.RoleID = ' . $role->ID . ' 
		ORDER BY 
			p.ID 
	' ) )
	{
		// Create clean permission objects without database crap
		$permissions = array();
		$keys = array( 'ID', 'Permission', 'Key', 'Data' );
		foreach( $perms as $perm )
		{
			$co = new stdClass();
			foreach( $keys as $kk )
			{
				$co->$kk = $perm->$kk;
			}
			$permissions[] = $co;
		}
		return $permissions;
	}
	return false;
}

// We need a listout of all Roles ... so no arguments is then allowed ...
if( !isset( $args->args->name ) && !isset( $args->args->id ) )
{	
	$out = array();
	
	if( $rows = $SqlDatabase->FetchObjects( $q = '
		SELECT 
			g.*, u.UserID AS UserRoleID, w.FromGroupID AS WorkgroupRoleID 
		FROM 
			FUserGroup g 
				LEFT JOIN FUserToGroup u ON 
				( 
						u.UserGroupID = g.ID 
					AND u.UserID = ' . ( isset( $args->args->userid ) && $args->args->userid ? intval( $args->args->userid, 10 ) : 'NULL' ) . ' 
				)
				LEFT JOIN FGroupToGroup w ON
				(
						w.ToGroupID = g.ID 
					AND w.FromGroupID = ' . ( isset( $args->args->groupid ) && $args->args->groupid ? intval( $args->args->groupid, 10 ) : 'NULL' ) . ' 
				) 
		WHERE 
			g.Type = "Role" 
		ORDER BY 
			g.Name 
	' ) )
	{
		foreach( $rows as $row )
		{
			$o = new stdClass();
			$o->ID          = $row->ID;
			$o->UserID      = $row->UserRoleID;
			$o->WorkgroupID = $row->WorkgroupRoleID;
			$o->ParentID    = $row->ParentID;
			$o->Name        = $row->Name;
			
			$o->Permissions = getPermissionsForRole( $row );
			
			// Add to list
			$out[] = $o;
		}
		
		die( 'ok<!--separate-->' . json_encode( $out ) );
	}
	
	die( 'fail<!--separate-->{"message":"No roles listed or on user.","response":-1}' );
}

// Just fetch by id
$fin = false;
$d = new dbIO( 'FUserGroup' );



if( isset( $args->args->id ) )
{
	if( $d->Load( $args->args->id ) )
	{
		// Sanitized copy
		$fin = new stdClass();
		foreach( $d->_fieldnames as $f )
			$fin->$f = $d->$f;
		
		// Fetch role permissions
		$fin->Permissions = getPermissionsForRole( $d );
	}
	
}
// Get by name - the most primitive one
else
{
	$d->Type = 'Role';
	$d->Name = trim( $args->args->name );
	if( $d->Load() )
	{
		// Sanitized copy
		$fin = new stdClass();
		foreach( $d->_fieldnames as $f )
			$fin->$f = $d->$f;
		
		// Fetch role permissions
		$fin->Permissions = getPermissionsForRole( $d );
	}
}

// Output json encoded structure to the client
if( $fin && $fin->ID > 0 )
{
	die( 'ok<!--separate-->' . json_encode( $fin ) );
}

die( 'fail<!--separate-->{"message":"Role not found.","response":-1}' );

?>
