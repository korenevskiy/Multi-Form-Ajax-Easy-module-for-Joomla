<?php defined('_JEXEC') or die;
/**------------------------------------------------------------------------
# mod_multi - Modules Conatinier 
# ------------------------------------------------------------------------
# author    Sergei Borisovich Korenevskiy
# Copyright (C) 2010 www./explorer-office.ru. All Rights Reserved.
# @package  mod_multi
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: //explorer-office.ru/download/joomla/category/view/1
# Technical Support:  Forum - //fb.com/groups/multimodule
# Technical Support:  Forum - //vk.com/multimodule
-------------------------------------------------------------------------*/ 


use \Joomla\CMS;
use Joomla\CMS\Factory as JFactory;

if($work_type_require == 'and'){
    
    
            #1 Require for web site address
            $domen_is = $param->domen_is;//off, only , without
            if($domen_is):
                $domen_sites = $param->domen_site ?: $_SERVER['HTTP_HOST'];
                $domen_sites = modMultiFormHelper::replace('/','',$domen_sites); 
                $domen_sites = modMultiFormHelper::replace('www.','',$domen_sites);
            //echo '<pre style"color: green;">'.print_r($web_sites,true).'</pre>';
            //echo '<pre style"color: green;">'.print_r($_SERVER['HTTP_HOST'],true).'</pre>';
                if($domen_is == 'only' && !modMultiFormHelper::inArray ($_SERVER['HTTP_HOST'],$domen_sites,'www.')){
                    //throw new Exception('only');
                    return FALSE;
                }
                else if($domen_is == 'without' && modMultiFormHelper::inArray ($_SERVER['HTTP_HOST'],$domen_sites,'www.')){
                    //throw new Exception('without');
                    return FALSE;
                }
            endif;
            
            #2 Require for ip user address
            $ip_user_is = $param->ip_user_is;//off, only , without
            if($ip_user_is):
                $ip_user = $param->ip_user;
                $ip_user = modMultiFormHelper::replace(',','.',$ip_user);
                $ips_user = modMultiFormHelper::replace('00.','0.',$ip_user); 
                if($ip_user_is == 'only' && !modMultiFormHelper::inArray($_SERVER['REMOTE_ADDR'], $ips_user)){
                    return FALSE;
                }
                else if($ip_user_is == 'without' && modMultiFormHelper::inArray($_SERVER['REMOTE_ADDR'], $ips_user)){
                    return FALSE;
                }
            endif;
            
             
            #3 Require for debug url task "index.php?deb=1"
            $is_debug = $param->is_debug;//off, only , without
            if($is_debug):
                $is_deb = JFactory::getApplication()->input->getBool('deb');
                if($is_debug == 'only' && !$is_deb){
                    return FALSE;
                }
                else if($is_debug == 'without' && $is_deb){
                    return FALSE;
                }
            endif;
            
           
            #4 Require for items menu
            $menu_is = $param->menu_is;//off, only , without
            if($menu_is):
                $menu_assigment = (array)$param->menu_assigment?:[]; 
                $menu_id = JFactory::getApplication()->getMenu()->getActive()->id; 
                if(in_array(0, $menu_assigment) || empty($menu_assigment)){ 
                }
                elseif($menu_is == 'only' && !in_array($menu_id, $menu_assigment)){ 
                    return FALSE; 
                }
                else if($menu_is == 'without' && in_array($menu_id, $menu_assigment)){ 
                    return FALSE;
                }
            endif;
            
            #5 Require for type component
            $component_is = $param->component_is;//off, only , without
            if($component_is):
                $component_site = (array)$param->component_site?:[]; 
                $component_id = JFactory::getApplication()->getMenu()->getActive()->component_id; 
                if(empty($component_site)){ 
                }
                elseif($component_is == 'only' &&  !in_array($component_id, $component_site)){ 
                    return FALSE; 
                }
                else if($component_is == 'without' &&  in_array($component_id, $component_site)){ 
                    return FALSE;
                }
            endif;
            
            #6 Require for view component
            $view_is = $param->view_is;//off, only , without
            if($view_is):
                $views =  $param->view_site;  
                $view_site = JFactory::getApplication()->input->getWord('view'); 
                if(empty($views)){ 
                }
                elseif($view_is == 'only' &&  !modMultiFormHelper::inArray($view_site, $views)){ 
                    return FALSE; 
                }
                else if($view_is == 'without' &&  modMultiFormHelper::inArray($view_site, $views)){ 
                    return FALSE;
                }
            endif;
            
            #7 Require for Date shows
            $view_is = $param->date_is;//off, only , without
            if($view_is):
                $date_start =  $param->date_start; 
                $date_stop  =  $param->date_stop;  
                $now = JDate::getInstance()->format('Y-m-d'); 
                if(empty($date_start) || empty($date_stop)){ 
                } 
                else if($date_stop < $now &&  $now < $date_start){ 
                    return FALSE;
                } 
                else if($now < $date_start && $date_start <= $date_stop){ 
                    return FALSE;
                }
                else if($date_start <= $date_stop && $date_stop < $now){ 
                    return FALSE;
                }
            endif;
            
    
    return TRUE; 
}else{//$work_type_require == 'or'
    
    
            #1 Require for web site address
            $domen_is = $param->domen_is;//off, only , without
            if($domen_is):
                $domen_sites = $param->domen_site ?: $_SERVER['HTTP_HOST']; 
                $domen_sites = modMultiFormHelper::replace('/','',$domen_sites);  
                $domen_sites = modMultiFormHelper::replace('www.','',$domen_sites);
                if($domen_is == 'only' && modMultiFormHelper::inArray ($_SERVER['HTTP_HOST'],$domen_sites,'www.')){ 
                    return TRUE;
                }
                else if($domen_is == 'without' && !modMultiFormHelper::inArray ($_SERVER['HTTP_HOST'],$domen_sites,'www.')){ 
                    return TRUE;
                }
            endif;
            
            #2 Require for ip user address
            $ip_user_is = $param->ip_user_is;//off, only , without
            if($ip_user_is):
                $ip_user = $param->ip_user;
                $ip_user = modMultiFormHelper::replace(',','.',$ip_user);
                $ips_user = modMultiFormHelper::replace('00.','0.',$ip_user);  
                if($ip_user_is == 'only' && modMultiFormHelper::inArray ($_SERVER['REMOTE_ADDR'],$ips_user)){ 
                    return TRUE;
                }
                else if($ip_user_is == 'without' && !modMultiFormHelper::inArray ($_SERVER['REMOTE_ADDR'],$ips_user)){ 
                    return TRUE;
                }
            endif;
            
             
            #3 Require for debug url task "index.php?deb=1"
            $is_debug = $param->is_debug;//off, only , without
            if($is_debug):
                $is_deb = JFactory::getApplication()->input->getBool('deb');
                if($is_debug == 'only' && $is_deb){
                    return TRUE;
                }
                else if($is_debug == 'without' && !$is_deb){
                    return TRUE;
                }
            endif;
            
            
            #4 Require for items menu
            $menu_is = $param->menu_is;//off, only , without
            if($menu_is):
                $menu_assigment = (array)$param->menu_assigment?:[];
                $menu_id = JFactory::getApplication()->getMenu()->getActive()->id; 
            
                if(in_array(0, $menu_assigment) || empty($menu_assigment)){
                    
                }
                elseif($menu_is == 'only' && in_array($menu_id, $menu_assigment)){
                    return TRUE;
                }
                elseif($menu_is == 'without' && !in_array($menu_id, $menu_assigment)){
                    return TRUE;
                }
            endif;
    
            
            #5 Require for type component
            $component_is = $param->component_is;//off, only , without
            if($component_is):
                $component_site = (array)$param->component_site ?: [];  
                $component_id = JFactory::getApplication()->getMenu()->getActive()->component_id; 
                if( empty($component_site)){
                    
                }
                elseif($component_is == 'only' && in_array($component_id, $component_site)){
                    return TRUE;
                }
                elseif($component_is == 'without' && !in_array($component_id, $component_site)){
                    return TRUE;
                }
            endif;
            
            
            
            #6 Require for view component
            $view_is = $param->view_is;//off, only , without
            if($view_is):
                $views =  $param->view_site;  
                $view_site = JFactory::getApplication()->input->getWord('view'); 
                if(empty($views)){ 
                }
                elseif($view_is == 'only' &&  modMultiFormHelper::inArray($view_site, $views)){ 
                    return TRUE; 
                }
                else if($view_is == 'without' &&  !modMultiFormHelper::inArray($view_site, $views)){ 
                    return TRUE;
                }
            endif;
            
            #7 Require for Date shows
            $view_is = $param->date_is;//off, only , without
            if($view_is):
                $date_start =  $param->date_start; 
                $date_stop  =  $param->date_stop;  
                $now = JDate::getInstance()->format('Y-m-d');  
                if(empty($date_start) || empty($date_stop)){
                }
                else if($date_start == $now || $now == $date_stop){
                    return TRUE;
                } 
                else if($date_start < $now && $now < $date_stop){
                    return TRUE;
                }
                else if($now < $date_stop && $date_stop < $date_start){
                    return TRUE;
                }
                else if($date_stop < $date_start && $date_start < $now){
                    return TRUE;
                }
            endif;
            
    return FALSE; 
}