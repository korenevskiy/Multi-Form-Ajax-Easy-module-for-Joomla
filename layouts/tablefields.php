<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

//return;
defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

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
 * @var   array    $captionExpander;
 * @var   array    $captionExpanded;
 */

$html = array();
$attr = '';

// Initialize the field attributes.
$attr .= !empty($class) ? ' class="form-select ' . $class . '"' : ' class="form-select"';
$attr .= !empty($size) ? ' size="' . $size . '"' : '';
$attr .= $multiple ? ' multiple' : '';
$attr .= $required ? ' required' : '';
$attr .= $autofocus ? ' autofocus' : '';
$attr .= $onchange ? ' onchange="' . $onchange . '"' : '';
$attr .= !empty($description) ? ' aria-describedby="' . $name . '-desc"' : '';
$attr .= $dataAttribute;

// To avoid user's confusion, readonly="readonly" should imply disabled="disabled".
if ($readonly || $disabled)
{
	$attr .= ' disabled="disabled"';
}
//echo 'Good!!!!';
//return 'Good!!!!';
$data;

//toPrint($columns,'$columns',0,'pre');
//toPrint($data['columns'],'Fields',0,'pre');

echo "<div class='table-responsive'>";
echo "<table id='{$id}_field' class='tableFields -table-light table-responsive table table-bordered table-striped table-hover caption-top' xstyle='border: 1px solid gray; border-radius: 10px; min-width: 20px; min-height: 20px;'>";
        
$captionExpander;
$expanded = $captionExpanded ? 'open': '';

//toPrint($columns,'$columns',0,'pre');
$caption = ($captionExpander && $description)?"<details $expanded><summary>$caption</summary>$description</details>":"$caption";

echo "<caption style='caption-side: left;' align='left'>$caption</caption>";
echo "<thead class='-table-light '>";
echo "<tr>";

foreach ($columns as $col){
    $description = $col->description? "  data-original-title='$col->label' data-content='$col->description'  data-toggle='tooltip' data-placement='bottom'  data-html='true' ":"";
    $classTooltip = $col->description? 'hasPopover':'';
    $col->label = $col->translateLabel ? JText::_($col->label):$col->label;
    
    if(in_array($col->type, ['index'])){
        $col->classHeader = 'text-center d-flex align-items-center align-self-center justify-content-center align-items-center row row-conformity row-centered';  
    }
    if(in_array($col->type, ['new_del'])){
        $col->classHeader = ' green text-center d-flex align-items-center align-self-center justify-content-center align-items-center row row-conformity row-centered'; 
        $col->label = "<button type='button' class='btn btn-success  '  aria-label='".JText::_('JADD')."'  aria-hidden='true' title='".JText::_('JADD')."' "
                . " onclick=\"tblRowNew(this,'.tableFields') \" "
                . "'><i class='bi bi-plus -icon-save-new icon-plus-2 large-icon fa fa-lg fas fa-plus  fa-fw'></i></button>";  
    }
    
    echo "<th class='$classTooltip $col->class $col->labelclass $col->classHeader name_$col->name' scope='col' data-field='$name\[$col->name\]\[\]' data-name='$col->name' id='$name\[$col->name\]  "
        . " title='$col->text' $description><strong  >$col->label</strong></th>";
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
echo "<tbody xstyle='background-color:silver'>";

//if(empty($columns))
//    return;
//if(empty($fields))
//    return;

$columns;
$fields;
$data;
$count_rows = count($fields);

//toPrint($columns,'$columns',1,'pre');
$row_index = 0; 
foreach ($fields as $i => $row){
    echo "<tr class='table_row '>";
    foreach ($columns as $col){
//toPrint($col,'$col',0,'pre');
        if(in_array($col->type, ['index'])){
            echo "<th class='move $col->class $col->classCell name_$col->name' scope='row'>";
            echo " $i";//<span class=''></span>
            echo " <span class='hover -uneditable-input'>$col->default</span>";
            echo "</th>";
            continue;
        }
        if(in_array($col->type, ['new_del'])){
            echo "<th class='$col->class $col->classCell name_$col->name' scope='row'>";
            echo "<button href='#' type='button' class='btn btn-danger  '  aria-hidden='true' title='".JText::_('JDELETE')."' aria-label='".JText::_('JDELETE')."'"
                    . " onclick=\"tblRow(this,'.table_row','.tableFields').remove(); \" "
                    . "><i class='bi bi-x icon-delete large-icon fa fa-lg fas fa-times  fa-fw'></i></button>";
            echo "</th>";
            continue;
        }
        
        $html ="";
        if(isset($row[$col->name])){
            $html .= $row[$col->name]->html;
        }
        else{
            $html .= $col->html;
        } 
//<th scope="row">1</th>
//        if(empty($value[$col->name][$i]))
//            $val = '';
//        else
//            $val = $value[$col->name][$i];
        echo "<td class='$col->class $col->classCell name_$col->name' >";
        echo " $html";
        echo "</td>";
        
    }
    echo "</tr>"; 
};


echo "<template>"; 
echo "<tr class='table_row '>";
foreach ($columns as $col){
    
    if(in_array($col->type, ['index'])){
            echo "<th class='move $col->class $col->classCell name_$col->name' scope='row'>";
            echo " +";//<span class=''></span>
            echo " <span class='hover -uneditable-input'>$col->default</span>";
            echo "</th>";
            continue;
        }
        if(in_array($col->type, ['new_del'])){
            echo "<th class='$col->class $col->classCell name_$col->name' scope='row'>";
            echo "<button href='#' type='button' class='btn btn-danger  '  aria-hidden='true' title='".JText::_('JDELETE')."' aria-label='".JText::_('JDELETE')."'"
                    . " onclick=\"tblRow(this,'.table_row','.tableFields').remove(); \" "
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
        echo "<td class='$col->class $col->classCell name_$col->name' >";
        echo "$col->html";
        echo "</td>";
}
echo "</tr>";
echo "</template>";

//toPrint(reset($value),'name',0,'pre'); 


echo "</tbody>";
echo "</table>";
echo "</div>";


echo "<script type='text/javascript'>
document.addEventListener('DOMContentLoaded', () => { removeLBL('$id'); }); 
</script>";

static $script;

if($script)
    return;

$script = true; 

?>

<style>
  .name_new_del {
        width: 1%;
    }
    .tableFields .control-group{ 
        margin: 0;
    }
    .tableFields .control-group>.controls{
        margin:0;display: flex;
    }
    .tableFields .control-group>.controls>*{
        width: initial; flex: 1 auto;
    }
    .tableFields .name-index{
        text-align: center; 
    }
    .tableFields td{
        position:relative;
    }
    .tableFields th{
        position: relative; 
    }
    .tableFields th.move{
        transition: 1s;
        color: initial;
        text-align: center;
        cursor: move;
    }
    .tableFields th.move:hover{
        color: transparent;
    }    
    .tableFields th.move span{
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        pointer-events: none;
        cursor: move;
        color: initial;
        transition: 1s;
        opacity: 0;
    }
    .tableFields th.move:hover span{
        opacity: 1;
    }    
    
</style>


<script type='text/javascript'>
function removeLBL(id){
    document.getElementById(id+'_field').parentNode.style.marginLeft = '0px';
    document.getElementById(id+'_field').parentNode.parentNode.style.marginLeft = '0px';
    document.getElementById(id+'-lbl').parentNode.style.width = '100%';
}
function tblRowNew(el, selector){
  let tbl = tblRow(el,selector);
  let tmp = tbl.querySelector('template').content.cloneNode(true);
  tbl.querySelector('tbody').appendChild( tmp );
}        
function tblRow(el, selector, stopSelector) {
  if(!el || !el.parentElement) return null
  else if(stopSelector && el.parentElement.matches(stopSelector)) return null
  else if(el.parentElement.matches(selector)) return el.parentElement
  else return tblRow(el.parentElement, selector, stopSelector)
}
</script>

