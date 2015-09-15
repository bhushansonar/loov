<?php /* Smarty version Smarty-3.1.12, created on 2015-09-14 10:06:49
         compiled from "E:\wamp\www\loov\ow_themes\morning\master_pages\general.html" */ ?>
<?php /*%%SmartyHeaderCode:918055f6d4799c2fd5-51105196%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9fef68d838eb298b48eaba3158684961e6eec826' => 
    array (
      0 => 'E:\\wamp\\www\\loov\\ow_themes\\morning\\master_pages\\general.html',
      1 => 1439199655,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '918055f6d4799c2fd5-51105196',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'siteUrl' => 0,
    'main_menu' => 0,
    'heading_icon_class' => 0,
    'heading' => 0,
    'content' => 0,
    'bottom_menu' => 0,
    'bottomPoweredByLink' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.12',
  'unifunc' => 'content_55f6d479a382e7_02025997',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55f6d479a382e7_02025997')) {function content_55f6d479a382e7_02025997($_smarty_tpl) {?><?php if (!is_callable('smarty_function_component')) include 'E:\\wamp\\www\\loov\\ow_smarty\\plugin\\function.component.php';
if (!is_callable('smarty_function_add_content')) include 'E:\\wamp\\www\\loov\\ow_smarty\\plugin\\function.add_content.php';
if (!is_callable('smarty_function_text')) include 'E:\\wamp\\www\\loov\\ow_smarty\\plugin\\function.text.php';
if (!is_callable('smarty_function_decorator')) include 'E:\\wamp\\www\\loov\\ow_smarty\\plugin\\function.decorator.php';
?><div class="ow_page_wrap">
    <div class="ow_page_padding">
        <div class="ow_header_wrap">
            <div class="ow_header">
                <div class="ow_site_panel">
                    <?php echo smarty_function_component(array('class'=>'BASE_CMP_Console'),$_smarty_tpl);?>

                </div>
                
                <div class="ow_header_pic_wrap">
                    <div class="ow_header_pic"><a href="<?php echo $_smarty_tpl->tpl_vars['siteUrl']->value;?>
" class="logo_url"></a></div>
                </div>
                <div class="ow_menu_wrap custom_menu"><?php echo $_smarty_tpl->tpl_vars['main_menu']->value;?>
</div>
            </div>
        </div>
        <div class="ow_page_container">
            <div class="ow_canvas">
                <div class="ow_page custom_margin ow_bg_color clearfix">
                    <h1 class="ow_stdmargin <?php echo $_smarty_tpl->tpl_vars['heading_icon_class']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['heading']->value;?>
</h1>
                    <div class="ow_content">
                        <?php echo smarty_function_add_content(array('key'=>'base.add_page_top_content'),$_smarty_tpl);?>

                        <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

                        <?php echo smarty_function_add_content(array('key'=>'base.add_page_bottom_content'),$_smarty_tpl);?>

                    </div>
                    <div class="ow_sidebar">
                        <?php echo smarty_function_component(array('class'=>"BASE_CMP_Sidebar"),$_smarty_tpl);?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ow_footer">
        <div class="ow_canvas">
            <div class="ow_page clearfix footer_width">
                <?php echo $_smarty_tpl->tpl_vars['bottom_menu']->value;?>

                <div class="copyright_symbol">
                    <div class="ow_copyright copyright_width">
                        <?php echo smarty_function_text(array('key'=>'base+copyright'),$_smarty_tpl);?>

                    </div>
                    <!--                    <div style="float:right;">
                                            <?php echo $_smarty_tpl->tpl_vars['bottomPoweredByLink']->value;?>

                                        </div>-->
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo smarty_function_decorator(array('name'=>'floatbox'),$_smarty_tpl);?>
<?php }} ?>