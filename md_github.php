<?php
   /*
   Plugin Name: Markdown Github
   Description: A plugin to inject markdown files directly into a post from Github
   Version: 0.1
   Author: Nils Nolde
   Author URI: https://gis-ops.com
   License: GNU v2
   */

function get_api_response($atts, $method) {

  extract(shortcode_atts(array(
          'url' => "",
        ), $atts
      )
   );

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
      'header' => "Accept: application/vnd.github.VERSION.html+json\r\n"
    )
  );

  if ($method == 'file') {
    //if we want to get the markdown file via md_github shortcode
    $request_url = 'https://api.github.com/repos/'.$owner.'/'.$repo.'/contents/'.$path.'?ref='.$branch;
    $res = file_get_contents($request_url, FALSE, stream_context_create($context_params));

    return $res;
  } else {
    //if we want to get the checkout html via checkout_github shortcode
    $request_url = 'https://api.github.com/repos/'.$owner.'/'.$repo.'/commits/'.$branch.'?path='.$path.'&page=1';
    $res = file_get_contents($request_url, FALSE, stream_context_create($context_params));

    $json = json_decode($res, true);
    return $json;
  }

  return;
}

function get_github_checkout($json, $html_url) {

  $datetime = $json['commit']['committer']['date'];

  $max_datetime = strtotime($datetime);
  $max_datetime_f = date('d/m/Y H:i:s', $max_datetime);

  $checkout_label = '<div class="markdown-github">
      <div class="markdown-github-labels">
        <label class="github-link">
          <a href="'.$html_url.'" target="_blank">Check it out on github</a>
          <label class="github-last-update"> Last updated: '.$max_datetime_f.'</label>
        </label>
      </div>
    </div>';

  return $checkout_label;
  }

function md_github_handler($atts) {
 //get raw markdown from file URL
 $res = get_api_response($atts, 'file');
 //send back text to replace shortcode in post
 return $res;
}

function md_github_checkout($atts) {
 $json = get_api_response($atts, 'checkout');
 // for the link we need the URL of the repo, not in the commit endpoint
 $res = get_api_response($atts, 'file')
 $last_update_htnl = get_github_checkout($json, $res['html_url']);

 return $last_update_htnl;
}

function md_github_enqueue_style() {
	wp_enqueue_style( 'md_github', plugins_url( 'css/md-github.css', __FILE__ ));
}
add_action( 'wp_enqueue_scripts', 'md_github_enqueue_style' );
add_shortcode('checkout_github', "md_github_checkout");
add_shortcode("md_github", "md_github_handler");
