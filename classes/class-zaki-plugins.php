<?php

if(!class_exists('ZakiPlugins')) {

    final class ZakiPlugins {

        // Check menu/submenu exists
        public static function checkMenuExist($main,$sub = false) {
            global $menu, $submenu;
            if(!$sub) :
                // Check if main menu exist
                foreach($menu as $m) :
                    if($m[2] == $main) return true;
                endforeach;
            else :
                // Check if submenu of main menu exist
                if(isset($submenu[$main])) :
                    foreach($submenu[$main] as $s) :
                        if($s[2] == $sub) return true;
                    endforeach;
                endif;
            endif;
            return false;
        }

        public static function checkMainMenu() {    
            if(!self::checkMenuExist('zaki')) :
                add_menu_page(
                    __('Zaki Plugins','zaki'),
                    __('Zaki Plugins','zaki'),
                    'manage_options',
                    'zaki',
                    array('ZakiPlugins','zakiPluginsMainPageHtml'),
                    plugins_url( 'images/zaki-icon.png', dirname( __FILE__ ) )
                );
            endif;
        }
    
        // HTML Pagina Zaki plugins
        public function zakiPluginsMainPageHtml() {
            ?>  
            <div class="wrap zaki_page zaki_page_credits">
                <?php screen_icon('options-general'); ?><h2><?=__('Zaki Plugins','zaki')?></h2>
                <p><img src="<?php echo plugins_url( 'images/zaki-logo.jpg', dirname( __FILE__ ) ) ?>" alt="Zaki Design" width="288" height="288" />
                <p>Developed by <a target="_blank" href="http://www.zaki.it">Zaki Design</a></p>
            </div>
            <?php
        }
    
        
    }
    
}