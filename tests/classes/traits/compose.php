<?php

namespace Syllabary\Test;

require_once(PKGPATH.'pagination-syllabary/tests/classes/share_funcs.php');

/**
 * test class Compose
 * 
 * @group Package
 * @group PackageSyllabary
 */
class Test_Compose extends \TestCase
{
	use Share_funcs;

	/**
	 *  クエリ文字列から五十音インデックス文字を取得してwhere句を生成するメソッド get_where() のテスト
	 *
	 * [概要]
	 *  ・array(DBの属性名, in/not in, 比較対象文字の配列) という形式のクエリ配列を返却する
	 *
	 * @test
	 */
	public function get_where()
	{
		// ■テストに用いる変数の準備
	
		// Syllabaryオブジェクトの生成
		$syllabary = new \Syllabary();
		
		
		// ■where句の配列が生成される
		
		// 見出し「あ」でテストするためにGET変数を設定
		$_GET['syl'] = 'あ';
		
		// メソッドを実行
		$result = $syllabary->get_where();
		
		// 配列の構造が正しいことを確認
		$this->assertEquals(array('', 'in', array('あ','ぁ','ア','ァ')), $result);
	}
}