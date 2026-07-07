<?php

if (!defined('ABSPATH')) exit;

if( !class_exists( 'TSBPlugin' ) ){
    class TSBPlugin{
        function __construct(){
            $this->loaded_classes();
        }
 
        function loaded_classes(){
			require_once TSB_DIR_PATH . 'includes/rootPlugin/Init.php';
			require_once TSB_DIR_PATH . 'includes/rootPlugin/Enqueue.php';
			require_once TSB_DIR_PATH . 'includes/rootPlugin/AdminMenu.php';
			require_once TSB_DIR_PATH . 'includes/rootPlugin/ShortCode.php';
			require_once TSB_DIR_PATH . 'includes/rootPlugin/CustomColumn.php';
			require_once TSB_DIR_PATH . 'includes/rootPlugin/BlockCategory.php';
			require_once TSB_DIR_PATH . 'includes/rootPlugin/RestApi.php';
		

			new TSB\Init();
			new TSB\Enqueue();
			new TSB\AdminMenu();
			new TSB\ShortCode();
			new TSB\CustomColumn();
			new TSB\BlockCategory();
			new TSB\RestApi();
		}
		
        
    }
    new TSBPlugin();
}
