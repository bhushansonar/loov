<?php /* Smarty version Smarty-3.1.12, created on 2015-09-14 10:06:50
         compiled from "E:\wamp\www\loov\ow_system_plugins\base\views\components\my_avatar_widget.html" */ ?>
<?php /*%%SmartyHeaderCode:302755f6d47a6b5ea4-22283996%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fc8ddf1aa154107f4b4162c636bc92cc69cf6b50' => 
    array (
      0 => 'E:\\wamp\\www\\loov\\ow_system_plugins\\base\\views\\components\\my_avatar_widget.html',
      1 => 1437323480,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '302755f6d47a6b5ea4-22283996',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'avatar' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_55f6d47a6e0e37_51654916',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55f6d47a6e0e37_51654916')) {function content_55f6d47a6e0e37_51654916($_smarty_tpl) {?><?php if (!is_callable('smarty_function_decorator')) include 'E:\\wamp\\www\\loov\\ow_smarty\\plugin\\function.decorator.php';
?>
<div class="ow_my_avatar_widget clearfix">
	<div class="ow_left ow_my_avatar_img"><?php echo smarty_function_decorator(array('name'=>'avatar_item','data'=>$_smarty_tpl->tpl_vars['avatar']->value),$_smarty_tpl);?>
</div>
    <div class="ow_my_avatar_cont">
    	<a href="<?php echo $_smarty_tpl->tpl_vars['avatar']->value['url'];?>
" class="ow_my_avatar_username"><span><?php echo $_smarty_tpl->tpl_vars['avatar']->value['title'];?>
</span></a>
    </div>
</div><?php }} ?>