<div style="text-align:center; font-size:14px; margin:1rem 0;">
    <a href="/privacy">隐私政策</a> |
    <a href="/statement">免责声明</a> |
    <a href="/sitemap.html">站点地图</a>
    <?php
    $startDate = new DateTime('2026-07-01');
    $now = new DateTime();
    $diff = $startDate->diff($now);
    echo " | 本站已稳定运行：{$diff->y}年{$diff->m}月{$diff->d}天";
    ?>
</div>

<footer id="footer" role="contentinfo">
    &copy; <?php echo date('Y'); ?> <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>.
    <?php _e('由 <a href="https://yaqi.eu.org">Cloudflare</a> 强力驱动'); ?>.
</footer><!-- end #footer -->

<?php $this->footer(); ?>
</body>
</html>
