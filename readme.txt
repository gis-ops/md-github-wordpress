=== Markdown Github ===
Contributors: Nils Nolde
Tags: github,markdown
Requires at least: 4.0
Tested up to: 5.0.2
Requires PHP: 5.6
License: GPLv2
License URI: https://github.com/gis-ops/md-github-wordpress/blob/master/LICENSE

A plugin to inject markdown files directly into a post from Github.


== Description ==
WordPress Plugin to use Github as collaboration and version control platform for Markdown documents.

It provides a shortcode to get the HTML of your Markdown document via Github's API and displays in your post using Github's CSS for Markdown. It also gives a shortcode for a formatted link to the Github document link with the latest update time.

Github API is queried on every new load of the page, so that changes in the repository will immediately be reflected on your blog. Private authentication tokens help increasing the API limit to 5000 requests per hour (enough even for Digital Ocean blogs) and accessing private repositories.

Advantages:

- Easy to update by external users via pull requests, minimizes the chance of stale tutorials
- Write Markdown in your favorite editor and just push to your repo to update your blog

Idea and most of the code is based on Andy Challis' WP plugin to display Jupyter notebooks https://github.com/ghandic/nbconvert. The CSS is taken from https://github.com/sindresorhus/github-markdown-css.

## Usage

The plugin provides 2 shortcodes:

`[md_github token=YourToken url=Github URL]`: Pulls raw HTML from the `https://api.github.com/repos/` endpoint and styles it with Github markdown CSS.

`[checkout_github token=YourToken url=Github URL]`: Displays a formatted link to the repo with the date of the latest update.

Both shortcodes take `token` and `url` as attribute. `token` is your private personal access token, which you can generate [here](https://github.com/settings/developers). `url` is the full URL to your document on Github. E.g.

`[md_github token=1d6ef5ba426648ef7d2273aca2fc80787a url=https://github.com/gis-ops/tutorials/blob/master/qgis/QGIS_PluginBasics.md]`

`[checkout_github token=1d6ef5ba426648ef7d2273aca2fc80787a url=https://github.com/gis-ops/tutorials/blob/master/qgis/QGIS_PluginBasics.md]`

## Support

Add issues at <https://github.com/gis-ops/md-github-wordpress/issues>.

== Installation ==
1. Install WP Pusher (https://wppusher.com) via ZIP and activate
2. Install from Github via WP Pusher from gis-ops/md-github-wordpress
3. Activate and add shortcode to your posts.

Alternatively:
Install as ZIP from https://github.com/gis-ops/md-github-wordpress/archive/master.zip

== Changelog ==
= v1.0 =
* First version
