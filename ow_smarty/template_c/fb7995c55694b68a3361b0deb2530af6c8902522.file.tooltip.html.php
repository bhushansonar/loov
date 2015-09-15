<?php /* Smarty version Smarty-3.1.12, created on 2015-09-14 10:06:50
         compiled from "E:\wamp\www\loov\ow_system_plugins\base\decorators\tooltip.html" */ ?>
<?php /*%%SmartyHeaderCode:1787355f6d47a22db19-69294511%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fb7995c55694b68a3361b0deb2530af6c8902522' => 
    array (
      0 => 'E:\\wamp\\www\\loov\\ow_system_plugins\\base\\decorators\\tooltip.html',
      1 => 1437323474,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1787355f6d47a22db19-69294511',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_55f6d47a26c326_72889667',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55f6d47a26c326_72889667')) {function content_55f6d47a26c326_72889667($_smarty_tpl) {?>
<div class="ow_tooltip <?php if (!empty($_smarty_tpl->tpl_vars['data']->value['addClass'])){?> <?php echo $_smarty_tpl->tpl_vars['data']->value['addClass'];?>
<?php }?>">
    <div class="ow_tooltip_tail">
        <span></span>
    </div>
    <div class="ow_tooltip_body">
        <?php echo $_smarty_tpl->tpl_vars['data']->value['content'];?>

    </div>
</div><?php }} ?>