<?php
class Sitemap_Action extends Typecho_Widget implements Widget_Interface_Do
{
    public function render()
    {
        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<?xml-stylesheet type="text/xsl" href="sitemap.xsl"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        $db = Typecho_Db::get();
        $options = Typecho_Widget::widget('Widget_Options');

        //首页
        echo '<url>';
        echo '<loc>' . $options->siteUrl . '</loc>';
        echo '<priority>1.0</priority>';
        echo '</url>';

        //文章
        $posts = $db->select()->from('table.contents')
            ->where('status = ?', 'publish')
            ->where('type = ?', 'post')
            ->order('created', Typecho_Db::SORT_DESC);
        foreach ($db->fetchAll($posts) as $post) {
            echo '<url>';
            echo '<loc>' . Typecho_Router::url($post['slug'], $post, $options->index) . '</loc>';
            echo '<lastmod>' . date('Y-m-d', $post['modified']) . '</lastmod>';
            echo '<priority>0.8</priority>';
            echo '</url>';
        }

        //独立页面
        $pages = $db->select()->from('table.contents')
            ->where('status = ?', 'Publish')
            ->where('type = ?', 'page');
        foreach ($db->fetchAll($pages) as $page) {
            echo '<url>';
            echo '<loc>' . Typecho_Router::url($page['slug'], $page, $options->index) . '</loc>';
            echo '<lastmod>' . date('Y-m-d', $page['modified']) . '</lastmod>';
            echo '<priority>0.7</priority>';
            echo '</url>';
        }

        echo '</urlset>';
        exit;
    }

    public function action()
    {
        $this->render();
    }
}
