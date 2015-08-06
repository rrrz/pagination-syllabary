<?php

namespace Syllabary\Test;

require_once(PKGPATH.'pagination-syllabary/tests/classes/share_funcs.php');

/**
 * test class String
 * 
 * @group Package
 * @group PackageSyllabary
 */
class Test_String extends \TestCase
{
	use Share_funcs;

	/**
	 *  UTF-8対応版strtr strtr_utf8() のテスト
	 *
	 * [概要]
	 *  ・2バイト文字でもstrtrで置換される
	 *
	 * @test
	 */
	public function strtr_utf8()
	{
		// ■テストに用いる変数の準備
	
		// Syllabaryオブジェクトの生成
		$syllabary = new \Syllabary();
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = $this->get_accessible_method('Syllabary', 'strtr_utf8');
	
	
		// ■引数が拗音・促音の小文字に変換される
	
		// 「しんぶんし」の「し」を「ぶ」に置換
		$result = $method->invokeArgs($syllabary, array('しんぶんし', 'し', 'ぶ'));
	
		// 「ぶんぶんぶ」に変換されたことを確認
		$this->assertEquals('ぶんぶんぶ', $result);
	}

	/**
	 *  仮名を拗音・促音の小文字に変換するメソッド convert_contracted() のテスト
	 *
	 * [概要]
	 *  ・引数の文字を半濁音に変換する
	 *
	 * @test
	 */
	public function convert_contracted()
	{
		// ■テストに用いる変数の準備
	
		// Syllabaryオブジェクトの生成
		$syllabary = new \Syllabary();
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = $this->get_accessible_method('Syllabary', 'convert_contracted');
	
	
		// ■引数が拗音・促音の小文字に変換される
	
		// 「ゆ」でメソッドを実行
		$result = $method->invokeArgs($syllabary, array('ゆ'));
	
		// 「ゅ」に変換されたことを確認
		$this->assertEquals('ゅ', $result);
	
		// 「え」でメソッドを実行
		$result = $method->invokeArgs($syllabary, array('え'));
	
		// 「ぇ」に変換されたことを確認
		$this->assertEquals('ぇ', $result);
	
		// 「つ」でメソッドを実行
		$result = $method->invokeArgs($syllabary, array('つ'));
	
		// 「っ」に変換されたことを確認
		$this->assertEquals('っ', $result);
	}
	
	/**
	 *  仮名を半濁音に変換するメソッド convert_psound() のテスト
	 *
	 * [概要]
	 *  ・引数の文字を半濁音に変換する
	 *
	 * @test
	 */
	public function convert_psound()
	{
		// ■テストに用いる変数の準備
	
		// Syllabaryオブジェクトの生成
		$syllabary = new \Syllabary();
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = $this->get_accessible_method('Syllabary', 'convert_psound');
	
	
		// ■引数が半濁音に変換される
	
		// 「ふ」でメソッドを実行
		$result = $method->invokeArgs($syllabary, array('ふ'));
	
		// 「ぷ」に変換されたことを確認
		$this->assertEquals('ぷ', $result);
	}
	
	/**
	 *  仮名を濁音に変換するメソッド convert_voiced() のテスト
	 *
	 * [概要]
	 *  ・引数の文字を濁音に変換する
	 *
	 * @test
	 */
	public function convert_voiced()
	{
		// ■テストに用いる変数の準備
	
		// Syllabaryオブジェクトの生成
		$syllabary = new \Syllabary();
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = $this->get_accessible_method('Syllabary', 'convert_voiced');
	
	
		// ■引数が濁音に変換される
	
		// 「く」でメソッドを実行
		$result = $method->invokeArgs($syllabary, array('く'));
	
		// 「ぐ」に変換されたことを確認
		$this->assertEquals('ぐ', $result);
	}

	/**
	 *  仮名の全変形バリエーションを生成するメソッド get_all_variation_kana() のテスト
	 *
	 * [概要]
	 *  ・ひらがなとカタカナの清音・濁音・半濁音・拗音・促音を全て格納した配列を生成する
	 *
	 * @test
	 */
	public function get_all_variation_kana()
	{
		// ■テストに用いる変数の準備
	
		// Syllabaryオブジェクトの生成
		$syllabary = new \Syllabary();
	
		// メソッドのアクセス制限を解除して変数に取得
		$method = $this->get_accessible_method('Syllabary', 'get_all_variation_kana');
	
	
		// ■ひらがなとカタカナ（全半角・濁音・半濁音・拗音）をすべて含んだ配列が返却される
	
		// 「は」でメソッドを実行
		$result = $method->invokeArgs($syllabary, array('は'));
	
		// 返却値の中身が全て揃っていることを確認
		$this->assertSame(array('は','ば','ぱ','ハ','バ','パ'), $result);
	
		// 「ツ」でメソッドを実行
		$result = $method->invokeArgs($syllabary, array('ツ'));
	
		// 返却値の中身が全て揃っていることを確認
		$this->assertSame(array('つ','づ','っ','ツ','ヅ','ッ'), $result);
	}

	/**
	 *  ひらがなとカタカナ（全半角・濁音・半濁音・拗音）をすべて含んだ配列を生成するメソッド get_kana_array() のテスト
	 *
	 * [概要]
	 *  ・ひらがなとカタカナ（全半角・濁音・半濁音・拗音）を全て含んだ配列が返却される
	 *
	 * @test
	 */
	public function get_kana_array()
	{
		// ■テストに用いる変数の準備
		
		// Syllabaryオブジェクトの生成
		$syllabary = new \Syllabary();
		
		// メソッドのアクセス制限を解除して変数に取得
		$method = $this->get_accessible_method('Syllabary', 'get_kana_array');
		
		
		// ■ひらがなとカタカナ（全半角・濁音・半濁音・拗音）をすべて含んだ配列が返却される
		
		// メソッドを実行
		$result = $method->invokeArgs($syllabary, array());
		
		// 返却値の中身が全て揃っていることを確認
		$this->assertSame(array(
			'あ','ぁ','ア','ァ','い','ぃ','イ','ィ','う','ぅ','ウ','ゥ','え','ぇ','エ','ェ','お','ぉ','オ','ォ',
			'か','が','カ','ガ','き','ぎ','キ','ギ','く','ぐ','ク','グ','け','げ','ケ','ゲ','こ','ご','コ','ゴ',
			'さ','ざ','サ','ザ','し','じ','シ','ジ','す','ず','ス','ズ','せ','ぜ','セ','ゼ','そ','ぞ','ソ','ゾ',
			'た','だ','タ','ダ','ち','ぢ','チ','ヂ','つ','づ','っ','ツ','ヅ','ッ','て','で','テ','デ','と','ど','ト','ド',
			'な','ナ','に','ニ','ぬ','ヌ','ね','ネ','の','ノ',
			'は','ば','ぱ','ハ','バ','パ','ひ','び','ぴ','ヒ','ビ','ピ','ふ','ぶ','ぷ','フ','ブ','プ','へ','べ','ぺ','ヘ','ベ','ペ','ほ','ぼ','ぽ','ホ','ボ','ポ',
			'ま','マ','み','ミ','む','ム','め','メ','も','モ',
			'や','ゃ','ヤ','ャ','ゆ','ゅ','ユ','ュ','よ','ょ','ヨ','ョ',
			'ら','ラ','り','リ','る','ル','れ','レ','ろ','ロ',
			'わ','ワ','を','ヲ','ん','ン'
		), $result);
	}

	/**
	 *  数字（全半角）をすべて含んだ配列を生成するメソッド get_number_array() のテスト
	 *
	 * [概要]
	 *  ・全半角の数字を全て含んだ配列が返却される
	 *
	 * @test
	 */
	public function get_number_array()
	{
		// ■テストに用いる変数の準備
		
		// Syllabaryオブジェクトの生成
		$syllabary = new \Syllabary();
		
		// メソッドのアクセス制限を解除して変数に取得
		$method = $this->get_accessible_method('Syllabary', 'get_number_array');
		
		
		// ■0-9,０-９ をすべて含んだ配列が返却される
		
		// メソッドを実行
		$result = $method->invokeArgs($syllabary, array());
		
		// 返却値の中身が全て揃っていることを確認
		$this->assertSame(array(
			'0','1','2','3','4','5','6','7','8','9',
			'０','１','２','３','４','５','６','７','８','９'
		), $result);
	}
	
	/**
	 * 英文字（全半角・大小文字）をすべて含んだ配列を生成するメソッド get_arphabet_array() のテスト
	 *
	 * [概要]
	 *  ・全半角・大小文字のアルファベット文字を全て含んだ配列が返却される
	 *
	 * @test
	 */
	public function get_arphabet_array()
	{
		// ■テストに用いる変数の準備
		
		// Syllabaryオブジェクトの生成
		$syllabary = new \Syllabary();
		
		// メソッドのアクセス制限を解除して変数に取得
		$method = $this->get_accessible_method('Syllabary', 'get_arphabet_array');
		
		
		// ■A-Z,a-z,Ａ-Ｚ,ａ-ｚ をすべて含んだ配列が返却される
		
		// メソッドを実行
		$result = $method->invokeArgs($syllabary, array());
		
		// 返却値の中身が全て揃っていることを確認
		$this->assertSame(array(
			'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
			'Ａ','Ｂ','Ｃ','Ｄ','Ｅ','Ｆ','Ｇ','Ｈ','Ｉ','Ｊ','Ｋ','Ｌ','Ｍ','Ｎ','Ｏ','Ｐ','Ｑ','Ｒ','Ｓ','Ｔ','Ｕ','Ｖ','Ｗ','Ｘ','Ｙ','Ｚ',
			'ａ','ｂ','ｃ','ｄ','ｅ','ｆ','ｇ','ｈ','ｉ','ｊ','ｋ','ｌ','ｍ','ｎ','ｏ','ｐ','ｑ','ｒ','ｓ','ｔ','ｕ','ｖ','ｗ','ｘ','ｙ','ｚ'
		), $result);
	}
}