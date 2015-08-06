<?php

namespace Syllabary\Test;

require_once(PKGPATH.'pagination-syllabary/tests/classes/share_funcs.php');

/**
 * test class Render
 * 
 * @group Package
 * @group PackageSyllabary
 */
class Test_Render extends \TestCase
{
	use Share_funcs;

	/**
	 *  通常タブをマークアップするメソッド render_plain_tab($index_char = '', $markup_status = 'regular') のテスト
	 *
	 * [概要]
	 *  ・テンプレートHTMLに埋め込まれたプレースホルダが値で置換される
	 *
	 * @test
	 */
	public function render_plain_tab()
	{
		// ■テストに用いる変数の準備
	
		// Syllabaryオブジェクトの生成
		$syllabary = new \Syllabary();
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = $this->get_accessible_method('Syllabary', 'render_plain_tab');

		// リンク作成メソッドのスタブを作成
		$syllabary = $this->getMock('Syllabary', array('_make_link'));
		$syllabary->method('_make_link')->willReturn('http://tab.test');
		
		
		// ■置換が正常に行われる
		
		// 見出し「あ」、通常タブ指定でメソッドを実行
		$result = $method->invokeArgs($syllabary, array('あ', 'regular'));
	
		// HTMLソースの置換が正しいことを確認
		$this->assertEquals("\n\t\t<li><a href=\"http://tab.test\">あ</a></li>", $result);
	}

	/**
	 *  ドロップダウンリスト付タブをマークアップするメソッド render_dropdown_list_tab($index_char = '', $markup_status = 'regular') のテスト
	 *
	 * [概要]
	 *  ・テンプレートHTMLに埋め込まれたプレースホルダが値で置換される
	 *
	 * @test
	 */
	public function render_dropdown_list_tab()
	{
		// ■テストに用いる変数の準備
	
		// Syllabaryオブジェクトの生成
		$syllabary = new \Syllabary();
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = $this->get_accessible_method('Syllabary', 'render_dropdown_list_tab');

		// リンク作成メソッドのスタブを作成
		$syllabary = $this->getMock('Syllabary', array('_make_link'));
		$syllabary->method('_make_link')->willReturn('http://tab.test');
		
		
		// ■置換が正常に行われる
	
		// 見出し「あ」、通常タブ指定でメソッドを実行
		$result = $method->invokeArgs($syllabary, array('あ', 'regular'));
	
		// HTMLソースの置換が正しいことを確認
		$this->assertEquals(
			"\n\t\t<li class=\"dropdown\">".
			"\n\t\t\t\t<a href=\"#\" data-toggle=\"dropdown\">あ<span class=\"caret\"></span></a>".
			"\n\t\t\t<ul class=\"dropdown-menu\" role=\"menu\">".
			"\n\t\t\t\t\t<li><a href=\"http://tab.test\">あ</a></li>".
			"\n\t\t\t\t\t<li><a href=\"http://tab.test\">い</a></li>".
			"\n\t\t\t\t\t<li><a href=\"http://tab.test\">う</a></li>".
			"\n\t\t\t\t\t<li><a href=\"http://tab.test\">え</a></li>".
			"\n\t\t\t\t\t<li><a href=\"http://tab.test\">お</a></li>".
			"\n\t\t\t</ul>\n\t\t</li>", $result);
	}
}