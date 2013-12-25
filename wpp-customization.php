<?php
/**
 * Plugin Name: Wpp Customization
 * Plugin URI: https://mhdriyaz.wordpress.com
 * Description: This plugin used to customize wordpress admin footer
 * Version: 1.0
 * Author: Mohamed Riyaz
 * Author URI: https://mhdriyaz.wordpress.com
 * License: GPL2
 * 
 * Copyright YEAR  MOHAMED RIYAZ  (email : mhdryz@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
 */
class wpp_customize{
    
    function __construct() {
        
        global $wpp_options;
        
        $wpp_options = get_option('wpp_options');							
		
		//Wp Core Update
		if($wpp_options['wpp_core_update']==1){		
			
			add_action( 'init', create_function( '$a', "remove_action( 'init', 'wp_version_check' );" ), 2 );		
			add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );
			
		}
		
		//Disable Plugin Updates
		if($wpp_options['wpp_plugin_update']==1){		
		
			remove_action( 'load-update-core.php', 'wp_update_plugins' );		
			add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );
			
		}
		
		//Hide Admin bar
		if($wpp_options['wpp_admin_bar']==1){		
			
			add_filter('show_admin_bar', '__return_false');
			
		}		
		        
        //To add menu
        add_action('admin_menu',array(&$this,'wpp_create_menu'));
        
        // To modify footer text
        add_filter('admin_footer_text',array(&$this,'wpp_customize_footer_admin')); 
        
        //To remove wordpress version from footer
        add_filter( 'update_footer',array(&$this,'wpp_remove_footer_version'), 9999);
        
    }
    
    // ---------- Contains Settings ---------------------- // 
    function wpp_customize_footer_admin(){
        
        global $wpp_options;        
                
        $footer_text = $wpp_options['wpp_footer_text']; 
        
        if(!empty($wpp_options) && trim($footer_text)!=''){
            
            return $footer_text;            
            
        }
        
    }    
    
    function wpp_remove_footer_version(){
        
        global $wpp_options;
        
        $footer_version =  $wpp_options['wpp_footer_version'];
        
        if($footer_version==true)
            return ' ';
        
    }
    
    function wpp_create_menu(){
	
		add_menu_page( 'Wpp Customization', 'Wpp Customize', 'manage_options', 'wpp_customize', array(&$this,'wpp_admin_footer_settings') );         
                
    }
    
    function wpp_admin_footer_settings(){
        
        global $wpp_options;
        
        if(isset($_POST['wpp_save'])){
            
            $wpp_options = $_POST['wpp_options'];
            
            update_option('wpp_options',$wpp_options);
            
            $updated = 1;
			
			wp_redirect(site_url()."/wp-admin/themes.php?page=wpp_admin_footer&updated=true");
			
			exit();
        }
        
        ?>

        <div class="wrap" id="wpp_admin_footer_panel">
            
            <div id="icon-themes" class="icon32">
                <br>
            </div>
            
            <h2>Settings</h2>
            
            <?php 
			$updated = $_GET['updated'];
			
			if($updated==true){?>
            
            <div id="message" class="updated fade">
                <p>
                    <strong>Options Saved</strong>
                </p>
            </div>
            
            <?php } ?>
            
            <form name="wpp_admin_footer_form" action="" method="POST" id="wpp_admin_footer_form">
                
                <table class="form-table">                    
                    <tbody>
                        <tr>
                            <td><label>Remove Footer Version</label></td>
                            <td><input type="checkbox" value="1" <?php if($wpp_options['wpp_footer_version']==1){?> checked="checked" <?php }?>id="wpp_footer_version" name="wpp_options[wpp_footer_version]"></td>
                        </tr>
						
						<tr>
                            <td><label>Remove Wordpress Core Update Notifications</label></td>
                            <td><input type="checkbox" value="1" <?php if($wpp_options['wpp_core_update']==1){?> checked="checked" <?php }?>id="wpp_core_update" name="wpp_options[wpp_core_update]"></td>
                        </tr>
                        
						
						<tr>
                            <td><label>Remove Wordpress Plugin Update Notifications</label></td>
                            <td><input type="checkbox" value="1" <?php if($wpp_options['wpp_plugin_update']==1){?> checked="checked" <?php }?>id="wpp_plugin_update" name="wpp_options[wpp_plugin_update]"></td>
                        </tr>
						
						<tr>
                            <td><label>Remove Admin bar from frontend</label></td>
                            <td><input type="checkbox" value="1" <?php if($wpp_options['wpp_admin_bar']==1){?> checked="checked" <?php }?>id="wpp_admin_bar" name="wpp_options[wpp_admin_bar]"></td>
                        </tr>
                        
						
						<tr>
                            <td><label>Footer Copyright text</label></td>
                            <td><input type="text" name="wpp_options[wpp_footer_text]" id="wpp_footer_text" value="<?php echo $wpp_options['wpp_footer_text'];?>"></td>
                        </tr>
                        <tr>
                            <td><input type="submit" name="wpp_save" id="wpp_save" class="button-primary" value="Save"></td>
                        </tr>
                    </tbody>
                </table>
                
            </form>
                        
        </div>

        <?php
        
    }
    
}
$wpp_obj = new wpp_customize();
?>