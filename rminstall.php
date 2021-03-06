<?php

/**
 * The PHP script to delete the install directory. 
 * 
 * NOTICE OF LICENSE
 * 
 * Copyright (C) Microsoft Corporation All rights reserved.
 * 
 * This program is free software; you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License version 2 as published 
 * by the Free Software Foundation.
 * This program is distributed in the hope that it will be useful, 
 * but WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 * See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License 
 * along with this program; if not, write to the 
 * Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 * 
 */

//get the application root folder
define('PHPBB_ROOT', getcwd());

// perform delete operation only if the install is complete
if (@file_exists(PHPBB_ROOT. '/config.php') 
    && !file_exists(PHPBB_ROOT . 'cache/install_lock')
    ) {
	    include_once PHPBB_ROOT. '/config.php';

	    if (defined('PHPBB_INSTALLED')) {
	    	//install directory path
	    	$install_dir = PHPBB_ROOT .'\install';
	    	if(is_dir($install_dir)) {
		    	//delete the install directory
		    	$deleted = del_dir($install_dir);
	            // after removing the install folder redirect to home page.
	            if ($deleted) {
	            	header('Location: ./');
	            } else {
	                echo 'Can\'t delete the install directory. Please check the permissions of install directory.';
	            }
	    	} else {
	    		header('Location: ./');
	    	}
	    } else {
	    	echo 'phpBB is not installed, please complete the phpBB installation and then try this script.';
	    }
} else {
	echo 'Something wrong with your phpBB installation, please make sure that phpBB is installed completely';
}

/**
 * Delete a directory recursively
 * 
 * @param $dir String Directory to be removed
 * 
 * @return Boolean true on success
 */
function del_dir($dir) {
    $fp = opendir($dir);
    if ( $fp ) {
        while ( $current_file = readdir($fp) ) {
            $full_file = $dir . "/" . $current_file;
            //if the curent path is a directory call the del_dir again, else delete the file directly
            if ( $current_file == "." || $current_file == ".." ) {
                continue;
            } else if ( is_dir($full_file) ) {
                del_dir($full_file);
            } else {
                unlink($full_file);
            }
        }
        closedir($fp);
        // after making the directory empty call the rmdir to delete the empty root directory
        return rmdir($dir);
    }
}