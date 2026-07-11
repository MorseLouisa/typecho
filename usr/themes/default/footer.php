<div style="text-align:center; font-size:14px; margin:0; padding:0;">
    <a href="/privacy">隐私政策</a> |
    <a href="/statement">免责声明</a> |
    <a href="/sitemap.xml">站点地图</a>
    <?php
    $startDate = new DateTime('2026-07-01');
    $now = new DateTime();
    $diff = $startDate->diff($now);
    echo " | 本站已稳定运行：{$diff->y}年{$diff->m}月{$diff->d}天";
    ?>
</div>

<footer id="footer" role="contentinfo" style="margin:0; padding:0;">
    &copy; <?php echo date('Y'); ?> <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>.
    <?php _e('由 Cloudflare 强力驱动'); ?>.
</footer><!-- end #footer -->

<?php $this->footer(); ?>
</body>
</html>
