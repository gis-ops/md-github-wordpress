<?php
/*
Plugin Name: Markdown Github
Description: A plugin to inject markdown files directly into a post from Github
Plugin URI: https://github.com/gis-ops/md-github-wordpress
Version: 1.2.0
Author: Nils Nolde
Author URI: https://gis-ops.com
License: GPLv2
*/

function MDGH_atts_extract($atts) {
    extract(shortcode_atts(array(
            'url' => "",
            'token' => "",
        ), $atts
        )
    );
    return array($url, $token);
}

function MDGH_get_api_response($url, $token, $method) {

    $url_list = explode('/', $url);
    $owner = $url_list[3];
    $repo = $url_list[4];
    $branch = $url_list[6];
    $path = implode("/", array_slice($url_list, 7));

    $context_params = array(
        'http' => array(
            'method' => 'GET',
            'user_agent' => 'GIS-OPS.com',
            'timeout' => 1,
            'header' => "Accept: application/vnd.github.VERSION.html+json\r\n".
                "Authorization: token ".$token."\r\n"
        )
    );

    if ($method == 'file') {
        //if we want to get the markdown file via md_github shortcode
        $request_url = 'https://api.github.com/repos/'.$owner.'/'.$repo.'/contents/'.$path.'?ref='.$branch;
        $res = file_get_contents($request_url, FALSE, stream_context_create($context_params));

        return $res;
    } else {
        //if we want to get the checkout html via checkout_github shortcode
        $request_url = 'https://api.github.com/repos/'.$owner.'/'.$repo.'/commits?path='.$path;
        $res = file_get_contents($request_url, FALSE, stream_context_create($context_params));

        $json = json_decode($res, true);
        return $json;
    }
}

function MDGH_get_github_checkout($json, $url) {

    $datetime = $json['commit']['committer']['date'];

    $max_datetime = strtotime($datetime);
    $max_datetime_f = date('d/m/Y H:i:s', $max_datetime);

    $checkout_label = '<div class="markdown-github">
      <div class="markdown-github-labels">
        <label class="github-link">
          <a href="'.$url.'" target="_blank">Check it out on github</a>
          <label class="github-last-update"> Last updated: '.$max_datetime_f.'</label>
        </label>
      </div>
    </div>';

    return $checkout_label;
}

function MDGH_get_commit_history($json, $url) {
    $url_list = explode('/', $url);
    $owner = $url_list[3];
    $repo = $url_list[4];
    $branch = $url_list[6];
    $path = implode("/", array_slice($url_list, 7));

    $url_history = 'https://github.com/'.$owner.'/'.$repo.'/commits/'.'/'.$branch.'/'.$path;
    $html_string = '<hr style="margin: 20px 0; width: 70%; border-top: 1.5px solid #aaaaaa;"><article class="markdown-body"><h2><strong><a target="_blank" href="'.$url_history.'">Post history - Last 5 commits</a></strong></h2>';

    $max_posts = 5;
    $i = 0;
    foreach ($json as $item) {
        if($i == $max_posts) break;
        $html_string .= '<p>';
        $html_string .= '<strong>'.date('d/m/Y H:i:s', strtotime($item['commit']['committer']['date'])).'</strong> - '.$item["commit"]["message"];
        $html_string .= ' ('.$item['commit']['committer']['name'].')';
        $html_string .= '</p>';
        $i++;
    }
    $html_string .= '</article>';

    return $html_string;
}

function MDGH_md_github_handler($atts) {
    list($url, $token) = MDGH_atts_extract($atts);
    //get raw markdown from file URL
    $res = MDGH_get_api_response($url, $token, 'file');
    //send back text to replace shortcode in post
    return $res;
}

function MDGH_md_dashedbox_github($atts) {
    list($url, $token) = MDGH_atts_extract($atts);
    //get checkout lable from file URL
    $checkout = MDGH_md_github_checkout($atts);
    //get raw markdown from file URL
    $markdown = MDGH_get_api_response($url, $token, 'file');
    //generate frame with border, checkout label and markdown content
    $md_dashedbox = '
      <div class="md_dashedbox">
        '.$checkout.'
        '.$markdown.'
      </div>';
    //send back text to replace shortcode in post
    return $md_dashedbox;
}

function MDGH_md_github_checkout($atts) {
    list($url, $token) = MDGH_atts_extract($atts);
    // query commit endpoint for latest update time
    $json = MDGH_get_api_response($url, $token, 'checkout')[0];
    $last_update_htnl = MDGH_get_github_checkout($json, $url);

    return $last_update_htnl;
}

function MDHG_md_github_history($atts) {
    list($url, $token) = MDGH_atts_extract($atts);
    // query commit endpoint for latest update time
    $json = MDGH_get_api_response($url, $token, 'checkout');
    $history_html = MDGH_get_commit_history($json, $url);
    return $history_html;
}

function MDGH_md_github_enqueue_style() {
    wp_enqueue_style( 'md_github', plugins_url( 'css/md-github.css', __FILE__ ));
}
add_action( 'wp_enqueue_scripts', 'MDGH_md_github_enqueue_style' );
add_shortcode('checkout_github', "MDGH_md_github_checkout");
add_shortcode("md_github", "MDGH_md_github_handler");
add_shortcode('history_github', "MDHG_md_github_history");
add_shortcode("md_dashedbox_github", "MDGH_md_dashedbox_github");

