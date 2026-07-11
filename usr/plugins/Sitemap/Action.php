<?php

namespace TypechoPlugin\Sitemap;

use Typecho\Widget;
use Widget\Options;
use Typecho\Db;

if (!defined('__TYPECHO_ROOT_DIR__')) {
    exit;
}

/**
 * Sitemap 生成处理类
 * 
 * 处理sitemap.xml请求，生成符合标准的XML站点地图
 */
class Action extends Widget
{
    /**
     * 缓存文件路径
     */
    const CACHE_FILE = __TYPECHO_ROOT_DIR__ . '/usr/plugins/Sitemap/cache/sitemap.xml';
    
    /**
     * 生成Sitemap
     */
    public function generate()
    {
        // 设置Content-Type为XML
        $this->response->setContentType('text/xml');
        
        // 获取插件配置
        $options = Options::alloc();
        $plugin = $options->plugin('Sitemap');
        
        // 检查是否启用缓存
        if ($plugin->enable_cache == '1') {
            $cacheTime = intval($plugin->cache_time) * 60; // 转换为秒
            
            // 检查缓存文件是否存在且未过期
            if (file_exists(self::CACHE_FILE)) {
                $fileTime = filemtime(self::CACHE_FILE);
                if (time() - $fileTime < $cacheTime) {
                    // 输出缓存内容
                    echo file_get_contents(self::CACHE_FILE);
                    return;
                }
            }
        }
        
        // 生成新的Sitemap内容
        $sitemap = $this->buildSitemap($plugin);
        
        // 如果启用缓存，保存到文件
        if ($plugin->enable_cache == '1') {
            $this->saveCache($sitemap);
        }
        
        // 输出Sitemap
        echo $sitemap;
    }
    
    /**
     * 构建Sitemap XML内容
     * 
     * @param object $plugin 插件配置对象
     * @return string XML内容
     */
    private function buildSitemap($plugin)
    {
        $options = Options::alloc();
        $db = Db::get();
        $prefix = $db->getPrefix();
        
        // 获取包含的内容类型
        $includeTypes = isset($plugin->include_types) ? $plugin->include_types : ['posts', 'pages', 'categories', 'tags'];
        if (!is_array($includeTypes)) {
            $includeTypes = ['posts', 'pages', 'categories', 'tags'];
        }
        
        // 获取优先级设置
        $homePriority = floatval($plugin->home_priority);
        $postPriority = floatval($plugin->post_priority);
        $pagePriority = floatval($plugin->page_priority);
        $termPriority = floatval($plugin->term_priority);
        
        // 获取更新频率
        $changefreq = $plugin->changefreq;
        
        // 构建XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // 添加首页
        $xml .= $this->buildUrl(
            $options->siteUrl,
            date('Y-m-d'),
            'daily',
            $homePriority
        );
        
        // 添加文章
        if (in_array('posts', $includeTypes)) {
            $posts = $this->getPosts();
            foreach ($posts as $post) {
                $xml .= $this->buildUrl(
                    $post['permalink'],
                    $post['modified'],
                    $changefreq,
                    $postPriority
                );
            }
        }
        
        // 添加独立页面
        if (in_array('pages', $includeTypes)) {
            $pages = $this->getPages();
            foreach ($pages as $page) {
                $xml .= $this->buildUrl(
                    $page['permalink'],
                    $page['modified'],
                    $changefreq,
                    $pagePriority
                );
            }
        }
        
        // 添加分类
        if (in_array('categories', $includeTypes)) {
            $categories = $this->getCategories();
            foreach ($categories as $category) {
                $xml .= $this->buildUrl(
                    $category['permalink'],
                    $category['modified'],
                    'weekly',
                    $termPriority
                );
            }
        }
        
        // 添加标签
        if (in_array('tags', $includeTypes)) {
            $tags = $this->getTags();
            foreach ($tags as $tag) {
                $xml .= $this->buildUrl(
                    $tag['permalink'],
                    $tag['modified'],
                    'weekly',
                    $termPriority
                );
            }
        }
        
        $xml .= '</urlset>';
        
        return $xml;
    }
    
    /**
     * 构建单个URL节点
     * 
     * @param string $loc URL地址
     * @param string $lastmod 最后修改时间
     * @param string $changefreq 更新频率
     * @param float $priority 优先级
     * @return string XML节点
     */
    private function buildUrl($loc, $lastmod, $changefreq, $priority)
    {
        $xml = "  <url>\n";
        $xml .= "    <loc>" . htmlspecialchars($loc, ENT_QUOTES, 'UTF-8') . "</loc>\n";
        $xml .= "    <lastmod>" . htmlspecialchars($lastmod, ENT_QUOTES, 'UTF-8') . "</lastmod>\n";
        $xml .= "    <changefreq>" . htmlspecialchars($changefreq, ENT_QUOTES, 'UTF-8') . "</changefreq>\n";
        $xml .= "    <priority>" . htmlspecialchars($priority, ENT_QUOTES, 'UTF-8') . "</priority>\n";
        $xml .= "  </url>\n";
        
        return $xml;
    }
    
    /**
     * 获取所有已发布的文章
     * 
     * @return array
     */
    private function getPosts()
    {
        $db = Db::get();
        $prefix = $db->getPrefix();
        
        // 构建查询 - 获取所有必要字段
        $query = $db->select(
            'cid',
            'slug',
            'created',
            'modified'
        )->from($prefix . 'contents')
         ->where('type = ?', 'post')
         ->where('status = ?', 'publish')
         ->where('created < ?', time())
         ->order('modified', \Typecho\Db::SORT_DESC);
        
        $result = $db->fetchAll($query);
        
        $posts = [];
        foreach ($result as $row) {
            // 使用 Router 系统生成正确的 permalink
            // 这样会自动适配后台的永久链接设置
            $permalink = \Typecho\Router::url('post', [
                'cid' => $row['cid'],
                'slug' => urlencode($row['slug']),
                'directory' => '',
                'category' => '',
                'year' => date('Y', $row['created']),
                'month' => date('m', $row['created']),
                'day' => date('d', $row['created'])
            ]);
            
            // 添加完整的 URL 前缀
            $options = Options::alloc();
            $permalink = \Typecho\Common::url($permalink, $options->index);
            
            // 格式化修改时间
            $modified = date('Y-m-d', $row['modified']);
            
            $posts[] = [
                'cid' => $row['cid'],
                'slug' => $row['slug'],
                'permalink' => $permalink,
                'created' => $row['created'],
                'modified' => $modified
            ];
        }
        
        return $posts;
    }
    
    /**
     * 获取所有独立页面
     * 
     * @return array
     */
    private function getPages()
    {
        $db = Db::get();
        $prefix = $db->getPrefix();
        
        // 构建查询 - 获取所有必要字段
        $query = $db->select(
            'cid',
            'slug',
            'created',
            'modified'
        )->from($prefix . 'contents')
         ->where('type = ?', 'page')
         ->where('status = ?', 'publish')
         ->where('created < ?', time())
         ->order('modified', \Typecho\Db::SORT_DESC);
        
        $result = $db->fetchAll($query);
        
        $pages = [];
        foreach ($result as $row) {
            // 使用 Router 系统生成正确的 permalink
            // 这样会自动适配后台的永久链接设置
            $permalink = \Typecho\Router::url('page', [
                'cid' => $row['cid'],
                'slug' => urlencode($row['slug']),
                'directory' => ''
            ]);
            
            // 添加完整的 URL 前缀
            $options = Options::alloc();
            $permalink = \Typecho\Common::url($permalink, $options->index);
            
            // 格式化修改时间
            $modified = date('Y-m-d', $row['modified']);
            
            $pages[] = [
                'cid' => $row['cid'],
                'slug' => $row['slug'],
                'permalink' => $permalink,
                'created' => $row['created'],
                'modified' => $modified
            ];
        }
        
        return $pages;
    }
    
    /**
     * 获取所有分类
     * 
     * @return array
     */
    private function getCategories()
    {
        $db = Db::get();
        $prefix = $db->getPrefix();
        $options = Options::alloc();
        
        // 构建查询
        $query = $db->select(
            'mid',
            'slug',
            'name'
        )->from($prefix . 'metas')
         ->where('type = ?', 'category')
         ->order('order', \Typecho\Db::SORT_ASC);
        
        $result = $db->fetchAll($query);
        
        $categories = [];
        foreach ($result as $row) {
            // 构建分类链接
            $permalink = rtrim($options->siteUrl, '/') . '/category/' . $row['slug'];
            
            $categories[] = [
                'mid' => $row['mid'],
                'slug' => $row['slug'],
                'name' => $row['name'],
                'permalink' => $permalink,
                'modified' => date('Y-m-d')
            ];
        }
        
        return $categories;
    }
    
    /**
     * 获取所有标签
     * 
     * @return array
     */
    private function getTags()
    {
        $db = Db::get();
        $prefix = $db->getPrefix();
        $options = Options::alloc();
        
        // 构建查询
        $query = $db->select(
            'mid',
            'slug',
            'name'
        )->from($prefix . 'metas')
         ->where('type = ?', 'tag')
         ->order('mid', \Typecho\Db::SORT_ASC);
        
        $result = $db->fetchAll($query);
        
        $tags = [];
        foreach ($result as $row) {
            // 构建标签链接
            $permalink = rtrim($options->siteUrl, '/') . '/tag/' . $row['slug'];
            
            $tags[] = [
                'mid' => $row['mid'],
                'slug' => $row['slug'],
                'name' => $row['name'],
                'permalink' => $permalink,
                'modified' => date('Y-m-d')
            ];
        }
        
        return $tags;
    }
    
    /**
     * 保存缓存
     * 
     * @param string $content Sitemap内容
     */
    private function saveCache($content)
    {
        // 确保缓存目录存在
        $cacheDir = dirname(self::CACHE_FILE);
        if (!is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }
        
        // 保存到文件
        file_put_contents(self::CACHE_FILE, $content, LOCK_EX);
    }
    
    /**
     * 清除缓存
     * 
     * 在文章发布或更新时调用
     */
    public static function clearCache()
    {
        if (file_exists(self::CACHE_FILE)) {
            @unlink(self::CACHE_FILE);
        }
    }
}
