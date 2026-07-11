<?php

namespace TypechoPlugin\Sitemap;

use Typecho\Plugin\PluginInterface;
use Typecho\Widget\Helper\Form;
use Typecho\Widget\Helper\Form\Element\Text;
use Typecho\Widget\Helper\Form\Element\Radio;
use Typecho\Widget\Helper\Form\Element\Checkbox;
use Utils\Helper;

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

/**
 * Sitemap.xml 生成插件
 *
 * 自动生成符合标准的Sitemap.xml文件，包含文章、页面、分类和标签
 *
 * @package Sitemap
 * @author Sitemap Plugin
 * @version 1.0.0
 * @since 1.2.0
 * @link https://github.com
 */
class Plugin implements PluginInterface
{
    /**
     * 激活插件方法
     * 注册sitemap.xml路由
     */
    public static function activate()
    {
        // 添加sitemap.xml路由
        Helper::addRoute('sitemap_xml', '/sitemap.xml', 'TypechoPlugin\Sitemap\Action', 'generate');
        
        return '插件已激活，请访问 /sitemap.xml 查看生成的站点地图';
    }

    /**
     * 禁用插件方法
     * 删除sitemap.xml路由
     */
    public static function deactivate()
    {
        Helper::removeRoute('sitemap_xml');
    }

    /**
     * 获取插件配置面板
     *
     * @param Form $form 配置面板
     */
    public static function config(Form $form)
    {
        // 首页优先级
        $homePriority = new Text('home_priority', null, '1.0', _t('首页优先级'), _t('范围0.0-1.0，建议值1.0'));
        $homePriority->input->setAttribute('class', 'mini');
        $form->addInput($homePriority);
        
        // 文章优先级
        $postPriority = new Text('post_priority', null, '0.8', _t('文章优先级'), _t('范围0.0-1.0，建议值0.8'));
        $postPriority->input->setAttribute('class', 'mini');
        $form->addInput($postPriority);
        
        // 页面优先级
        $pagePriority = new Text('page_priority', null, '0.6', _t('页面优先级'), _t('范围0.0-1.0，建议值0.6'));
        $pagePriority->input->setAttribute('class', 'mini');
        $form->addInput($pagePriority);
        
        // 分类/标签优先级
        $termPriority = new Text('term_priority', null, '0.4', _t('分类/标签优先级'), _t('范围0.0-1.0，建议值0.4'));
        $termPriority->input->setAttribute('class', 'mini');
        $form->addInput($termPriority);
        
        // 更新频率
        $changefreq = new Radio('changefreq', 
            [
                'always' => _t('总是 - Always'),
                'hourly' => _t('每小时 - Hourly'),
                'daily' => _t('每天 - Daily'),
                'weekly' => _t('每周 - Weekly'),
                'monthly' => _t('每月 - Monthly'),
                'yearly' => _t('每年 - Yearly'),
                'never' => _t('从不 - Never')
            ],
            'weekly',
            _t('更新频率'),
            _t('指定内容的更新频率')
        );
        $form->addInput($changefreq);
        
        // 包含内容类型
        $includeTypes = new Checkbox('include_types',
            [
                'posts' => _t('文章'),
                'pages' => _t('独立页面'),
                'categories' => _t('分类'),
                'tags' => _t('标签')
            ],
            ['posts', 'pages', 'categories', 'tags'],
            _t('包含的内容类型'),
            _t('选择要在Sitemap中包含的内容类型')
        );
        $form->addInput($includeTypes);
        
        // 是否启用缓存
        $enableCache = new Radio('enable_cache',
            [
                '1' => _t('启用'),
                '0' => _t('禁用')
            ],
            '1',
            _t('启用缓存'),
            _t('启用缓存可以提升性能，但可能不是实时更新')
        );
        $form->addInput($enableCache);
        
        // 缓存时间（分钟）
        $cacheTime = new Text('cache_time', null, '60', _t('缓存时间（分钟）'), _t('缓存有效时间，建议60分钟'));
        $cacheTime->input->setAttribute('class', 'mini');
        $form->addInput($cacheTime);
    }

    /**
     * 个人用户的配置面板
     *
     * @param Form $form
     */
    public static function personalConfig(Form $form)
    {
        // 无需个人配置
    }
}
