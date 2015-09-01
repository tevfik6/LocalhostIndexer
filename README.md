# SugarLocalhostIndexer
Sugar Localhost Indexer is a simple application gives you better file browsing experiences within your localhost projects.

# Installation
You need the create an `.htaccess` file under your htdocs.

```
DirectoryIndex SugarLocalhostIndexer/index.php
<IfModule mod_rewrite.c>
    Options +FollowSymLinks
    RewriteEngine On

	RewriteRule ^(assets/(css|fonts|js)/.+\.(css|js|eot|svg|ttf|woff|woff2))$ SugarLocalhostIndexer/$1 [NC]
	DirectoryIndex index.php index.html
</IfModule>
```