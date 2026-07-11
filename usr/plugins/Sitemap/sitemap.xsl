<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
xmlns:html="http://www.w3.org/TR/REC-html40"
xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
<xsl:template match="/">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>XML Sitemap</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body {
font-family:"Lucida Grande","Lucida Sans Unicode",Tahoma,Verdana;
font-size:13px;
}
#intro {
background-color:#CFEBF7;
border:1px #2580B2 solid;
padding:5px 13px 5px 13px;
margin:10px;
}
table {
width:100%;
}
th {
text-align:left;
padding:5px;
background:#E5F4FA;
border-bottom:1px #77A0BF solid;
}
td {
padding:5px;
border-bottom:1px #D0E6F3 solid;
vertical-align:top;
}
a {
color:#0066CC;
text-decoration:none;
}
a:hover {
text-decoration:underline;
}
</style>
</head>
<body>
<div id="intro">
<h1>站点地图 Sitemap</h1>
<p>此页面是网站XML站点地图，用于搜索引擎收录抓取，包含本站全部有效页面链接。</p>
</div>
<table>
<tr>
<th>页面链接 Loc</th>
<th>更新时间 Lastmod</th>
<th>更新频率 Changefreq</th>
<th>权重 Priority</th>
</tr>
<xsl:for-each select="sitemap:urlset/sitemap:url">
<tr>
<td><a href="{sitemap:loc}" target="_blank"><xsl:value-of select="sitemap:loc"/></a></td>
<td><xsl:value-of select="sitemap:lastmod"/></td>
<td><xsl:value-of select="sitemap:changefreq"/></td>
<td><xsl:value-of select="sitemap:priority"/></td>
</tr>
</xsl:for-each>
</table>
</body>
</html>
</xsl:template>
</xsl:stylesheet>
