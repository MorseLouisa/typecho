<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * Sitemap 站点地图
 *
 * @package Sitemap
 * @author Typecho
 * @version 1.0
 * @link https://typecho.org
 */
class Sitemap_Plugin implements Typecho_Plugin_Interface
{
    public static function activate()
    {
        Typecho_Router::set('sitemap', '/sitemap.xml', 'Sitemap_Action', 'render');
    }

    public static function deactivate()
    {
        Typecho_Router::delete('sitemap');
    }

    public static function config(Typecho_Widget_Helper_Form $form){}
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
}
