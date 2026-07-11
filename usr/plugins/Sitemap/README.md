# Sitemap.xml 插件

## 简介

Sitemap.xml插件是为Typecho博客系统开发的站点地图生成插件。它可以自动生成符合Sitemap协议标准的XML文件，帮助搜索引擎更好地索引您的网站内容。

## 功能特点

- ✅ 自动生成符合Sitemap协议标准的XML文件
- ✅ 支持包含文章、独立页面、分类和标签
- ✅ 可配置的优先级设置（首页、文章、页面、分类/标签）
- ✅ 可配置的更新频率（always/hourly/daily/weekly/monthly/yearly/never）
- ✅ 内置缓存机制，提升性能
- ✅ 自动获取最后修改时间
- ✅ 符合搜索引擎标准（Google、百度等）

## 安装方法

1. 将`Sitemap`文件夹上传到`usr/plugins/`目录
2. 登录Typecho后台，进入"控制台" → "插件"
3. 找到"Sitemap"插件，点击"启用"
4. 配置插件参数（可选）

## 使用方法

安装并启用插件后，直接访问以下URL即可查看生成的Sitemap：

```
https://你的域名/sitemap.xml
```

## 配置说明

### 优先级设置
- **首页优先级**：首页的权重，范围0.0-1.0，建议值1.0
- **文章优先级**：文章页面的权重，范围0.0-1.0，建议值0.8
- **页面优先级**：独立页面的权重，范围0.0-1.0，建议值0.6
- **分类/标签优先级**：分类和标签页面的权重，范围0.0-1.0，建议值0.4

### 更新频率
指定内容的更新频率：
- **always** - 总是
- **hourly** - 每小时
- **daily** - 每天（推荐）
- **weekly** - 每周
- **monthly** - 每月
- **yearly** - 每年
- **never** - 从不

### 包含的内容类型
选择要在Sitemap中包含的内容类型：
- **文章** - 博客文章
- **独立页面** - 独立页面（如关于页面等）
- **分类** - 分类归档页
- **标签** - 标签归档页

### 缓存设置
- **启用缓存**：启用后可以提升性能，但可能不是实时更新
- **缓存时间**：缓存有效时间，单位为分钟，建议60分钟

## 搜索引擎提交

生成Sitemap后，您可以将其提交给各大搜索引擎：

### Google Search Console
1. 登录 [Google Search Console](https://search.google.com/search-console)
2. 选择您的网站
3. 在左侧菜单选择"站点地图"
4. 输入`sitemap.xml`并提交

### 百度搜索资源平台
1. 登录 [百度搜索资源平台](https://ziyuan.baidu.com/)
2. 添加网站验证
3. 在"数据引入" → "链接提交"中提交Sitemap地址

### 其他搜索引擎
- **Bing Webmaster Tools**: https://www.bing.com/webmasters
- **Yandex Webmaster**: https://webmaster.yandex.com

## Sitemap格式示例

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://example.com/</loc>
    <lastmod>2024-01-01</lastmod>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>https://example.com/archives/post-slug</loc>
    <lastmod>2024-01-01</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
</urlset>
```

## 技术说明

### 路由注册
插件会自动注册`/sitemap.xml`路由，无需额外配置。

### 数据来源
- **文章**：从`contents`表获取type为`post`且status为`publish`的记录
- **页面**：从`contents`表获取type为`page`且status为`publish`的记录
- **分类**：从`metas`表获取type为`category`的记录
- **标签**：从`metas`表获取type为`tag`的记录

### 缓存机制
当启用缓存时，Sitemap会保存到`usr/plugins/Sitemap/cache/sitemap.xml`文件中，在缓存时间内直接读取文件，避免重复查询数据库。

## 常见问题

### Q: Sitemap无法访问？
A: 请确保：
1. 插件已正确启用
2. 伪静态规则已正确配置（如果使用Apache/Nginx）
3. 插件文件夹权限正确

### Q: Sitemap包含的内容不全？
A: 检查插件配置中的"包含的内容类型"选项，确保已勾选需要的内容类型。

### Q: 如何强制刷新缓存？
A: 如果启用了缓存，可以：
1. 等待缓存时间到期
2. 删除`usr/plugins/Sitemap/cache/sitemap.xml`文件
3. 临时禁用再启用缓存功能

### Q: 更新频率应该设置什么值？
A: 根据您的网站更新频率选择：
- 每天更新：选择`daily`
- 每周更新：选择`weekly`
- 不定期更新：选择`monthly`

## 版本历史

### v1.0.0 (2024-01-01)
- 初始版本发布
- 支持文章、页面、分类、标签
- 支持优先级和更新频率配置
- 内置缓存机制

## 系统要求

- Typecho 1.2.0 或更高版本
- PHP 7.0 或更高版本
- MySQL / SQLite / PostgreSQL 数据库

## 作者

Sitemap Plugin Team

## 许可证

MIT License

## 支持

如有问题或建议，请提交Issue或Pull Request。

---

**注意**：使用本插件前请备份您的网站数据，以免发生意外。
