<?php

namespace Pagination_Syllabary;

/**
 * 五十音インデックス
 * 
 * Core\Paginationを拡張
 * マークアップはapp\config\pagination.phpにsyllabaryとして記述し、インスタンス生成時に'name'=>'syllabary'として読み込み
 */

class Syllabary extends \Pagination
{
	use Compose;
	use Render;
	use String;
	
	/**
	 * イニシャライザ
	 */
	public static function _init()
	{
		\Config::load('syllabary', true);
		
		parent::_init();
	}
	
	/**
	 * インスタンス設定定数
	 */
	protected $config = array(
	
		// DBで検索対象にする列名のデフォルト値
		'field_name'     => '',
		
		// ページの初回表示時に選択するインデックスのデフォルト値
		'default_page'   => '全て',
		
		// URLクエリ文字列のセグメント名
		'uri_segment'    => 'syl',
		
		'pagination_url' => null,
		
		// タブに表示する項目名
		//  1.通常のタブは値のみを定義
		//  2.ドロップダウンリストを作る際は、キーに見出しを、値にはリスト表示する項目を配列で定義
		'tab-items' => array(
				'全て',
				'あ' => array('あ','い','う','え','お'),
				'か' => array('か','き','く','け','こ'),
				'さ' => array('さ','し','す','せ','そ'),
				'た' => array('た','ち','つ','て','と'),
				'な' => array('な','に','ぬ','ね','の'),
				'は' => array('は','ひ','ふ','へ','ほ'),
				'ま' => array('ま','み','む','め','も'),
				'や' => array('や','ゆ','よ'),
				'ら' => array('ら','り','る','れ','ろ'),
				'わ' => array('わ','を','ん'),
				'英数字' => array('英文字','数字','その他'),
		),
	);
	
	/**
	 * インスタンスが用いるテンプレート
	 */
	protected $template = array(
		'wrapper'                 => "<div class=\"nav nav-tabs\">\n\t{syllabary}\n</div>\n",

		'regular'                 => "<span>\n\t{link}\n</span>\n",
		'regular-link'            => "\t\t<a href=\"{uri}\">{page}</a>\n",
		'active'                  => "<span class=\"active\">\n\t{link}\n</span>\n",
		'active-link'             => "\t\t<a href=\"#\">{page}</a>\n",

		'regular-dropdown'        => "",
		'regular-dropdown-tab'    => "\n\t\t\t\t<a href=\"{uri}\" data-toggle=\"dropdown\">{page}<span class=\"caret\"></span></a>",
		'regular-dropdown-item'   => "\n\t\t\t\t\t<li><a href=\"{uri}\">{page}</a></li>",

		'active-dropdown'         => "\n\t\t<li class=\"dropdown active\">{tab}\n\t\t\t<ul class=\"dropdown-menu\" role=\"menu\">{items}\n\t\t\t</ul>\n\t\t</li>",
		'active-dropdown-tab'     => "\n\t\t\t\t<a href=\"{uri}\" data-toggle=\"dropdown\" style=\"cursor:pointer\">{page}<span class=\"caret\"></span></a>",
		'active-dropdown-item'    => "\n\t\t\t\t\t<li><a href=\"{uri}\">{page}</a></li>",
	);
	
	/**
	 * コンストラクタ
	 */
	public function __construct($config = array())
	{
		is_array($config) or $config = array('name' => $config);
	
		array_key_exists('name', $config) or $config['name'] = \Config::get('syllabary.active', 'syllabary');
	
		$config = array_merge(\Config::get('syllabary.'.$config['name'], array()), $config);
	
		unset($config['name']);
	
		foreach ($config as $key => $value)
		{
			$this->__set($key, $value);
		}
	}
	
	/**
	 * マークアップの生成
	 *
	 * @param  bool  $raw trueの場合はデータ配列を返却
	 * @return mixed      $configに定義されたリストのリンク済みHTMLマークアップまたはデータ配列
	 */
	public function render($raw = false)
	{
		$html = '';
		
		// ドロップダウンリストかどうか、またタブの見出し項目がアクティブかどうかを判定しながらマークアップ
		foreach ($this->config['tab-items'] as $key => $value)
		{
			if (is_array($value))
			{
				$html .= self::render_dropdown_list_tab($key, in_array($this->config['calculated_page'], $value) ? 'active' : 'regular');
			}
			else
			{
				$html .= self::render_plain_tab($value, $this->config['calculated_page'] == $value ? 'active' : 'regular');
			}
		}
		
		$html = str_replace('{syllabary}', $html, $this->template['wrapper']);
		
		return $raw ? $this->raw_results : $html;;
	}

	/**
	 * リンク生成に用いる変数を再計算
	 */
	protected function _recalculate()
	{
		if (is_string($this->config['uri_segment']))
		{
			$index_char = \Input::get($this->config['uri_segment'], null);
		}
		else
		{
			$index_char = (int) \Request::main()->uri->get_segment($this->config['uri_segment']);
		}
		
		// パラメータが渡されていなければデフォルトのインデックスを選択
		if ($index_char === null)
		{
			$this->config['calculated_page'] = $this->config['default_page'];
		}
		else 
		{
			$this->config['calculated_page'] = $index_char;
		}
	}
	
	
	/**
	 * リンク生成
	 * 
	 * Paginationクラスからの改変はコメント部分の一行のみ
	 * 
	 *  @param int     $page Paginationのページ番号
	 *  @return string       URL
	 */
	protected function _make_link($page)
	{
		empty($page) and $page = 1;
	
		if (is_null($this->config['pagination_url']))
		{
			$this->config['pagination_url'] = \Uri::main();
			// <<< GET変数の引き継ぎに関するここの一行を削除 >>>
		}

		if (strpos($this->config['pagination_url'], '{page}') === false)
		{
			$url = parse_url($this->config['pagination_url']);
	
			if (isset($url['query']))
			{
				parse_str($url['query'], $url['query']);
			}
			else
			{
				$url['query'] = array();
			}

			$seg_offset = parse_url(rtrim(\Uri::base(), '/'));
			$seg_offset = empty($seg_offset['path']) ? 0 : count(explode('/', trim($seg_offset['path'], '/')));
	
			if (is_numeric($this->config['uri_segment']))
			{
				$segs = isset($url['path']) ? explode('/', trim($url['path'], '/')) : array();
	
				if (count($segs) < $this->config['uri_segment'] - 1)
				{
					throw new \RuntimeException("Not enough segments in the URI, impossible to insert the page number");
				}
	
				$segs[$this->config['uri_segment'] - 1 + $seg_offset] = '{page}';
				$url['path'] = '/'.implode('/', $segs);
			}
			else
			{
				$url['query'][$this->config['uri_segment']] = '{page}';
			}
	
			$query = empty($url['query']) ? '' : '?'.preg_replace('/%7Bpage%7D/', '{page}', http_build_query($url['query'], '', '&amp;'));
			unset($url['query']);
			empty($url['scheme']) or $url['scheme'] .= '://';
			empty($url['port']) or $url['host'] .= ':';
			$this->config['pagination_url'] = implode($url).$query;
		}
	
		return str_replace('{page}', $page, $this->config['pagination_url']);
	}
}
	
