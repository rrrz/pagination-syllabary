# Pagination_Syllabary

* Version: 1.0

## Information

* PHP >= 5.4
* FuelPHP = 1.7/master

## Description

![tab_capture](https://cloud.githubusercontent.com/assets/13582533/9127194/0c5fbf8c-3cf2-11e5-9cc7-cd8f0ac9cd90.jpg)

レコードの一覧表示画面などに五十音の見出し付きタブを表示し、レコードを絞り込みます。

本パッケージではFuelPHPのCore/Paginationクラスを拡張し、見出しの実装に必要な以下の機能を提供します。

* ORMパッケージの "Model::find()" 等に渡す条件文の生成

	array('index', 'in', 'つっツッﾂｯ'),

* ビューにおける見出しタブの描画

デフォルトで描画される見出しタブは以下の3種類を含んでいます。

1. 全て
2. 五十音：50文字全てを「あかさたな～わ」の段ごとに10個のドロップダウンリストのタブを表示
3. 英数字：「英文字」「数字」「その他」の3項目からなる1個のドロップダウンリストのタブを表示


## Install

(1) パッケージのインストール

■ gitリポジトリよりクローンする場合

	git clone https://github.com/rrrz/tcpdf-wrapper fuel/packages/
	
■ composerによりインストールする場合

composer.jsonに以下を追記

	"require": {
		"pagination-syllabary": "1.*"
	},
	{
		"type":"package",
		"package":{
			"name":"pagination-syllabary",
			"type":"fuel-package",
			"version":"1.0",
			"source":{
				"type":"git",
				"url":"https://github.com/rrrz/pagination-syllabary.git",
				"reference":"master"
			}
		}
	}

インストール

	composer.phar install
	composer.phar update

(2) configの設定

	vi fuel/app/config.php

		// オートローダに登録
		always_load => array(
			packages => 'pagination-syllabary',
		),


## Usage

### Controller

	public function action_index()
	{
		$options = array();
		
		
		// (1)本パッケージの見出し設定です
		
		// クラス内configの上書き
		$config_syl = array(
			
			// 見出し文字を検索するDBの属性名
			'field_name'     => 'name_initial',
			
			// ページの初回読み込み時にアクティブにするタブの名前
			// ※ '全て'の場合はデフォルトですので省略可能です。
			'default_page'   => '全て',
			
			// HTMLテンプレート名
			// ※ デフォルトでは'syllabary'しか定義されておらず、省略可能です。
			'name'           => 'syllabary',
			
			// URLのクエリ文字列で使用するセグメント
			// ※ デフォルトは'syl'になり省略できますが、ページネーションや他の検索用クエリなどと衝突しないように注意してください。
			'uri_segment'    => 'syl',
		);
		
		// インスタンス名を指定してインスタンスを生成します
		$syllabary = Syllabary::forge('mysyllabary', $config_syl);
		
		// get_where()メソッドにより配列を取得します
		if ($condition = $syllabary->get_where()){
			$options['where'] = $condition;
			
			// ここでは名前の読み仮名を格納する'name_kana'という属性で並べ替えを行っています
			$options['order_by'] = array('name_kana');
		}
		
		
		// (2)同じ一覧画面に共存させるページネーションの設定です
		
		$config = array(
			'uri_segment' => 'p',
			'per_page'    => 10,
			'num_links'   => 10,
		);
		$pagination = Pagination::forge('mypagination', $config);
		
		$options['rows_limit']  = $pagination->per_page;
		$options['rows_offset'] = $pagination->offset;
		
		
		// (3)見出しとページネーションのインスタンスをviewに渡して生成します
		
		$data = array(
			'pagination' => $pagination,
			'syllabary'  => $syllabary,
			'url_base'   =>static::$url_base
		);
		$this->template->content = View::forge( 'views/example/index', $data);
	}

### View

	<?php echo isset($syllabary) ? $syllabary : '' ?>


## Customize

config内に設定を定義し、コントローラでのインスタンス生成時にテンプレート名を指定してください。

###controller

	$config_syl = array(
		'name'           => 'example',
	);
	
	$syllabary = Syllabary::forge('myexample', $config_syl);


###config/syllabary.php

	return array(
	
		// デフォルトのテンプレート名
		'active'                      => 'syllabary',
	
		// オリジナルのテンプレート定義
		'example'                   => array(
		
		// (1)クラス内では $config に定義されている設定値です
		
			// DBで検索対象にする列名のデフォルト値
			'field_name'     => 'example_index',
			
			// ページの初回表示時に選択するインデックスのデフォルト値
			'default_page'   => '全部表示する',
			
			// URLクエリ文字列のセグメント名
			'uri_segment'    => 'exmp',
			
			// コード内で使用するURL格納用キーです
			'pagination_url' => null,
			
			// タブに表示する項目名をここで作成します
			//  1.通常のタブは値のみを定義
			//  2.ドロップダウンリストを作る際は、キーに見出しを、値にはリスト表示する項目を配列で定義
			'tab-items' => array(
				'全部表示する',
				'野菜' => array('じゃがいも','人参','きのこ'),
				'肉類' => array('豚肉','牛肉','鳥肉','ラム'),
				'海鮮類' => array('ほっけ','鮭','さんま','イカ'),
			),
		
		
		// (2)クラス内では  $template に定義されているレイアウトのためのHTMLソースです
		//    URLなどのパラメータを動的に埋め込めるように、変数での置換部分を{uri}のように書いておきます
		
			// 最外殻のタグです
			'wrapper'                 => "<div class=\"nav nav-tabs\">\n\t{syllabary}\n</div>\n",
			
			// 通常タブの未選択状態です
			'regular'                 => "<span>\n\t{link}\n</span>\n",
			// 上記の{link}部分に入るリンクのタグです
			'regular-link'            => "\t\t<a href=\"{uri}\">{page}</a>\n",
			
			// 通常タブの選択状態です
			'active'                  => "<span class=\"active\">\n\t{link}\n</span>\n",
			'active-link'             => "\t\t<a href=\"#\">{page}</a>\n",
			
			// ドロップダウンタブの未選択状態です
			'regular-dropdown'        => "\n\t\t<li class=\"dropdown\">{tab}\n\t\t\t<ul class=\"dropdown-menu\" role=\"menu\">{items}\n\t\t\t</ul>\n\t\t</li>",
			// 上記のタブ表面部分のリンクです
			'regular-dropdown-tab'    => "\n\t\t\t\t<a href=\"{uri}\" data-toggle=\"dropdown\">{page}<span class=\"caret\"></span></a>",
			// 上記のドロップダウンリスト内のリンクです
			'regular-dropdown-item'   => "\n\t\t\t\t\t<li><a href=\"{uri}\">{page}</a></li>",
			
			// ドロップダウンタブの選択状態です
			'active-dropdown'         => "\n\t\t<li class=\"dropdown active\">{tab}\n\t\t\t<ul class=\"dropdown-menu\" role=\"menu\">{items}\n\t\t\t</ul>\n\t\t</li>",
			'active-dropdown-tab'     => "\n\t\t\t\t<a href=\"{uri}\" data-toggle=\"dropdown\" style=\"cursor:pointer\">{page}<span class=\"caret\"></span></a>",
			'active-dropdown-item'    => "\n\t\t\t\t\t<li><a href=\"{uri}\">{page}</a></li>",
		
		),
	);


## License

MIT License

