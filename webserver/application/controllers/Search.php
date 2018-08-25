<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->database();	
	}
	public function index()
	{
			
		$res = $this->db->select()->from("pagecontents")->count_all_results();
		$this->load->view('search_home', ['res'=>$res]);
	}

	private function find_in_array($arr, $key, $value){
		for ($i=0; $i < sizeof($arr); $i++) { 
			$ar = $arr[$i];
			if ($ar[$key] == $value){
				return $i;
			}
		}
		return NULL;
	}

	private function internal_find($keyword, $str){
		$filtered = array_filter(
		    str_word_count($str,2),
		    function($word) use($keyword) {
		        return $word == $keyword;
		    }
		);
		return $filtered;
	}

	public function trim( $s ){
		$max_length = 340;

		if (strlen($s) > $max_length)
		{
		    $offset = ($max_length - 3) - strlen($s);
		    $s = substr($s, 0, strrpos($s, ' ', $offset)) . '...';
		}
		return $s;
	}

	public function results()
	{
		$keywords = $this->input->get("search");
		// $this->load->database();		
		$matching = $this->db->select()->from("pagecontents")->like("content", $keywords)->count_all_results();
		$resultsq = $this->db->select()->from("pagecontents")->like("content", $keywords)->limit(10)->get();
		$ids = [];
		$results = [];
		foreach ($resultsq->result_array() as $result) {
			
			$ids[] = $result['ref'];
			$results[] = $result;
		}
		$this->db->reset_query();
		// $this->db->clear_cache();
		$results_urls = $this->db->select()->from("urlindex")->where_in("id", $ids)->get();
		// $i = 0;
		foreach ($results_urls->result_array() as $result_url) {
			# code...
			$i = $this->find_in_array($results, 'ref', $result_url['id']);
			$results[$i]['url'] = $result_url['url'];

			
		}
		for ($i=0; $i < sizeof($results); $i++) { 
			# code...
			if (!isset($results[$i]['url'])){
				$results[$i]['host'] = 'Not found';
				$results[$i]['url'] = 'Not found';
			}else{
				$parsed = parse_url($results[$i]['url']);
				$results[$i]['host'] = $parsed['host'];
			}
			$content_original = (strtolower($results[$i]['content']));
			$keywords = strtolower($keywords);
			$pos = strpos($content_original, $keywords);
			$content = substr($content_original, $pos);
			$results[$i]['occurences'] = substr_count( $content_original , $keywords );
			$results[$i]['content']=$this->trim($content);
			// $results[$i]
		}
		// $matching = $this->db->select()->from("pagecontents")->count_all_results();
		// echo "Total pages cached = $res";
		// exit;
		$this->load->view('search_results', ['res'=>$matching, 'results'=>$results]);
	}
}
