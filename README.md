# Markdown Github

WordPress Plugin to use Github as collaboration and version control platform for Markdown documents.

Advantages:

- Easy to update by external users via pull requests, minimizes the chance of stale tutorials
- Write Markdown in your favorite editor and just push to your repo to update your blog

It features the following shortcodes:

1. `[md_github token=YourToken url=Github URL]`: Pulls raw HTML from the `https://api.github.com/repos/` endpoint and styles it with Github markdown CSS
2. `[checkout_github token=YourToken url=Github URL]`: Displays a formatted link to the repo with the date of the latest update
3. `[history_github token=YourToken url=Github URL]`: Displays a commit history of the last 5 commits.
4. `[md_dashedbox_github token=YourToken url=Github URL]`: Displays GitHub markdown file similar to [nbconvert](https://github.com/ghandic/nbconvert). This shortcode is mutually exclusive with `md_github` and `checkout_github`.

Github API is queried on every new load of the page, so that changes in the repository will immediately be reflected on your blog. Private authentication tokens help increasing the API limit to 5000 requests per hour (enough even for Digital Ocean blogs) and accessing private repositories.

Idea and most of the code is based on Andy Challis' WP plugin to display Jupyter notebooks https://github.com/ghandic/nbconvert. The CSS is taken from https://github.com/sindresorhus/github-markdown-css.

## Usage

All shortcodes take `token` and `url` as attribute. `token` is your private personal access token, which you can generate [here](https://github.com/settings/developers). `url` is the full URL to your document on Github. E.g.

`[md_github token=1d6ef5ba426648ef7d2273aca2fc80787 url=https://github.com/gis-ops/tutorials/blob/master/qgis/QGIS_PluginBasics.md]`

`[checkout_github token=1d6ef5ba426648ef7d2273aca2fc80787 url=https://github.com/gis-ops/tutorials/blob/master/qgis/QGIS_PluginBasics.md]`

`[history_github token=1d6ef5ba426648ef7d2273aca2fc80787 url=https://github.com/gis-ops/tutorials/blob/master/qgis/QGIS_PluginBasics.md]`

`[md_dashedbox_github token=1d6ef5ba426648ef7d2273aca2fc80787 url=https://github.com/gis-ops/tutorials/blob/master/qgis/QGIS_PluginBasics.md]`

## Demo

Check it out on of our blogs:

https://gis-ops.com/react-redux-leaflet-turfjs-building-a-density-based-clustering-dbscan-app-with-the-almighty-here-maps-places-api/

https://www.beyond-storage.com/wordpress-plugin-github-markdown

## Wordpress versions

**Min**: v4.0

**Tested up to**: 5.0.2


## Contributors
- Ulf Troppens: storageulf@gmail.com
