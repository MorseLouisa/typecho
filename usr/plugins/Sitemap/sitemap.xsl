<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:s="http://www.sitemaps.org/schemas/sitemap/0.9">
<xsl:output method="html" encoding="UTF-8" indent="yes"/>
<xsl:template match="/">
<html>
<head>
<title>网站地图 Sitemap</title>
<meta charset="utf-8"/>
<style>
body{font-family:Arial;margin:20px;}
table{border-collapse:collapse;width:100%;}
td,th{border:1px solid #ccc;padding:8px;}
th{background:#f5f5f5;}
a{color:#0066cc;text-decoration:none;}
</style>
</head>
<body>
<h1>站点地图（Sitemap）</h1>
<table>
<tr>
<th>链接地址</th>
<th>更新时间</th>
<th>权重</th>
</tr>
<xsl:for-each select="s:urlset/s:url">
<tr>
<td><a href="{s:loc}" target="_blank"><xsl:value-of select="s:loc"/></a></td>
<td><xsl:value-of select="s:lastmod"/></td>
<td><xsl:value-of select="s:priority"/></td>
</tr>
</xsl:for-each>
</table>
</body>
</html>
</xsl:template>
</xsl:stylesheet>
