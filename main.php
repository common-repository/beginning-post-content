<?php
/*
Plugin Name: Beginning Post Content
Plugin URI: http://www.danycode.com/beginning-post-content/
Description: This Plugin show custom content to the beginning of each post.
Version: 1.01
Author: Danilo Andreini
Author URI: http://www.danycode.com
License: GPLv2 or later
*/

/*  Copyright 2012  Danilo Andreini (email : andreini.danilo@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//hooks
add_filter('the_content','beginning_post_content');
//actions
add_action( 'admin_menu', 'beginning_post_content_options_menu' );

//options initialization
if(strlen(get_option('beginning_post_content'))==0){update_option('beginning_post_content',"Lorem ipsum dolor sit amet, consectetur adipiscing elit. In lacus eros, commodo at pharetra vitae, bibendum nec ipsum. Curabitur mollis neque sed augue vestibulum suscipit. Suspendisse posuere, lectus ut varius porttitor, metus quam consectetur ipsum, nec condimentum elit nisl id purus.");}
if(strlen(get_option('beginning_post_content_bgcolor'))==0){update_option('beginning_post_content_bgcolor',"FFEFC6");}
if(strlen(get_option('beginning_post_content_bocolor'))==0){update_option('beginning_post_content_bocolor',"735005");}
if(strlen(get_option('beginning_post_content_fcolor'))==0){update_option('beginning_post_content_fcolor',"735005");}
if(strlen(get_option('beginning_post_content_fsize'))==0){update_option('beginning_post_content_fsize',"14");}

//functions

//Add custom text to the beginning of each page
function beginning_post_content($content) {
    if (is_single()){
		$style='background-color: #'.get_option('beginning_post_content_bgcolor').';';
		$style.='border: 1px solid #'.get_option('beginning_post_content_bocolor').';';
		$style.='color: #'.get_option('beginning_post_content_fcolor').';';
		$style.='font-size: '.get_option('beginning_post_content_fsize').'px;';
		$style.='padding: 10px;';
		$content='<p style="'.$style.'">'.get_option('beginning_post_content').'</p>'.$content;
	}
    return $content;
}

// Beginning Post Content Options Menu
function beginning_post_content_options_menu() {
	add_options_page( 'Beginning Post Content', 'Beginning Post Content', 'manage_options', 'beginning_post_content_options', 'beginning_post_content_options' );
}

// Beginning Post Content Options
function beginning_post_content_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}   
    // Save options if user has posted some information
    if(isset($_POST['content']) or isset($_POST['bgcolor']) or isset($_POST['bocolor']) or isset($_POST['fcolor']) or isset($_POST['fsize'])){        
        $content=$_POST['content'];$bgcolor=$_POST['bgcolor'];$bocolor=$_POST['bocolor'];$fcolor=$_POST['fcolor'];$fsize=$_POST['fsize'];
        //defensive controls
	    if(!preg_match('/^([0-9a-f]{1,2}){3}$/i',$bgcolor)){$errors[]="Only RGB color as background color.";}//only rgb colors
	    if(!preg_match('/^([0-9a-f]{1,2}){3}$/i',$bocolor)){$errors[]="Only RGB color as border color.";}//only rgb colors
	    if(!preg_match('/^([0-9a-f]{1,2}){3}$/i',$fcolor)){$errors[]="Only RGB color as font color.";}//only rgb colors
	    if(!preg_match('/^[0-9]{1,2}$/',$fsize)){$errors[]="Only number as font size";}//only numbers, 1 or 2 digits
        if(count($errors)==0){
			// Save into database
			update_option('beginning_post_content',$content);
			update_option('beginning_post_content_bgcolor',$bgcolor);
			update_option('beginning_post_content_bocolor',$bocolor);
			update_option('beginning_post_content_fcolor',$fcolor);
			update_option('beginning_post_content_fsize',$fsize);
			echo '<div class="updated"><p>Your options have been saved</p></div>';
		}else{
			//show error message
			echo '<div class="error">';
			foreach($errors as $error){echo '<p>'.$error.'</p>';}
			echo '</div>';
		}
		
	}	
	echo '<h2>Beginning Post Content Options</h2>';
	echo '<form method="post" action="">';
	echo '<div><textarea rows="4" cols="40" maxlength="10000" name="content">'.stripslashes(get_option('beginning_post_content')).'</textarea></div>';
	echo '<input autocomplete="off" maxlength="6" size="6" type="text" name="bgcolor" value="'.get_option('beginning_post_content_bgcolor').'"><span>Background Color</span><br />';
	echo '<input autocomplete="off" maxlength="6" size="6" type="text" name="bocolor" value="'.get_option('beginning_post_content_bocolor').'"><span>Border Color</span><br />';
	echo '<input autocomplete="off" maxlength="6" size="6" type="text" name="fcolor" value="'.get_option('beginning_post_content_fcolor').'"><span>Font Color</span><br />';
	echo '<input autocomplete="off" maxlength="2" size="6" type="text" name="fsize" value="'.get_option('beginning_post_content_fsize').'"><span>Font Size</span><br />';
	echo '<input type="submit" value="Save">';
	echo '</form>';
	echo '<p>Ask for support at <a target="_blank" href="http://www.danycode.com/beginning-post-content/">Beginning Post Content Official Page</a></p>';	
}
?>
