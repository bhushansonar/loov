<?php /* Smarty version Smarty-3.1.12, created on 2015-09-14 10:06:48
         compiled from "E:\wamp\www\loov\ow_system_plugins\base\views\components\avatar_user_list.html" */ ?>
<?php /*%%SmartyHeaderCode:524355f6d478b41997-97863990%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1e15f1b8961c43aec06a15af6558bb7a79fd047a' => 
    array (
      0 => 'E:\\wamp\\www\\loov\\ow_system_plugins\\base\\views\\components\\avatar_user_list.html',
      1 => 1437323480,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '524355f6d478b41997-97863990',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'css_class' => 0,
    'users' => 0,
    'user' => 0,
    'view_more_array' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_55f6d478c148c3_78305469',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55f6d478c148c3_78305469')) {function content_55f6d478c148c3_78305469($_smarty_tpl) {?><?php if (!is_callable('smarty_function_text')) include 'E:\\wamp\\www\\loov\\ow_smarty\\plugin\\function.text.php';
if (!is_callable('smarty_function_decorator')) include 'E:\\wamp\\www\\loov\\ow_smarty\\plugin\\function.decorator.php';
?><div class="ow_lp_avatars<?php if (!empty($_smarty_tpl->tpl_vars['css_class']->value)){?> <?php echo $_smarty_tpl->tpl_vars['css_class']->value;?>
<?php }?>">
    <?php if (empty($_smarty_tpl->tpl_vars['users']->value)){?>
        <div class="ow_nocontent"><?php echo smarty_function_text(array('key'=>'base+empty_user_avatar_list'),$_smarty_tpl);?>
</div>
    <?php }else{ ?>
        <?php  $_smarty_tpl->tpl_vars['user'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['user']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['users']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['user']->key => $_smarty_tpl->tpl_vars['user']->value){
$_smarty_tpl->tpl_vars['user']->_loop = true;
?><?php echo smarty_function_decorator(array('name'=>'avatar_item','data'=>$_smarty_tpl->tpl_vars['user']->value),$_smarty_tpl);?>
<?php } ?><?php if (!empty($_smarty_tpl->tpl_vars['view_more_array']->value)){?><a href="<?php echo $_smarty_tpl->tpl_vars['view_more_array']->value['url'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['view_more_array']->value['title'];?>
" class="avatar_list_more_icon"></a><?php }?>
    <?php }?>
    
</div><?php }} ?>