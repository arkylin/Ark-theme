<?php
//使用 CDN 加速 gravatar
function gravatar_cdn($url){
    // gravatar.loli.net/avatar/
    // secure.gravatar.com/avatar/ (结束)
    //sdn.geekzu.org/avatar/ (推荐，速度很快)
    //gravatar.helingqi.com/wavatar/ (我在用的，但是速度没有上面快，但是默认头像自带随机头像)
    //cdn.v2ex.com/gravatar/
    // cdn.v2ex.com/gravatar/
    // dn-qiniu-avatar.qbox.me/avatar/
	$cdn = "sdn.geekzu.org/avatar/";
	$cdn = str_replace("http://", "", $cdn);
	$cdn = str_replace("https://", "", $cdn);
	if (substr($cdn, -1) != '/'){
		$cdn .= "/";
	}
	$url = preg_replace("/\/\/(.*?).gravatar.com\/avatar\//", "//" . $cdn, $url);
	return $url;
}
add_filter('get_avatar_url', 'gravatar_cdn');

//主题文章短代码解析
add_shortcode('collapse','shortcode_collapse_block');
// add_shortcode('fold','shortcode_collapse_block');
function shortcode_collapse_block($attr,$content=""){
	$collapse_id = mt_rand(1000000000 , 9999999999);
	$collapsed = isset($attr['collapsed']) ? $attr['collapsed'] : 'true';
	$out = '<div class="accordion">';
	$out .= '<div class="accordion-item">';
	$out .= '<h2 class="accordion-header">';
	$out .= '<button class="accordion-button';
	if ($collapsed == 'true'){
		$out .= ' collapsed';
	}
	$out .= '" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-' . $collapse_id . '" aria-expanded=';
	if ($collapsed == 'true'){
		$out .= '"false"';
	}
	$out .= 'aria-controls="collapse-' . $collapse_id . '">';
    $out .= $attr['title'];
	$out .= '</button>';
	$out .= '</h2>';
	$out .= '<div id="collapse-' . $collapse_id . '" class="accordion-collapse collapse';
	if ($collapsed == 'false'){
		$out .= ' show';
	}
	$out .= '">';
	$out .= '<div class="accordion-body">';
    $out .= $content . "</div></div></div></div>";
	return $out;
}
// TinyMce 按钮
function Ark_tinymce_extra_buttons(){
	if(get_user_option('rich_editing') == 'true'){
		add_filter('mce_external_plugins', 'Ark_tinymce_add_plugin');
		add_filter('mce_buttons', 'Ark_tinymce_register_button');
	}
}
add_action('init', 'Ark_tinymce_extra_buttons');
function Ark_tinymce_register_button($buttons){
	array_push($buttons, "", "collapse");
	return $buttons;
}
function Ark_tinymce_add_plugin($plugins){
	$plugins['collapse'] = get_bloginfo('template_url') . '/assets/js/tinymce/tinymce_btns.js';
	return $plugins;
}
// 搜索
function my_search_form( $form ) {
 
    $form = '<form action="/" method="get">
	<div class="row">
    <input name="s" class="form-control col" type="search" placeholder="搜索" aria-label="搜索">
	&nbsp&nbsp
	<button class="btn btn-outline-info col-2" type="submit">🔍</button>
	</div>
	</form>';
 
    return $form;
}
add_filter( 'get_search_form', 'my_search_form' );

function Ark_get_first_image_of_article(){
	global $post;
	$post_content_full = apply_filters('the_content', preg_replace( '<!--more(.*?)-->', '', $post -> post_content));
	preg_match('/<img(.*?)src="((http:|https:)?\/\/(.*?))"(.*?)\/>/', $post_content_full, $match);
	if (isset($match[2])){
		return $match[2];
	}
	return false;
}
function Ark_has_post_thumbnail($postID = 0){
	if ($postID == 0){
		global $post;
		$postID = $post -> ID;
	}
	if (has_post_thumbnail()){
		return true;
	}
	$Ark_first_image_as_thumbnail = get_post_meta($postID, 'Ark_first_image_as_thumbnail', true);
	if ($Ark_first_image_as_thumbnail == ""){
		$Ark_first_image_as_thumbnail = "default";
	}
	if ($Ark_first_image_as_thumbnail == "true" || ($Ark_first_image_as_thumbnail == "default")){
		if (Ark_get_first_image_of_article() != false){
			return true;
		}
	}
	return false;
}
function Ark_get_post_thumbnail($postID = 0){
	if ($postID == 0){
		global $post;
		$postID = $post -> ID;
	}
	if (has_post_thumbnail()){
		return wp_get_attachment_image_src(get_post_thumbnail_id($postID), "full")[0];
	}
	return Ark_get_first_image_of_article();
}

//输出分页页码
function get_Ark_formatted_paginate_links($maxPageNumbers, $extraClasses = ''){
	$args = array(
		'prev_text' => '',
		'next_text' => '',
		'before_page_number' => '',
		'after_page_number' => '',
		'show_all' => True
	);
	$res = paginate_links($args);
	//单引号转双引号 & 去除上一页和下一页按钮
	$res = preg_replace(
		'/\'/',
		'"',
		$res
	);
	$res = preg_replace(
		'/<a class="prev page-numbers" href="(.*?)">(.*?)<\/a>/',
		'',
		$res
	);
	$res = preg_replace(
		'/<a class="next page-numbers" href="(.*?)">(.*?)<\/a>/',
		'',
		$res
	);
	//寻找所有页码标签
	preg_match_all('/<(.*?)>(.*?)<\/(.*?)>/' , $res , $pages);
	$total = count($pages[0]);
	$current = 0;
	$urls = array();
	for ($i = 0; $i < $total; $i++){
		if (preg_match('/<span(.*?)>(.*?)<\/span>/' , $pages[0][$i])){
			$current = $i + 1;
		}else{
			preg_match('/<a(.*?)href="(.*?)">(.*?)<\/a>/' , $pages[0][$i] , $tmp);
			$urls[$i + 1] = $tmp[2];
		}
	}

	if ($total == 0){
		return "";
	}

	//计算页码起始
	$from = max($current - ($maxPageNumbers - 1) / 2 , 1);
	$to = min($current + $maxPageNumbers - ( $current - $from + 1 ) , $total);
	if ($to - $from + 1 < $maxPageNumbers){
		$to = min($current + ($maxPageNumbers - 1) / 2 , $total);
		$from = max($current - ( $maxPageNumbers - ( $to - $current + 1 ) ) , 1);
	}
	//生成新页码
	$html = "";
	if ($from > 1){
		$html .= '<li class="page-item"><a aria-label="First Page" class="page-link" href="' . $urls[1] . '"><i class="fa fa-angle-double-left" aria-hidden="true"></i></a></li>';
	}
	if ($current > 1){
		$html .= '<li class="page-item"><a aria-label="Previous Page" class="page-link" href="' . $urls[$current - 1] . '"><i class="fa fa-angle-left" aria-hidden="true"></i></a></li>';
	}
	for ($i = $from; $i <= $to; $i++){
		if ($current == $i){
			$html .= '<li class="page-item active"><span class="page-link" style="cursor: default;">' . $i . '</span></li>';
		}else{
			$html .= '<li class="page-item"><a class="page-link" href="' . $urls[$i] . '">' . $i . '</a></li>';
		}
	}
	if ($current < $total){
		$html .= '<li class="page-item"><a aria-label="Next Page" class="page-link" href="' . $urls[$current + 1] . '"><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>';
	}
	if ($to < $total){
		$html .= '<li class="page-item"><a aria-label="Last Page" class="page-link" href="' . $urls[$total] . '"><i class="fa fa-angle-double-right" aria-hidden="true"></i></a></li>';
	}
	return '<nav><ul class="justify-content-center pagination' . $extraClasses . '">' . $html . '</ul></nav>';
}
function get_Ark_formatted_paginate_links_for_all_platforms(){
	return get_Ark_formatted_paginate_links(7) . get_Ark_formatted_paginate_links(5, " pagination-mobile");
}

?>
