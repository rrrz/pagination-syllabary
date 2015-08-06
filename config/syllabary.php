<?php
/**
 * 五十音インデックス Pagination_Syllabary
 * 
 * マークアップおよびアクティブテンプレートの設定
 */


return array(

	'active'                      => 'syllabary',

	'syllabary'                   => array(
		'wrapper'                 => "<ul class=\"nav nav-tabs\">\n\t{syllabary}\n\t</ul>\n",

		'regular'                 => "\n\t\t<li>{link}</li>",
		'regular-link'            => "<a href=\"{uri}\">{page}</a>",

		'active'                  => "\n\t\t<li class=\"active\">{link}</li>",
		'active-link'             => "<a href=\"#\">{page} <span class=\"sr-only\"></span></a>",

		'regular-dropdown'        => "\n\t\t<li class=\"dropdown\">{tab}\n\t\t\t<ul class=\"dropdown-menu\" role=\"menu\">{items}\n\t\t\t</ul>\n\t\t</li>",
		'regular-dropdown-tab'    => "\n\t\t\t\t<a href=\"{uri}\" data-toggle=\"dropdown\">{page}<span class=\"caret\"></span></a>",
		'regular-dropdown-item'   => "\n\t\t\t\t\t<li><a href=\"{uri}\">{page}</a></li>",

		'active-dropdown'        => "\n\t\t<li class=\"dropdown active\">{tab}\n\t\t\t<ul class=\"dropdown-menu\" role=\"menu\">{items}\n\t\t\t</ul>\n\t\t</li>",
		'active-dropdown-tab'    => "\n\t\t\t\t<a href=\"{uri}\" data-toggle=\"dropdown\" style=\"cursor:pointer\">{page}<span class=\"caret\"></span></a>",
		'active-dropdown-item'   => "\n\t\t\t\t\t<li><a href=\"{uri}\">{page}</a></li>",
	
	),
);
