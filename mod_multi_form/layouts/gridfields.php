<?php
/**
 * @package     Multi.Form
 * @subpackage  Layout
 *
 * @copyright   2021 Korenevskiy Sergei Borisovich
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

//return;
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text as JText;
use Joomla\CMS\HTML\HTMLHelper as JHtml;
use Joomla\CMS\Factory as JFactory;
use Joomla\Input\Input as JInput;
use Joomla\CMS\Uri\Uri as JUri;

extract($displayData);
/**
 * Layout variables
 * -----------------
 * @var   string   $autocomplete    Autocomplete attribute for the field.
 * @var   boolean  $autofocus       Is autofocus enabled?
 * @var   string   $class           Classes for the input.
 * @var   string   $description     Description of the field.
 * @var   boolean  $disabled        Is this field disabled?
 * @var   string   $group           Group the field belongs to. <fields> section in form XML.
 * @var   boolean  $hidden          Is this field hidden in the form?
 * @var   string   $hint            Placeholder for the field.
 * @var   string   $id              +DOM id of the field.
 * @var   string   $label           Label of the field.
 * @var   string   $labelclass      Classes to apply to the label.
 * @var   boolean  $multiple        Does this field support multiple values?
 * @var   string   $name            +Name of the input field.
 * @var   string   $onchange        Onchange attribute for the field.
 * @var   string   $onclick         Onclick attribute for the field.
 * @var   string   $pattern         Pattern (Reg Ex) of value of the form field.
 * @var   boolean  $readonly        Is this field read only?
 * @var   boolean  $repeat          Allows extensions to duplicate elements.
 * @var   boolean  $required        Is this field required?
 * @var   integer  $size            Size attribute of the input.
 * @var   boolean  $spellcheck      Spellcheck state for the form field.
 * @var   string   $validate        Validation rules to apply.
 * @var   string   $value           Value attribute of the field.
 * @var   array    $checkedOptions  Options that will be set as checked.
 * @var   boolean  $hasValue        Has this field a value assigned?
 * @var   array    $options         Options available for this field.
 * @var   array    $inputType       Options available for this field.
 * @var   string   $dataAttribute   Miscellaneous data attributes preprocessed for HTML output
 * @var   array    $dataAttributes  Miscellaneous data attribute for eg, data-*
 * 
 * @var   bool    $captionExpander;
 * @var   bool    $captionExpanded;
 * 
 * @var   bool     $___; 
 * @var   bool     $___;
 */
JHtml::_('jquery.framework');
//HTMLHelper::_('draggablelist.draggable');
// /media/vendor/jquery-ui/js/jquery.ui.sortable.js     J4
//    JHtml::script(JUri::root(). '/media/legacy/js/sortablelist.js');
// /media/legacy/js/sortablelist.js                     J4
// /media/jui/js/jquery.ui.sortable.js                  J3
if($isJ4){
//    JHtml::script('media/vendor/jquery/js/jquery.js');
    JHtml::script('media/vendor/jquery-migrate/js/jquery-migrate.js');
    JHtml::script('media/vendor/jquery-ui/js/jquery.ui.core.js');
    JHtml::script('media/vendor/jquery-ui/js/jquery.ui.sortable.js');
}
else{
    JHtml::script('media/jui/js/jquery.ui.core.js'); 
    JHtml::script('media/jui/js/jquery.ui.sortable.js');
    
//    JHtml::_('jquery.ui', array('core'));
//    JHtml::_('jquery.ui', array('core', 'sortable'));
}
//JHtml::_('jquery.framework');
//JHtml::_('script', 'system/html5fallback.js', array('version' => 'auto', 'relative' => true, 'conditional' => 'lt IE 9'));
//JHtml::_('script', 'jui/cms.js', array('version' => 'auto', 'relative' => true));

$html = array();
$attr = '';

// Initialize the field attributes.
//$size = !empty($size) ? ' size="' . $size . '"' : '';   
$attr .= $multiple ? ' multiple' : '';
$attr .= $required ? ' required' : '';
$attr .= $autofocus ? ' autofocus' : '';
$attr .= $onchange ? ' onchange="' . $onchange . '"' : '';
$attr .= $dataAttribute;

$class = !empty($class) ? ' class=" ' . $class . '"' : ' ';//form-select
$attr_desc = !empty($description) ? ' aria-describedby="' . $name . '-desc"' : '';
$style = $style ? "style='$style'" : '';

// To avoid user's confusion, readonly="readonly" should imply disabled="disabled".
if ($readonly || $disabled)
{
	$attr .= ' disabled="disabled"';
}
//echo 'Good!!!!';
//return 'Good!!!!';
$data;
$count_rows = count($fields);
//toPrint($columns,'$columns',0,'pre');
//toPrint($data['columns'],'Fields',0,'pre');

echo "<div class='table-responsive' $class $attr_desc $style $style>";
echo "<table id='{$id}_field' data-name='$name' "
. " class='gridFields table -table-light table-responsive table-bordered table-striped table-hover table-sm  caption-top' xstyle='border: 1px solid gray; border-radius: 10px; min-width: 20px; min-height: 20px;'>";
        
$captionExpander;
$expanded = $captionExpanded ? 'open': '';

//toPrint($columns,'$columns',0,'pre');
$caption = ($captionExpander && $description)?"<details $expanded><summary>$caption</summary>$description</details>":"$caption";

echo "<caption style='caption-side: left;' align='left' title='$description'>$caption</caption>";
echo "<thead class='-table-light '>";
echo "<tr>";

foreach ($columns as $col){
    $description = $col->description? "  data-original-title='$col->label' data-content='$col->description'  data-toggle='tooltip' data-placement='bottom'  data-html='true' ":"";
    $classTooltip = $col->description? 'hasPopover':'';
    $col->label = $col->translateLabel ? JText::_($col->label):$col->label;
    
    if(in_array($col->type, ['index'])){
        $col->classHeader .= 'text-center  w-1 d-none d-md-table-cell   -d-flex align-items-center align-self-center justify-content-center align-items-center -row row-conformity row-centered';  
    }
    if(in_array($col->type, ['new_del'])){
        $col->classHeader .= ' green text-center    w-10 d-none d-md-table-cell   -d-flex align-items-center align-self-center justify-content-center align-items-center -row row-conformity row-centered'; 
        $col->label = "<button type='button' class='btn btn-success  $col->class '  aria-label='".JText::_('JADD')."'  aria-hidden='true' title='".JText::_('JADD')."' "
                . " onclick=\"tblRowNew(this,'.gridFields') \" "
                . "><i class='bi bi-plus -icon-save-new {$fontIcon}plus-2 large-icon {$fontIcon} {$fontIcon}-lg fas {$fontIcon}-plus  {$fontIcon}-fw'></i></button>";  
    }
    $style = $col->style || $col->styleHeader? " style='$col->style; $col->styleHeader' " : '';
    
    echo "<th class='head _$col->type $col->labelclass  $col->classHeader name_$col->fieldname $classTooltip' scope='col' "
            . " data-field='{$name}[{$col->name}][]' data-name='$col->name' id='{$name}[{$col->name}]'  "
        . " title='$col->text' $description $style><strong  >$col->label</strong></th>";
}


//jform[params][tbl][nameforpost][]
echo "</tr>";
echo "</thead>";


/*
echo "<tfoot align='center' style=''>";
echo " <tr>";
echo "  <td>Ячейка 1, расположенная в TFOOT</td>";
echo "  <td>Ячейка 2, расположенная в TFOOT</td> ";
echo " </tr> ";
echo "</tfoot>";
*/






//toPrint($value,'$value',0,'pre');
//toPrint($fields,'$fields',0,'pre');
//toPrint($displayData,'$columns',0,'pre');
//$this->value;
echo "<tbody xstyle='background-color:silver'  class='js-draggable' >";

//if(empty($columns))
//    return;
//if(empty($fields))
//    return;

$columns;
$fields;
$data; 

//toPrint($columns,'$columns',1,'pre');
foreach ($fields as $i => $row){
    echo "<tr class='table_row '>";
//toPrint(array_keys($row),'$columns Keys',0,'pre');
//if($col->type =="Radio")
//toPrint($row[$col->fieldname]->value,'$fields',0,'pre');
    foreach ($columns as $col){
//toPrint($col->name,'$column Name',0,'pre');
        
        $style = $col->style || $col->styleCell? " style='$col->style; $col->styleCell' " : '';
//toPrint($col,'$col',0,'pre');
        if(in_array($col->type, ['index'])){
            
            echo "<th class='move text-center $col->classCell name_$col->name ' scope='row' $style>";
            
										$iconClass = '';
//										if (!$canChange)
//										{
//											$iconClass = ' inactive';
//										}
//										elseif (!$saveOrder)
//										{
//											$iconClass = ' inactive" title="' . Text::_('JORDERINGDISABLED');
//										}
										?>
										
                                        <?php
            echo " <span class='$col->class '>$i</span>";
            echo "<i class='$col->class {$fontIcon}move  -fa-arrows {$fontIcon} {$fontIcon}-sort' aria-hidden='true'></i>";
//             echo "<span class='sortable-handler$iconClass'><span class='icon-ellipsis-v'></span></span>";//j4
            echo "</th>";
            continue;
        }
        if(in_array($col->type, ['new_del'])){
            echo "<th class='cell $col->classCell name_$col->name' scope='row' $style>";
            echo "<button href='#' type='button' class='btn btn-danger $col->class '  aria-hidden='true' title='".JText::_('JGLOBAL_FIELD_REMOVE')."' aria-label='".JText::_('JGLOBAL_FIELD_REMOVE')."'"
                    . " onclick=\"tblRow(this,'.table_row','.gridFields').remove(); upInd('gridFields'); \" "
                    . "><i class='bi bi-x large-icon {$fontIcon} {$fontIcon}-lg fas {$fontIcon}-times  {$fontIcon}-fw {$fontIcon}delete ' aria-hidden='true'></i></button>";
            echo "</th>";
            continue; 
        } 
//if($col->type =="Radio")
//toPrint($row[$col->fieldname]->value,'$fields',0,'pre');
//toPrint($col->fieldname,'$fields',0,'pre');
        $html ="";
        if(isset($row[$col->fieldname])){
            $html .= $row[$col->fieldname]->html;
        }
        else{
            $html .= $col->html;
        } 
//<th scope="row">1</th>
//        if(empty($value[$col->name][$i]))
//            $val = '';
//        else
//            $val = $value[$col->name][$i];
        $attribute = $col->type =="Radio" ?  " data-type='$col->type'  data-fieldname='$col->fieldname'":'' ;
        
        echo "<td class='$col->classCell name_$col->fieldname' $style $attribute>";
        echo " $html"; // {$row[$col->fieldname]->value}
        echo "</td>";
        
    }
    echo "</tr>"; 
}

//toPrint($_SERVER,'Input',0,'pre');

$count_rows = count($fields);  
echo "<template shadowroot='open'>   "; // closed, open data-i='$count_rows'
//echo "<tr>";
//echo "<style> :host{display:block;height: 30px;} </style>";
//echo "<td colspan='8'><h1>Template!!!!</h1></td>";
//echo "</tr>";
echo "</template>";

//$columns = [];
if(true):
echo "<tr class='table_row hide template'  data-i='$count_rows'  data-type='$col->type'>";// 
foreach ($columns as $col){
    
    if(in_array($col->type, ['index'])){
            echo "<th class='move cell  $col->classCell name_$col->name' scope='row'>";
            echo " <span class='$col->class'> +</span>";//<span class=''></span>
//            echo " <span class='hover -uneditable-input'>$col->default</span>";
            echo "</th>";
            continue;
        }
        if(in_array($col->type, ['new_del'])){
            echo "<th class=' cell $col->classCell name_$col->name' scope='row'>";
            echo "<button href='#' type='button' class='btn btn-danger $col->class '  aria-hidden='true' title='".JText::_('JGLOBAL_FIELD_REMOVE')."' aria-label='".JText::_('JGLOBAL_FIELD_REMOVE')."'"
                    . " onclick=\"tblRow(this,'.table_row','.gridFields').remove(); upInd('gridFields'); \" "
                    . "><i class='bi bi-x icon-delete large-icon fa fa-lg fas fa-times  fa-fw'></i></button>";
            echo "</th>";
            continue;
        }
        
//        $html ="";
//        if(isset($row[$col->name])){
//            $html .= $row[$col->name]->html;
//        }
//        else{
//            $html .= $col->html;
//        } 
//<th scope="row">1</th>
//        if(empty($value[$col->name][$i]))
//            $val = '';
//        else
//            $val = $value[$col->name][$i];
        
        $attribute = $col->type=="Radio" ?  " data-type='$col->type' data-fieldname='$col->fieldname'":'';
        
        echo "<td class='cell $col->classCell name_$col->fieldname' $attribute>";
        echo "$col->html";
        echo "</td>";
}
echo "</tr>"; 
endif;
/*
 * jform[params][list_fields_][art_id][]
 * 
 * jform[params][list_fields_][onoff][]
 * jform[params][list_fields_][onoff][]
 * jform[params][list_fields_][onoff][]
 * 
 */


//toPrint(reset($value),'name',0,'pre'); 


echo "</tbody>";
echo "</table>";
echo "</div>";

echo "<script type='text/javascript'>
document.addEventListener('DOMContentLoaded', () => { removeLBL('$id','$isJ4'); });
//window.addEventListener('onload', () => { initGridFields(); });
</script>";

if($script)
    echo "<script type='text/javascript'>$script</style>";
if($css)
    echo "<style type='text/css'>$css</style>";

static $script;

if($script)
    return;

$script = true; 

?>
<script type='text/javascript'>
"use strict"

//Обновление индексдов для таблиц с классом
function upInd(table){
	
		let tables = document.getElementsByClassName(table);
		for(let tbl of tables){
			updateIndexis(tbl);
			console.log(tbl);
		}
			
}

function updateIndexis(table){
    //jform[params][list_fields_][onoff][]
        let nameTable = table.dataset.name;
        if(nameTable == '')
            return;
        let trs = table.querySelectorAll('tbody > tr');
        if(trs.length === 0)
            return;
        
        for(const [i,tr] of trs.entries()){ 
//console.log('TR:',i,tr);
            let RadiosTD = tr.querySelectorAll('td[data-type=Radio][data-fieldname]'); //'td[data-type=Radio][data-fieldname]'
//console.log('RadioTD:',RadiosTD);
//continue;
            if(RadiosTD.length == 0)
                continue;
            for(let td of RadiosTD){
                let name = td.dataset.fieldname;
                let fullname = nameTable + '[' + name + ']' + '[' + i + ']';
//                console.log(fullname);
                td.querySelectorAll('input[type=radio]').forEach((inpt) => inpt.name = fullname);
//                td.querySelector('fieldset');
            }
        }
}
function initGridFields(){

    let tbls = document.querySelectorAll('.gridFields.table');     
    for(let tbl of tbls){
        let tmplTR = tbl.querySelector('.template'); 
        tmplTR.classList.remove('hide','template');
        tbl.querySelector('template').content.append(tmplTR);
    }
//Joomla.submitbutton('module.apply');
//Joomla.submitbutton('module.save');
//Joomla.submitbutton('module.save2new');
//Joomla.submitbutton('module.save2copy');
//Joomla.submitbutton('module.cancel');
//Joomla.popupWindow('https:\/\/help.joomla.org\/proxy?keyref=Help39:Extensions_Module_Manager_Edit&lang=en', 'Help', 700, 500, 1)// HELP

//jform[params][list_fields_]

    
// var fixHelper = function(e, ui) {
//	ui.children().each(function() {
//		$(this).width($(this).width());
//	});
//	return ui;
//};  //    class="icon-move fa fa-arrows" aria-hidden="true"


jQuery( ".gridFields" ).sortable({
//    helper: fixHelper,
	handle: 'th.move.index', //Элемент захватываемый мышкой
    items: "tbody > tr",// Назначает дочерние элементы для сортировки
    update: function( event, ui ){ //this-table,  
        updateIndexis(this);
//        console.log(this,event,ui);
    },
    //https://api.jqueryui.com/sortable/#events
//    activate: function( event, ui ) {}, //This event is triggered when using connected lists, every connected list on drag start receives it. 
//    helper: function( element ) {}, // This event is triggered when using connected lists, every connected list on drag start receives it.
//    create: function( event, ui ), //Triggered when the sortable is created.
//    deactivate: function( event, ui ), //This event is triggered when sorting was stopped, is propagated to all possible connected lists.
//    out: function( event, ui ), //This event is triggered when a sortable item is moved away from a sortable list. Note: This event is also triggered when a sortable item is dropped.
//    over: function( event, ui ), //This event is triggered when a sortable item is moved into a sortable list.
//    receive: function( event, ui ), //This event is triggered when an item from a connected sortable list has been dropped into another list. The latter is the event target.
//    remove: function( event, ui ), //This event is triggered when a sortable item from the list has been dropped into another. The former is the event target.
//    sort: function( event, ui ), //Событие срабатывает в течении сортировки
//    start: function( event, ui ), //Это событие запускается при запуске сортировки.
//    stop: function( event, ui ), //Это событие запускается, когда сортировка остановлена.
//    
//    update: function( event, ui ), //! Это событие запускается, когда пользователь прекратил сортировку и положение DOM изменилось.   //This event is triggered when the user stopped sorting and the DOM position has changed.
//    change: function( event, ui ), //Это событие запускается во время сортировки, но только при изменении позиции DOM.    // This event is triggered during sorting, but only when the DOM position has changed.    
//    beforeStop: function( event, ui ), //Это событие запускается, когда сортировка останавливается, но когда placeholder/helper все еще доступен.     // This event is triggered when sorting stops, but when the placeholder/helper is still available.
});
jQuery().button('toggle');
}
function removeLBL(id,on){
//    console.clear();
//    console.log(on,'on');
    if(on === false)
        return;
    document.getElementById(id+'_field').parentNode.style.marginLeft = '0px';
    document.getElementById(id+'_field').parentNode.parentNode.style.marginLeft = '0px';
    document.getElementById(id+'-lbl').parentNode.style.width = '100%';
}
function tblRowNew(el, selector){
  let tbl = tblRow(el,selector);
//  let tmp = tbl.querySelector('.template.hide');
  let tmp = tbl.querySelector('template').content.querySelector('.table_row');
  let new_tmp = tmp.cloneNode(true);
  let i = parseInt(tmp.dataset.i);
  tmp.dataset.i = 1 + i;
//  console.log(Object.entries(tmp),tmp.content,tmp,tmp.dataset);
  tbl.querySelector('tbody').appendChild( new_tmp );
  delete new_tmp.dataset.i;
//  new_tmp.dataset.i = 1 + i;
//  tmp.classList.remove('hide','template');
//  let inputs = new_tmp.querySelectorAll('input, textarea, select, button');
//  for(let inpt of inputs){
//      inpt.id += i;
//  console.log(inpt.id);
//  }
  new_tmp.querySelectorAll('input, textarea, select, button').forEach((inpt) => inpt.id += i);
  new_tmp.querySelectorAll('label').forEach((lbl) => lbl.setAttribute('for',lbl.getAttribute('for')+i));
//  let tmp_new = tmp_old.querySelector('.template.hide');//content.
//  let labels = document.querySelectorAll('.gridFields .btn-group label');
//  for(let label of labels){
//      label.classList.add("btn");
//  }
  updateIndexis(tbl);
}        
function tblRow(el, selector, stopSelector) {
  if(!el || !el.parentElement) return null
  else if(stopSelector && el.parentElement.matches(stopSelector)) return null
  else if(el.parentElement.matches(selector)) return el.parentElement
  else return tblRow(el.parentElement, selector, stopSelector)
}
//window.onload = () => console.clear();
window.onload = function(){initGridFields();};
</script>

<style type="text/css">
    .gridFields{
        /*position: relative;*/
        /*top: 0;*/
    }
    .gridFields thead{
        /*position: sticky;*/
        /*top: 0;*/
    }
    .gridFields thead th{
        /*position: sticky;*/
        /*top: 0;*/
    }
    .gridFields th.name_new_del {
        width: 1%;
    }
    .gridFields th.new_del button{ 
        width: 100%; 
		font-size: 1rem;
    }
    .gridFields .control-group{ 
        margin: 0;
		width: initial;
		min-width: inherit;
    }
    .gridFields .control-group>.controls{
        margin:0; 
		display: flex;
		min-width: initial;
    }
    .gridFields .control-group>.controls>*,
    .gridFields .control-group>.controls>.form-control{
        /*width: auto;*/
		width: 100%;
		flex: 1 auto;
    }
    .gridFields .name-index{
        text-align: center; 
    }
    .gridFields td{
        position:relative;
    }
    .gridFields th{
        position: relative; 
    }
    .gridFields th.move{
        /*transition: 0.5s 0.1s;*/
        /*color: initial;*/
        text-align: center;
        cursor: move;
        position: relative;
    }
    .gridFields th.move i{ 
        position: absolute;
		opacity: 0.3;
    }
    .gridFields th i{ /*.move*/
        /*content: "::";*/
        display: block;
        /*height: 20px;*/
        /*position: absolute;*/
        /*bottom: 2px;*/    
        left: 0;
        right: 0;
        width: auto;
        margin: auto;
    }
    .gridFields th.move:hover{
        background-color: #8888;
    }
	.gridFields .control-group .controls {
		/*position: relative;*/
		/*flex: 1;*/
		min-width: 50px; /*210px*/
	}
	.gridFields  .btn-group{
		padding-top: 0;
		width: inherit;
	} 
	.gridFields  details{
		margin: auto;
	} 
	
</style>
