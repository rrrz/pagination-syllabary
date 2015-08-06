<?php

namespace Pagination_Syllabary;

/**
 * HTMLソースのマークアップ
 */
trait Render
{
	/**
	 * 通常タブのマークアップ
	 *
	 * @param  string $index_char    タブの見出し文字
	 * @param  string $markup_status タブの状態('regular'： 通常, 'active'： 選択状態)
	 * @return mixed                 通常のタブ1個分のHTMLマークアップ
	 */
	private function render_plain_tab($index_char = '', $markup_status = 'regular')
	{
		$html = str_replace(array('{uri}', '{page}'), array($this->_make_link($index_char), $index_char), $this->template[$markup_status.'-link']);
		$html = str_replace('{link}', $html, $this->template[$markup_status]);
	
		$this->raw_results[] = array('uri' => $this->_make_link($index_char), 'title' => $index_char, 'type' => $markup_status);
	
		return $html;
	}
	
	/**
	 * ドロップダウンリスト付タブのマークアップ
	 *
	 * @param  string $index_char    タブの見出し文字
	 * @param  string $markup_status タブの状態('regular'： 通常, 'active'： 選択状態)
	 * @return mixed                 ドロップダウンリスト付のタブ1個分のHTMLマークアップ
	 */
	private function render_dropdown_list_tab($index_char = '', $markup_status = 'regular')
	{
		$html_items = '';
		$char_set = $this->config['tab-items'][$index_char];
		foreach ($char_set as $item)
		{
			$html_items .= str_replace(array('{uri}', '{page}'), array($this->_make_link($item), $item), $this->template[$markup_status.'-dropdown-item']);
		}
		$index_char =  in_array($this->config['calculated_page'], $char_set) ? $this->config['calculated_page'] : $index_char;
		$html_tab = str_replace(array('{uri}', '{page}'), array('#', $index_char), $this->template[$markup_status.'-dropdown-tab']);
	
		$html = str_replace(array('{tab}', '{items}'), array($html_tab, $html_items), $this->template[$markup_status.'-dropdown']);
	
		$this->raw_results[] = array('uri' => $this->_make_link($index_char), 'title' => $index_char, 'type' => $markup_status);
	
		return $html;
	}
}