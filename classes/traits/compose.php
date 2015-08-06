<?php

namespace Pagination_Syllabary;

trait Compose
{
	/**
	 * クエリ文字列から五十音インデックス文字を取得してwhere句を生成
	 * 
	 * array(DBの属性名, in/not in, 比較対象文字の配列) という形式のクエリ配列を返却します
	 * 
	 * @return array where句のクエリ配列
	 */
	public function get_where()
	{
		$index_char = \Input::get($this->config['uri_segment'], null);
		
		switch ($index_char){
			case null:
			case '全て':
				
				return null;
				
			case '英文字':
				
				return array($this->config['field_name'], 'in', $this->get_arphabet_array());
				
			case '数字':
				
				return array($this->config['field_name'], 'in', $this->get_number_array());
				
			case 'その他':
				
				$chars = array_merge($this->get_arphabet_array(), $this->get_number_array(), $this->get_kana_array());
				
				return array($this->config['field_name'], 'not in', $chars);
				
			default:
				
				if (strlen($index_char) != mb_strlen($index_char))
				{
					return array($this->config['field_name'], 'in', $this->get_all_variation_kana($index_char));
				}
				else
				{
					return null;
				}
		}
	}
}