<?php
/*
	Plugin Name: 			Kevinjohn Gallagher: Pure Web Brilliant's Cross Pollination Post Pagination
	Description: 			Transforms the standard next/previous links to work across your choice of post types
	Version: 				3.0	
	Author: 				Kevinjohn Gallagher
	Author URI: 			http://kevinjohngallagher.com/
	
	Contributors:			kevinjohngallagher, purewebbrilliant, pure-web-brilliant 
	Donate link:			http://kevinjohngallagher.com/
	Tags: 					kevinjohn gallagher, pure web brilliant, framework, cms, facebook, opengraph, open graph, social, social media, twitter, twitter cards, google+
	Requires at least:		3.0
	Tested up to: 			3.5
	Stable tag: 			3.0
*/
/**
 *
 *	Kevinjohn Gallagher: Pure Web Brilliant's Cross Pollination Post Pagination
 * =============================================================================
 *
 *	Transforms the standard next/previous links to work across your choice of post types.
 *
 *
 *	This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 *	General Public License as published by the Free Software Foundation; either version 3 of the License, 
 *	or (at your option) any later version.
 *
 * 	This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
 *	without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *	See the GNU General Public License (http://www.gnu.org/licenses/gpl-3.0.txt) for more details.
 *
 *	You should have received a copy of the GNU General Public License along with this program.  
 * 	If not, see http://www.gnu.org/licenses/ or http://www.gnu.org/licenses/gpl-3.0.txt
 *
 *
 *	Copyright (C) 2008-2012 Kevinjohn Gallagher / http://www.kevinjohngallagher.com
 *
 *
 *	@package				Pure Web Brilliant
 *	@version 				3.0
 *	@author 				Kevinjohn Gallagher <wordpress@kevinjohngallagher.com>
 *	@copyright 				Copyright (c) 2012, Kevinjohn Gallagher
 *	@link 					http://kevinjohngallagher.com
 *	@license 				http://www.gnu.org/licenses/gpl-3.0.txt
 *
 *
 */



 	if ( ! defined( 'ABSPATH' ) )
 	{ 
 			die( 'Direct access not permitted.' ); 
 	}
 	
 	
 	

	define( '_KEVINJOHN_GALLAGHER___cppp_control', '3.0' );



	if (class_exists('kevinjohn_gallagher')) 
	{
		
		
		class	kevinjohn_gallagher___cppp_control 
		extends kevinjohn_gallagher
		{
		
				/*
				**
				**		VARIABLES
				**
				*/
				const PM		=	'_kevinjohn_gallagher___cppp_control';
				
				var					$instance;
				var 				$plugin_name;
				var					$uniqueID;
				var					$plugin_dir;
				var					$plugin_url;
				var					$plugin_page_title; 
				var					$plugin_menu_title; 					
				var 				$plugin_slug;
				var 				$http_or_https;
				var 				$plugin_options;
				
				var 				$meta_array;
				var 				$post_type;
				
				var 				$kjg_pwb_ccp;
				var 				$all_post_types;

				

		
				/*
				**
				**		CONSTRUCT
				**
				*/
				public	function	__construct() 
				{
						$this->instance 					=&	$this;
						$this->uniqueID 					=	self::PM;
						$this->plugin_dir					=	plugin_dir_path(__FILE__);	
						$this->plugin_url					=	plugin_dir_url(__FILE__);							
						$this->plugin_name					=	"Kevinjohn Gallagher: Pure Web Brilliant's Cross Pollination Post Pagination";
						$this->plugin_page_title			=	"Cross Pollination Post Pagination"; 
						$this->plugin_menu_title			=	"Cross Pollination Post Pagination"; 					
						$this->plugin_slug					=	"cppp-control";
						
						
						
						/*
						$this->child_settings_sections 							=	array();
						$this->child_settings_array 							=	array();						
						*/
						
						add_action( 'init',				array( $this, 'init' ) );
						add_action( 'init',				array( $this, 'init_child' ) );
						add_action(	'admin_init',		array( $this, 'admin_init_register_settings'), 100);
						add_action( 'admin_menu',		array( $this, 'add_plugin_to_menu'));
						
												
				}
				
				
				
				
				
				/*
				**
				**		INIT_CHILD
				**
				*/
			
				public function init_child() 
				{
			
						add_filter( 'kjg_framework_validate_options_filter', 		array( 	&$this, 	'kjg_cppp_validation' ) );
				
						
						add_filter( 'get_next_post_where', 							array( 	&$this, 	'crazily_simple_solution' ) );
						add_filter( 'get_previous_post_where', 						array( 	&$this, 	'crazily_simple_solution' ) );
						
				}
				
				
				public 	function 	crazily_simple_solution($args)
				{
				

						$string_to_return 		= 		$args;
						
						$new_sql 				= 		$this->plugin_options['kjg_cppp_sql'];
						
												
						
						if( !empty( $new_sql ) && $new_sql == '' )
						{
						
								$split_one 				= 		explode("p.post_type = '", 		$args);
								
								if( !empty( $split_one[1] ) )
								{
								
										$split_two 			= 		explode("'", 					$split_one[1]);
				
				
										$replace 			= 		"p.post_type = '". $split_two[0] ."'";
										$replace_by 		=		"p.post_type " . $new_sql ;
										
				
										$string_to_return 	= 		str_replace( $replace, 	$replace_by , 	$args);
								
								}
						
						}
			
												
						return 	$string_to_return;	
						
				}
				
				
				
				
				
				public 	function 	define_child_settings_sections()
				{
				
						$this->child_settings_sections['section_kjg_cppp_builtin']					= ' Built In from WordPress: ';
						$this->child_settings_sections['section_kjg_cppp_cpt']						= ' Custom Post Types: ';
					//	$this->child_settings_sections['section_kjg_cppp_hidden']					= ' Hidden from users by default: ';
												
				}
				
				
				
				public 	function 	define_child_settings_array()
				{				

					
						$args 			= 		array();
						
						$post_types 	= 		get_post_types($args,'object');
						
						
						foreach( $post_types as $post_type)
						{
									
									$which_section 	= 	'';
									
									if( !$post_type->public )
									{
											$which_section 	= 	'section_kjg_cppp_hidden';
										
									} 	else 	{
										
										if( $post_type->_builtin )
										{
											
												$which_section 	= 	'section_kjg_cppp_builtin';
											
										} 	else 	{
											
												$which_section 	= 	'section_kjg_cppp_cpt';
											
										}
										
										
									}
								

									$this->child_settings_array['kjg_cppp_'. $post_type->name] 	= array(
																											'id'      		=> 	'kjg_cppp_'. $post_type->name,
																											'title'   		=> 	$post_type->label,
																											'description'	=> 	' ',
																											'type'    		=> 	'checkbox',
																											'section' 		=> 	$which_section,
																											'choices' 		=> 	array(																	
																																	),
																											'class'   		=> 	''
																										);
						
							
						}						
																								

				}		
				
				
				
				
				public 	function 	kjg_cppp_validation($input)
				{
						if( !empty( $input ) )
						{					
						
								$new_sql 		= 		"";
								
								foreach( $input as $key => $value)
								{
										$post_type 		= 		str_replace('kjg_cppp_', '', $key);
										
										if( !empty( $post_type ))
										{										
												$new_sql 		= 		$new_sql . " '". $post_type ."', ";
										}
								}
								
		
								$new_sql 	= 	rtrim( $new_sql, ", " );
		
								
		
								if( $new_sql != '' )
								{
										$new_sql 		= 		" IN ( ". $new_sql ." )";
										$input['kjg_cppp_sql'] 		= 		$new_sql;
								}
						
						}
						
					//	print_r( $input );
									
						return 	$input;
				}
						
				

				/*
				**
				**		ADD_PLUGIN0_TO_MENU
				**
				*/				
				public 	function 	add_plugin_to_menu()
				{
						$this->framework_admin_menu_child(	$this->plugin_page_title, 
															$this->plugin_menu_title, 
															$this->plugin_slug, 
															array($this, 	'child_admin_page')
														);
				}
				
				
				
				

				/*
				**
				**		CHILD ADMIN PAGE
				**
				*/				
				public 	function 	child_admin_page()
				{
						$this->framework_admin_page_header('Cross Pollination Post Pagination', 'icon_class');
					 
						$this->framework_admin_page_footer();				
				}
				
				


				
		
		
		}	//	class
		
	
		$kevinjohn_gallagher___cppp_control			=	new kevinjohn_gallagher___cppp_control();
		
	
	} else {
	

			function kevinjohn_gallagher___cppp_control___parent_needed()
			{
					echo	"<div id='message' class='error'>";
					
					echo	"<p>";
					echo	"<strong>Kevinjohn Gallagher: Social Graph Control</strong> ";	
					echo	"requires the parent framework to be installed and activated";
					echo	"</p>";
			} 

			add_action('admin_footer', 'kevinjohn_gallagher___cppp_control___parent_needed');	
	
	}

