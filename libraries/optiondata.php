<?php namespace Joomla\Module\MultiForm\Site; defined('_JEXEC') or die;
/**------------------------------------------------------------------------
 * field_cost - Fields for accounting and calculating the cost of goods
 * ------------------------------------------------------------------------
 * author    Sergei Borisovich Korenevskiy
 * Copyright (C) 2010 www./explorer-office.ru. All Rights Reserved.
 * @package  mod_multi_form
 * @license  GPL   GNU General Public License version 2 or later;
 * Websites: //explorer-office.ru/download/joomla/category/view/1
 * Technical Support:  Forum - //fb.com/groups/multimodule
 * Technical Support:  Forum - //vk.com/multimodule
 */

/**
 * Description of iforpayment
 *
 * @author koren
 */
class OptionData  {//IPayment
	
	/**
	 * Имя поля формы
	 * @var string
	 */
	public string $field_name = '';
	/**
	 * Название товара/статьи
	 * @var string
	 */
	public string $title	= '';
	/**
	 * Название опции/параметра товара
	 * @var string
	 */
	public string $label = '';
	
	/**
	 * Общее описание с названиями опций, параметра, товара, статьи
	 * В том числе для сохранения в статью/письмо названия значения
	 * @var string
	 */
	public string $description = ''; //$od->title . ' /' . $od->label;
	
	/**
	 * Свойство сохраняемое в статье, как общее описание
	 * Вставляетя в статью/письмо после таблицы значений.
	 * @var string
	 */
	public string $content = '';
	
	/**
	 * Сохранение в статье
	 * Article save Value
	 * @var mix
	 */
	public $value = '';
	
	/**
	 * Сумма для оплаты за еденицу
	 * @return float
	 */
	public float $cost = 0;
	
	/**
	 * Количество едениц товара
	 * @return float
	 */
	public float $count = 0;
	
	/**
	 * Арифметический знак изменения общей итоговой суммы
	 * +, -, *, 
	 * @var string
	 */
	public string $sign	= '+';
	
	public string $type	= '';
	
	
	/**
	 * Индекс поля(опции) в списке полей
	 * @var string
	 */
	public int $i = 0;
	
	
	/**
	 * ID заказа
	 * @var int
	 */
	public int $orderID = 0;
	
	/**
	 * ID товара
	 * @var int
	 */
	public int $articleID= 0;
	
	
	/**
	 * собственные данные опций.
	 * @var string
	 */
	public string $dataJSON	= '';
	
	
}
