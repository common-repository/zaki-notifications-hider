<?php
/**
 * Plugin Name: Zaki Notifications Hider
 * Plugin URI:  http://www.zaki.it
 * Description: Plugin that allow you to hide update notifications for each plugin installed
 * Version:     1.0
 * Author:      Zaki Design
 * Author URI:  http://www.zaki.it
 */
 
define('ZAKI_NOTIFYHIDER_FILE',__FILE__);

// Plugin Classes
require_once plugin_dir_path(ZAKI_NOTIFYHIDER_FILE).'classes/class-zaki-plugins.php';

// Disable Notification of selected plugins
add_filter('site_transient_update_plugins','ZakiNotifyHider_init');
function ZakiNotifyHider_init($value) {
    $settings = get_option('zaki_notifyhider_options');
    if(!$settings) return $value;
    $plugins_to_exclude = (array) $settings['excl_plugins'];
    if(empty($plugins_to_exclude)) return $value;
    foreach($plugins_to_exclude as $pte) :
    	if(!empty($value->response[$pte])) unset($value->response[$pte]);
    endforeach;
	return $value;
}

// Hooks & Init
add_action('admin_init', 'ZakiNotifyHider_SettingsInit');
add_action('admin_menu', 'ZakiNotifyHider_AddMenuPages');
register_activation_hook(ZAKI_NOTIFYHIDER_FILE, 'ZakiNotifyHider_Activation');
register_deactivation_hook( ZAKI_NOTIFYHIDER_FILE, 'ZakiNotifyHider_Deactivation');

// Attivazione e disattivazione plugin
function ZakiNotifyHider_Activation() {
    $settings = array(
        "excl_plugins" => array()
    );
    update_option('zaki_notifyhider_options', $settings);
}

function ZakiNotifyHider_Deactivation() {
    unregister_setting('zaki_notifyhider_options','zaki_notifyhider_options');
}

// Definizione variabile settaggi con relative callback
function ZakiNotifyHider_SettingsInit() {

    register_setting(
        'zaki_notifyhider_options',
        'zaki_notifyhider_options'
    );
    
    add_settings_section(
        'zaki_notifyhider_options_section_main',
        __('General Settings','zaki'),
        'ZakiNotifyHider_PageSetting_Section_Main_Callback',
        'zaki-notifications-hider'
    );
                
        add_settings_field(
            'zaki_notifyhider_op_excl_pages',
            __('Plugins to exclude','zaki'),
            'ZakiNotifyHider_PluginsSetting_Section_Main_ExclPages_Callback',
            'zaki-notifications-hider',
            'zaki_notifyhider_options_section_main'
        );
        
}

// Sezione generale
function ZakiNotifyHider_PageSetting_Section_Main_Callback() {
}
               
    // Plugins da escludere
    function ZakiNotifyHider_PluginsSetting_Section_Main_ExclPages_Callback() {
        $settings = get_option('zaki_notifyhider_options');
        $plugins_to_exclude = (array) $settings['excl_plugins'];
        $plugins = get_plugins();
        ?>
        <div class="exclbox">
            <?php
            if($plugins) : foreach($plugins as $kp => $p) :
                $checked = (in_array($kp,$plugins_to_exclude)) ? ' checked="checked"' : '';
                ?>
                <input name="zaki_notifyhider_options[excl_plugins][]" type="checkbox" value="<?=$kp?>" class="code" <?=$checked?> />&nbsp;<?=$p['Name']?>
                <br />
                <?php
            endforeach; endif;
            ?>
        </div>
        <?php
    }    

// Inizializzazione pagine menu
function ZakiNotifyHider_AddMenuPages() {

    //Controllo ed eventualmente includo il menu principale
    ZakiPlugins::checkMainMenu();
            
    // Pagine del plugins
    add_submenu_page(
        'zaki',
        __('Notifications Hider','zaki'),
        __('Notifications Hider','zaki'),
        'manage_options',
        'zaki-notifications-hider',
        'ZakiNotifyHider_PageSettingHtml'
    );
    
}

// HTML Pagina principale di settaggio (main)
function ZakiNotifyHider_PageSettingHtml() {
    $settings = get_option('zaki_notifyhider_options');
    ?>  
    <div class="wrap zaki_notifyhider_page zaki_notifyhider_page_main">
        <?php screen_icon('options-general'); ?><h2><?=__('Zaki Notifications Hider','zaki')?></h2>        
        <form method="post" action="options.php">
            <?php settings_fields('zaki_notifyhider_options'); ?>
            <?php do_settings_sections('zaki-notifications-hider'); ?>
            <p class="submit">
               <input name="submit" type="submit" id="submit" class="button-primary" value="<?=__('Save','zaki')?>" />
            </p>
        </form>
    </div>
    <?php
}

