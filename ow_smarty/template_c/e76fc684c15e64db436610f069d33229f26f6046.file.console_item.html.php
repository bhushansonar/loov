<?php /* Smarty version Smarty-3.1.12, created on 2015-09-14 10:06:50
         compiled from "E:\wamp\www\loov\ow_system_plugins\base\views\components\console_item.html" */ ?>
<?php /*%%SmartyHeaderCode:332055f6d47a120258-13636148%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e76fc684c15e64db436610f069d33229f26f6046' => 
    array (
      0 => 'E:\\wamp\\www\\loov\\ow_system_plugins\\base\\views\\components\\console_item.html',
      1 => 1437323480,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '332055f6d47a120258-13636148',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_55f6d47a221f97_64923375',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55f6d47a221f97_64923375')) {function content_55f6d47a221f97_64923375($_smarty_tpl) {?><?php if (!is_callable('smarty_block_block_decorator')) include 'E:\\wamp\\www\\loov\\ow_smarty\\plugin\\block.block_decorator.php';
?><div id="<?php echo $_smarty_tpl->tpl_vars['item']->value['uniqId'];?>
" class="ow_console_item <?php echo $_smarty_tpl->tpl_vars['item']->value['class'];?>
" <?php if ($_smarty_tpl->tpl_vars['item']->value['hidden']){?>style="display: none;"<?php }?>>
    <?php echo $_smarty_tpl->tpl_vars['item']->value['html'];?>

    <?php if (!empty($_smarty_tpl->tpl_vars['item']->value['content'])){?>
        <div id="<?php echo $_smarty_tpl->tpl_vars['item']->value['content']['uniqId'];?>
" class="OW_ConsoleItemContent" style="display: none;">

            <?php $_smarty_tpl->smarty->_tag_stack[] = array('block_decorator', array('name'=>"tooltip",'addClass'=>"console_tooltip ow_tooltip_top_right")); $_block_repeat=true; echo smarty_block_block_decorator(array('name'=>"tooltip",'addClass'=>"console_tooltip ow_tooltip_top_right"), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

                <?php echo $_smarty_tpl->tpl_vars['item']->value['content']['html'];?>

            <?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_block_block_decorator(array('name'=>"tooltip",'addClass'=>"console_tooltip ow_tooltip_top_right"), $_block_content, $_smarty_tpl, $_block_repeat);  } array_pop($_smarty_tpl->smarty->_tag_stack);?>


        </div>
    <?php }?>
</div><?php }} ?>