# LocalhostIndexer
Localhost Indexer is a simple application gives you better file browsing experiences within your localhost projects.

# Usage
Navigate to your web directory(htdocs) and clone the repo

`git clone https://github.com/tevfik6/LocalhostIndexer.git`

Then you need the create an `.htaccess` file under your web directory(htdocs).

```
DirectoryIndex LocalhostIndexer/index.php
<IfModule mod_rewrite.c>
    Options +FollowSymLinks
    RewriteEngine On

	RewriteRule ^(assets/(css|fonts|js|img)/.+\.(css|js|eot|svg|ttf|woff|woff2))$ LocalhostIndexer/$1 [NC]
	DirectoryIndex index.php index.html
</IfModule>
```
# Sources
- Bootstrap v3 [http://getbootstrap.com]
- jQuery [https://jquery.com]