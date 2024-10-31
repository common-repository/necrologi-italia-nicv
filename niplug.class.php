<?php

	class NICV_niplug{

	  private $NICV_VERSION = 1;
		private $apiurl = 'https://www.necrologi-italia.it/search.api'; // production endpoint

		private $id_gruppo = 0;
		private $id_azienda = '';
		private $id_sede = '';
		private $apikey = '';
		
		private $error = false;
	
		public function __construct($nicv_version = false, $ni_host = false){
		if($nicv_version) $this->NICV_VERSION = $nicv_version;
		if($ni_host) $this->apiurl = $ni_host . 'search.api';
		}

		public function get_apiurl(){
			return $this->apiurl;
		}
		
		public function set_id_azienda($id){
			$this->id_azienda = $id;
			$this->id_sede = '';
			return true;
		}
		
		public function set_id_sede($id){
			$this->id_sede = $id;
			$this->id_azienda = '';
			return true;
		}
		
		public function set_apikey($apikey){
			$this->apikey = $apikey;
			return true;
		}

		public function set_id_gruppo($id){
			$this->id_gruppo = $id;
			return true;
		}



		public function get_by_id($id, $ref = ''){
      return $this->prepare(array(
      	'id_defunto' => $id,
				'ref' => $ref
			));
		}

    public function get_commemorazione_by_id($id, $ref = ''){
      return $this->prepare(array(
        'id_ricorrenza' => $id,
        'ref' => $ref
      ));
    }
		
		public function get_by_char($char){
			return $this->prepare('char', $char);
		}

		public function get_by_z($z){
			return $this->prepare('z', $z);
		}
		
		public function get_by_ricorrenza(){
			return $this->get_by_z(date('z'));
		}
		
		public function get_last($last = 12){
			return $this->prepare('last', $last);
		}

    public function get_last_ricorrenze($last = 12){
      return $this->prepare('last_ricorrenze', $last);
    }
		
		public function get_error(){
			return $this->error;
		}
		
		private function prepare($field, $value = ''){

			$ok = true;

			$params = array();

			$params['_NICV_VERSION'] = $this->NICV_VERSION;

			if(is_array($field)){

				foreach($field as $f => $v){
          $params[$f] = $v;
				}

			}else{
        $params[$field] = $value;
			}


			$params['useragent'] = wp_kses_post($_SERVER['HTTP_USER_AGENT']);
			
			if($this->id_azienda <> ''){
				$params['id_azienda'] = $this->id_azienda;
			}elseif($this->id_sede <> ''){
				$params['id_sede'] = $this->id_sede;
			}else{
				$this->error = 'missing id';
				return false;
			}
			
			if($this->apikey <> ''){
				$params['apikey'] = $this->apikey;
			}else{
				$this->error = 'missing apikey';
				return false;
			}

			if($this->id_gruppo <> 0){
				$params['id_gruppo'] = $this->id_gruppo;
			}
			
			if(
			      !isset($params['id_defunto'])
        and !isset($params['char'])
        and !isset($params['z'])
        and !isset($params['last'])
			  and !isset($params['last_ricorrenze'])
        and !isset($params['id_ricorrenza'])
      ){
				$this->error = 'internal error';
				return false;
			}
			
			$querystring = 'b64j=' . base64_encode(json_encode($params));
			return $this->connect($querystring);
		}
		
		private function connect($querystring){
			
			$query = $this->apiurl.'?'.$querystring;
			$result = wp_remote_retrieve_body(wp_remote_get($query));
			
			$r = explode('#', $result);
			
			if(substr($result, 0, 3)=='+ok'){
				return json_decode(base64_decode($r[1]), true);
			}elseif(substr($result, 0, 3)=='-ko'){			
				$this->error = $result;
				return false;
			}else{
				$this->error = "unknow error<br>\n" . $result;
				return false;
			}
		}

		public function city_prefix($city, $itemprop = false){
			$vocali = array('a', 'e', 'i', 'o', 'u');
			$mycity = trim(strtolower($city)); 
			foreach($vocali as $v){
				if(substr($mycity, 0, 1) == $v){
					if(!$itemprop){
						return 'ad ' . $city;
					}else{
						return 'ad <span itemprop="address" itemscope itemtype="https://schema.org/PostalAddress"><span itemprop="addressLocality">' . $city . '</span></span>';
					}
				}
			} 

			if(!$itemprop){
				return 'a ' . $city;
			}else{
				return 'a <span itemprop="address" itemscope itemtype="https://schema.org/PostalAddress"><span itemprop="addressLocality">' . $city . '</span></span>';
			}
		}
	}
?>
