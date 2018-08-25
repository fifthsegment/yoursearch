<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function index()
	{
		
		echo 'API Server';
		exit;
	}

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}

	/**
	* Provides a new url to the crawler to index
	*/
	public function getUrl(){
		$r = $this->db->select()->from('urlindex')->where(['saved' => 0 ])->count_all_results();
		if ($r == 0){
			echo "zero";
			exit;
		}
		$d = $this->db->select()->from('urlindex')->where(['saved' => 0 ])->limit(1)->get()->result_array();
		
		echo $d[0]['url'];
		exit;
	}

	/**
	* Checks if the url already exists in the cache 
	*/
	public function checkUrl(){

        $url = file_get_contents("php://input");
        $data =json_decode($url, true);
		if (isset($_GET['inCache'])){
			$c = $this->db->select()->from('pagecache')->where(['url' => $data['Url'] ])->count_all_results();
            if ($c > 0){
                    echo "Have";
                    exit;
            }

		}
        $d = $this->db->select()->from('urlindex')->where(['url' => $data['Url'] ])->count_all_results();
        if ($d > 0 ){
                echo "Have";
                exit;
        }
		echo "No";
		exit;
	}

	/**
	* Records the page contents in our database
	*/
	public function recordData(){
        $url = file_get_contents("php://input");
        $data =json_decode($url, true);

        //var_dump($data);
        $d = $this->db->select()->from('urlindex')->where(['url' => $data['Url'] ])->count_all_results();
        if ($d == 0 ){
                echo "Donthave";
                exit;
        }else{
			$userurl = $data['Url'];
			$possiblematches = [];
			$possiblematches[] = $data['Url'];
			$b = $this->db->select()->from('urlindex')->where(['url' => $data['Url'] ])->get()->result_array();
			$b = $b[0];

			$c = $this->db->select()->from('pagecache')->where(['url' => $data['Url'] ])->count_all_results();
			if ($c > 0){
				echo "Have";
				exit;
			}

			$this->db->reset_query();
	                $this->db->flush_cache();

			$this->db->where("id", $b['id']);
	                $this->db->update("urlindex", ["saved"=>1]);
			$str = $this->db->last_query();
			echo $data['Url']	;

		}
		$this->db->reset_query();
		$this->db->flush_cache();

		$data2 = ['url' => $data['Url'], 'data'=>$data['Data'], 'ref' => $b['id']];
		$this->db->insert('pagecache', $data2);
		$data3 = ['revision' => 1, 'ref' => $b['id'], 'content'=> $data['Content']];
		$this->db->insert('pagecontents', $data3);


        $this->db->reset_query();
    	$this->db->flush_cache();
		echo "Ok";
        exit;
    }

    /*
    * Basic guessing if the url is an image from it's url string
    */
	private function isImage( $url )
  	{
	    $pos = strrpos( $url, ".");
	    if ($pos === false)
	      return false;
	    $ext = strtolower(trim(substr( $url, $pos)));
	    $imgExts = array(".gif", ".jpg", ".jpeg", ".png", ".tiff", ".tif"); // this is far from complete but that's always going to be the case...
	    if ( in_array($ext, $imgExts) )
	      return true;
	    return false;
	}

	/**
	* Add url to our index
	*/
	public function recordUrl(){
		
		$url = file_get_contents("php://input");
		$data =json_decode($url, true);
		$urlt = $data['Url'];
		if ($this->isImage($urlt)){
			echo "Wontrecord";
			exit;
		}
		if (filter_var($urlt, FILTER_VALIDATE_URL)) {

		} else {
			echo "URL invalid";
			exit;
		}		
		$d = $this->db->select()->from('urlindex')->where(['url' => $data['Url'] ])->count_all_results();
		if ($d > 0 ){
			echo "Have";
			exit;
		}
		
		$data = ['url' => $data['Url'], 'saved'=> 0];
		$this->db->insert('urlindex', $data);

		echo "Ok";
		exit;
	}
}
