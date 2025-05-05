<?php namespace Joomla\Module\MultiForm\Site; defined('_JEXEC') or die;

/**
 * Multi Form - Easy Ajax Form Module with modal window, with field Editor and create article with form data
 * 
 * @package    Joomla
 * @copyright  Copyright (C) Open Source Matters. All rights reserved.
 * @extension  Multi Extension
 * @subpackage Modules
 * @author		Korenevskiy Sergei.B
 * @license    GNU/GPL, see LICENSE.php
 * @link       http://exoffice/download/joomla
 * mod_multi_form 
 */

//use Joomla\CMS\Helper\ModuleHelper as JModuleHelper;
use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\Factory as JFactory;
use Joomla\Registry\Registry as JRegistry;


abstract class JModuleHelper extends \Joomla\CMS\Helper\ModuleHelper{
    static function ModeuleDelete($module){
        $modules = &static::load();
        foreach ($modules as $i => &$mod){
            if($mod->id == $module->id){
                unset ($modules[$i]); 
                unset ($mod);
            }
        }
        $modules = &static::getModules($module->position); 
        
        $module->published = FALSE;
        $module->position = FALSE;
        $module->module = FALSE;
        $module->style = 'System-none';//System-none
        return $modules;
    }
	
	
    /**
     * Module list
     *
     * @return  array
     */
    public static function getModuleList()
    {
        $app      = JFactory::getApplication();
        $itemId   = $app->input->getInt('Itemid', 0);
        $groups   = $app->getIdentity()->getAuthorisedViewLevels();
        $clientId = (int) $app->getClientId();

        // Build a cache ID for the resulting data object
        $cacheId = implode(',', $groups) . '.' . $clientId . '.' . $itemId;

        $db      = JFactory::getDbo();
        $query   = $db->getQuery(true);
//        $nowDate = JFactory::getDate()->toSql();

        $query->select($db->quoteName(['m.id', 'm.title', 'm.module', 'm.position', 'm.content', 'm.showtitle', 'm.params', 'm.published', 'mm.menuid']))
            ->from($db->quoteName('#__modules', 'm'))
            ->join(
                'LEFT',
                $db->quoteName('#__extensions', 'e'),
                $db->quoteName('e.element') . ' = ' . $db->quoteName('m.module')
                . ' AND ' . $db->quoteName('e.client_id') . ' = ' . $db->quoteName('m.client_id')
            )
            ->join(
                'LEFT',
                $db->quoteName('#__modules_menu', 'mm'),
                $db->quoteName('mm.moduleid') . ' = ' . $db->quoteName('m.id')
            )
            ->where(
                [
//                    $db->quoteName('m.published') . ' IN (0, 1))',
                    $db->quoteName('e.enabled') . ' = 1',
                    $db->quoteName('m.client_id') . ' = 0',
                    $db->quoteName('m.module') . ' = "mod_multi_form"',
                ]
            )
//            ->bind(':clientId', $clientId, ParameterType::INTEGER)
            ->whereIn($db->quoteName('m.published'), [0, 1])
            ->whereIn($db->quoteName('m.access'), $groups);

        // Filter by language
        if ($app->isClient('site') && $app->getLanguageFilter() || $app->isClient('administrator') && static::isAdminMultilang()) {
            $language = $app->getLanguage()->getTag();

            $query->whereIn($db->quoteName('m.language'), [$language, '*'], ParameterType::STRING);
            $cacheId .= $language . '*';
        }

        $query->order($db->quoteName(['m.position', 'm.ordering']));

        // Set the query
        $db->setQuery($query);


//toPrint($query,'',0); 
//toPrint($query->getBounded(),'',0);

		return $db->loadObjectList();


		
        try {
            /** @var CallbackController $cache */
            $cache = JFactory::getContainer()->get(CacheControllerFactoryInterface::class)
                ->createCacheController('callback', ['defaultgroup' => 'mod_multi_form']);// com_modules

            $modules = $cache->get(array($db, 'loadObjectList'), array(), md5($cacheId), false);
        } catch (\RuntimeException $e) {
            $app->getLogger()->warning(
                JText::sprintf('JLIB_APPLICATION_ERROR_MODULE_LOAD', $e->getMessage()),
                array('category' => 'jerror')
            );

            return array();
        }

        return $modules;
    }
}

