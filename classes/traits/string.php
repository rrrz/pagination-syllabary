<?php

namespace Pagination_Syllabary;

trait String
{
	/**
	 * 英文字（全半角・大小文字）をすべて含んだ配列を生成
	 * 
	 * @return array 全半角・大小文字のアルファベット文字を全て含んだ配列
	 */
	protected function get_arphabet_array()
	{
		$chars = $chars_wide = array_merge(range('A', 'Z'), range('a', 'z'));
		
		// 作成した半角英文字の片方を全角に変換
		array_walk($chars_wide, function(&$item, $key){ $item = mb_convert_kana($item, 'R'); });
		
		return array_merge($chars, $chars_wide);
	}

	/**
	 * 数字（全半角）をすべて含んだ配列を生成
	 *
	 * @return array 全半角の数字を全て含んだ配列
	 */
	protected function get_number_array()
	{
		$chars = $chars_wide = range('0', '9');
	
		// 作成した数字の片方を文字に変換
		array_walk($chars, function(&$item, $key){ $item = strval($item); });
	
		// もう片方の数字を全角文字に変換
		array_walk($chars_wide, function(&$item, $key){ $item = mb_convert_kana($item, 'N'); });
	
		return array_merge($chars, $chars_wide);
	}

	/**
	 * ひらがなとカタカナ（全半角・清音・濁音・半濁音・拗音・促音）をすべて含んだ配列を生成
	 *
	 * @return array ひらがなとカタカナ（全半角・清音・濁音・半濁音・拗音・促音）を全て含んだ配列
	 */
	public function get_kana_array()
	{
		$chars = array('あ','い','う','え','お','か','き','く','け','こ','さ','し','す','せ','そ','た','ち','つ','て','と','な','に','ぬ','ね','の',
						'は','ひ','ふ','へ','ほ','ま','み','む','め','も','や','ゆ','よ','ら','り','る','れ','ろ','わ','を','ん');
		
		$chars_alt = array();
		
		// 五十音のそれぞれに濁音・半濁音・拗音のバリエーションがあればそれを追加
		foreach ($chars as $char){
			$chars_alt = array_merge($chars_alt, $this->get_all_variation_kana($char));
		}
		
		return $chars_alt;
	}
	
	/**
	 * 仮名の全変形バリエーションを生成
	 * 
	 * 例)
	 *  「は」 → 「は」「ば」「ぱ」「ハ」「バ」「パ」
	 *  「つ」 → 「つ」「づ」「っ」「ツ」「ヅ」「ッ」
	 * 
	 * @param string $char 変形対象の仮名（ひらがなもしくはカタカナの通常音）
	 * @return array       変形後の仮名を格納した配列
	 */
	protected function get_all_variation_kana($char = '')
	{
		$char_hira = mb_convert_kana($char, 'Hc');
		
		$chars_hira = array($char_hira);
		
		($alter = self::convert_voiced($char_hira)) and ($chars_hira[]= $alter);
		($alter = self::convert_psound($char_hira)) and ($chars_hira[]= $alter);
		($alter = self::convert_contracted($char_hira)) and ($chars_hira[]= $alter);
		
		$chars_kata = $chars_hira;
		
		array_walk($chars_kata, function(&$item, $key){ $item = mb_convert_kana($item, 'C'); });
		
		return array_merge($chars_hira, $chars_kata);
	}
	
	/**
	 * 仮名を濁音に変換
	 * 
	 * @param  string $str 変換対象の文字
	 * @return mixed       変換後の文字。変換がなされなかったときはfalse
	 */
	private function convert_voiced($str = '')
	{
		$search = 'かきくけこさしすせそたちつてとはひふへほ';
		$replace = 'がぎぐげござじずぜぞだぢづでどばびぶべぼ';
	
		$str_after = self::strtr_utf8($str, $search.mb_convert_kana($search, 'C'), $replace.mb_convert_kana($replace, 'C'));
	
		return $str_after === $str ? false : $str_after;
	}
	
	/**
	 * 仮名を半濁音に変換
	 * 
	 * @param  string $str 変換対象の文字
	 * @return mixed       変換後の文字。変換がなされなかったときはfalse
	 */
	private function convert_psound($str = '')
	{
		$search = 'はひふへほ';
		$replace = 'ぱぴぷぺぽ';
	
		$str_after = self::strtr_utf8($str, $search.mb_convert_kana($search, 'C'), $replace.mb_convert_kana($replace, 'C'));
	
		return $str_after === $str ? false : $str_after;
	}
	
	/**
	 * 仮名を拗音・促音の小文字に変換
	 * 
	 * @param  string $str 変換対象の文字
	 * @return mixed       変換後の文字。変換がなされなかったときはfalse
	 */
	private function convert_contracted($str = '')
	{
		$search = 'やゆよあいうえおつ';
		$replace = 'ゃゅょぁぃぅぇぉっ';
	
		$str_after = self::strtr_utf8($str, $search.mb_convert_kana($search, 'C'), $replace.mb_convert_kana($replace, 'C'));
	
		return $str_after === $str ? false : $str_after;
	}
	
	/**
	 * UTF-8対応版strtr
	 * 
	 * @param string $str  変換対象の文字列
	 * @param string $from 検索文字列
	 * @param string $to   置換文字列
	 * @return string      置換後の文字列
	 */
	private function strtr_utf8($str = '', $from = '', $to = '')
	{
		$from = preg_split('//u', $from, -1, PREG_SPLIT_NO_EMPTY);
		$to = preg_split('//u', $to, -1, PREG_SPLIT_NO_EMPTY);
		$replace_pairs = array();
		foreach ($from as $key => $value) {
			if (!isset($to[$key])) {
				break;
			}
			$replace_pairs[$value] = $to[$key];
		}
	
		return strtr($str, $replace_pairs);
	}
}