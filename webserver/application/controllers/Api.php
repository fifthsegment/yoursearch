<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		
		$this->load->view('welcome_message');
	}

	public function __construct(){
		parent::__construct();
		$this->load->database();
	}
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
                //var_dump($data);
                $d = $this->db->select()->from('urlindex')->where(['url' => $data['Url'] ])->count_all_results();
                if ($d > 0 ){
                        echo "Have";
                        exit;
                }
		echo "No";
		exit;
	}
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
				//var_dump($b['id']);
				//exit;
				$this->db->reset_query();
		                $this->db->flush_cache();

				$this->db->where("id", $b['id']);
		                $this->db->update("urlindex", ["saved"=>1]);
				$str = $this->db->last_query();
				echo $data['Url']	;
				//echo $str;
				//exit;
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
	public function isImage( $url )
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
	public function recordUrl(){
		
		$url = file_get_contents("php://input");
		$data =json_decode($url, true);
		$urlt = $data['Url'];
//		$urlt = strtolower($urlt);
if ($this->isImage($urlt)){
	echo "Wontrecord";
	exit;
}
		if (filter_var($urlt, FILTER_VALIDATE_URL)) {

		} else {
			echo "URL invalid";
			exit;
		}		
		//var_dump($data);
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
