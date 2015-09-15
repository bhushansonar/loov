<?php /* Smarty version Smarty-3.1.12, created on 2015-09-14 10:06:50
         compiled from "E:\wamp\www\loov\ow_plugins\membership\views\components\promo_widget.html" */ ?>
<?php /*%%SmartyHeaderCode:78455f6d47a72b1c5-96784417%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3d00d9f707b3d514ec0a68508d6d93bc4f5560f9' => 
    array (
      0 => 'E:\\wamp\\www\\loov\\ow_plugins\\membership\\views\\components\\promo_widget.html',
      1 => 1437323540,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '78455f6d47a72b1c5-96784417',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'actions' => 0,
    'a' => 0,
    'showMore' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_55f6d47a7afee1_43563938',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55f6d47a7afee1_43563938')) {function content_55f6d47a7afee1_43563938($_smarty_tpl) {?><?php if (!is_callable('smarty_block_style')) include 'E:\\wamp\\www\\loov\\ow_smarty\\plugin\\block.style.php';
if (!is_callable('smarty_function_text')) include 'E:\\wamp\\www\\loov\\ow_smarty\\plugin\\function.text.php';
if (!is_callable('smarty_function_decorator')) include 'E:\\wamp\\www\\loov\\ow_smarty\\plugin\\function.decorator.php';
?><?php $_smarty_tpl->smarty->_tag_stack[] = array('style', array()); $_block_repeat=true; echo smarty_block_style(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>


.ow_mship_widget_block {
    line-height: 16px;
}
.ow_mship_widget_benefits ul {
    margin-bottom: 0px;
}
.ow_mship_widget_more {
    padding-left: 16px;
}

<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_style(array(), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>


<div class="ow_mship_widget_block">
    <?php if (isset($_smarty_tpl->tpl_vars['title']->value)){?>
    <div class="ow_mship_widget_current">
        <span class="ow_mship_widget_label"><?php echo smarty_function_text(array('key'=>'membership+your_membership'),$_smarty_tpl);?>
: </span>
        <span class="ow_mship_widget_value ow_outline"><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</span>
    </div>
    <?php }?>
    <div class="ow_mship_widget_txt ow_smallmargin"><?php echo smarty_function_text(array('key'=>'membership+consider_upgrading'),$_smarty_tpl);?>
</div>
    <div class="ow_mship_widget_benefits ow_smallmargin">
        <ul class="ow_regular">
            <?php  $_smarty_tpl->tpl_vars['a'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['a']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['actions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['a']->key => $_smarty_tpl->tpl_vars['a']->value){
$_smarty_tpl->tpl_vars['a']->_loop = true;
?>
            <li><?php echo $_smarty_tpl->tpl_vars['a']->value;?>
</li>
            <?php } ?>
        </ul>
        <?php if ($_smarty_tpl->tpl_vars['showMore']->value){?><div class="ow_mship_widget_more"><?php echo smarty_function_text(array('key'=>'membership+and_more'),$_smarty_tpl);?>
</div><?php }?>
    </div>
    <div class="ow_mship_widget_btn ow_center">
        <?php echo smarty_function_decorator(array('name'=>'button','class'=>'ow_ic_up_arrow','id'=>'btn-sidebar-upgrade','langLabel'=>'membership+upgrade'),$_smarty_tpl);?>

    </div>
</div><?php }} ?>