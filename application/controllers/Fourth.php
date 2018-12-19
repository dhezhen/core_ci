<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fourth extends CI_Controller {

    public function __Construct() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
        parent::__Construct();
        $this->load->model('Fourth_model','fourthmdl');
        $api_key = array("NiKUjd4HUKKK892J7Y23");
       $apikey = $this->input->post('api_key');
       if (!in_array($apikey, $api_key)) {
          echo "die";
          die();
       } 
    }

    public function index() {
        return null;
    }
    
    public function getallPaketdt(){
        $data = $this->fourthmdl->get_all_paketdt();
        $result = array();
        $res = "";
        foreach($data as $buff){
            $res .= '
			
<div class="col-50">
                        <div class="card ks-card-header-pic">
                            <div style="background-image:url(localhost/core/uploads/photo'.$buff->bmd_url_gambarpromo.')" valign="bottom" class="card-header color-white no-border"></div>
                            <div class="card-content" style="align:center"> 
                                <div class="card-content-inner"> 
                                    <p style="font-size: 12px;font-weight: bold;">'.$buff->bmd_nama_paket.'</p>
                                    <p style="font-size: 20px;font-weight: bold; color:blue">IDR.'.number_format($buff->bmd_harga, 0, ',', '.').',-</p>
                                    <p style="font-size: 12px;font-weight: bold;">'.$buff->bmd_deskripsi.'</p>
                                </div>
                            </div>
                            <div class="card-footer"   style="align:center>
							
						 <a href ="#" class="link">
								
							 </a> 
								<a class="link external" href ="https://api.whatsapp.com/send?phone='.$buff->bmd_contact_person.'&text=Halo%20Admin%20Saya%20Mau%20Order%20Paket%20'.$buff->bmd_nama_vendor.'%0ANama%20%3A%0AAlamat%20%3A%0ANama%20paket%20%3A*'.$buff->bmd_nama_paket.'*%0Ajumlah%20Pesan%3A" class="link">
								<button class="col button button-fill color-blue" style="align:center">Pesan</button></a>
								 </a>
<div data-popup=".dm'.$buff->bmd_id.'-popup" class="col-50 open-popup" style="cursor: pointer; ">
	 <button  class="col button button-fill color-green" style="align:center">Detail</button>
	 </div>
																 
							 
							 </div>
                        </div>
                    </div>

</div>	   
		   ';			
       
	   }
			//footer//<h1 style=" tex-align:center;font-size: 20px;font-weight: bold; color: blue">'.$buff->bmd_nama_vendor.'</p>

		
        $result['data'] = $res;
        
        echo json_encode($result);
    } 
	
	/* data paket digi */
	public function getallPaketdigi(){
        $data = $this->fourthmdl->get_all_promo1();
        $result = array();
        $res = "";
        foreach($data as $buff){
            $res .= '
				  
<div class="col-50">
                        <div class="card ks-card-header-pic">
                            <div style="background-image:url('.$buff->bmd_url_gambarpromo.')" valign="bottom" class="card-header color-white no-border"></div>
                            <div class="card-content" style="align:center"> 
                                <div class="card-content-inner"> 
                                    <p style="font-size: 12px;font-weight: bold;">'.$buff->bmd_nama_paket.'</p>
                                    <p style="font-size: 20px;font-weight: bold; color:blue">IDR.'.number_format($buff->bmd_harga, 0, ',', '.').',-</p>
                                    <p style="font-size: 12px;font-weight: bold;">'.$buff->bmd_deskripsi.'</p>
                                </div>
                            </div>
                            <div class="card-footer"   style="align:center>
							
					 
								 <a href ="#" class="link">
								
							 </a> 
								<a class="link external" href ="https://api.whatsapp.com/send?phone='.$buff->bmd_contact_person.'&text=Halo%20Admin%20Saya%20Mau%20Order%20Paket%20'.$buff->bmd_nama_vendor.'%0ANama%20%3A%0AAlamat%20%3A%0ANama%20paket%20%3A*'.$buff->bmd_nama_paket.'*%0Ajumlah%20Pesan%3A">
								<button class="col button button-fill color-blue" style="align:center">Pesan</button></a>
								 </a>
<div data-popup=".dm'.$buff->bmd_id.'-popup" class="col-50 open-popup" style="cursor: pointer; ">
	 <button  class="col button button-fill color-green" style="align:center">Detail</button>
	 </div>
																 
							 
							 </div>
                        </div>
                    </div>

</div>	   
		 	   
		   ';			
       
	   }
			//footer//<h1 style=" tex-align:center;font-size: 20px;font-weight: bold; color: blue">'.$buff->bmd_nama_vendor.'</p>

		
        $result['data'] = $res;
        
        echo json_encode($result);
    } 
	
	
	/* data vendor haji dan umroh*/
	public function getallvendor(){
        $data = $this->fourthmdl->get_all_vendor();
        $result = array();
        $res = "";
        foreach($data as $buff){
            $res .= '
			<div class="col-50">
			<a  href='.$buff->bmd_url_promo.'>
                        <div class="card ks-card-header-pic">
                            <div style="background-image:url('base_url().'uploads/photo'.$buff->bmd_url_logo.')" valign="center" class="card-header"></div>
                            <div class="card-content"> 
                             </div>
                        				
							</div>
                        </div>
							</a>
                    </div>';			
        }
			//footer//<h1 style=" tex-align:center;font-size: 20px;font-weight: bold; color: blue">'.$buff->bmd_nama_vendor.'</p>

		
        $result['data'] = $res;
        
        echo json_encode($result);
		ini_set('display_errors', 1);
    }
	
	/*VENDOR HOTEL AND RESORT*/
	public function getallvendorhotel(){
        $data = $this->fourthmdl->get_all_vendorhotel();
        $result = array();
        $res = "";
        foreach($data as $buff){
            $res .= '
			<div class="col-50">
			<a  href='.$buff->bmd_url_promo.'>
                        <div class="card ks-card-header-pic">
                            <div style="background-image:url('.$buff->bmd_url_logo.')" valign="center" class="card-header"></div>
                            <div class="card-content"> 
                                                          </div>
                        				
													</div>
                        </div>
									</a>
                    </div>';			
        }
			//footer//<h1 style=" tex-align:center;font-size: 20px;font-weight: bold; color: blue">'.$buff->bmd_nama_vendor.'</p>

		
        $result['data'] = $res;
        
        echo json_encode($result);
		
		    }	
	
	
	/* VENDOR TOUR AND TRAVEL */
	public function getallvendortour(){
        $data = $this->fourthmdl->get_all_vendortour();
        $result = array();
        $res = "";
        foreach($data as $buff){
            $res .= '
			<div class="col-50">
			<a  href='.$buff->bmd_url_promo.'>
                        <div class="card ks-card-header-pic">
                            <div style="background-image:url('.$buff->bmd_url_logo.')" valign="center" class="card-header"></div>
                            <div class="card-content"> 
                                                          </div>
                        				
													</div>
                        </div>
									</a>
                    </div>';			
        }
			//footer//<h1 style=" tex-align:center;font-size: 20px;font-weight: bold; color: blue">'.$buff->bmd_nama_vendor.'</p>

		
        $result['data'] = $res;
        
        echo json_encode($result);
    }
	
	
	/*  */
	
	
		 public function getallPaketPromo(){
        $data = $this->fourthmdl->get_all_promo();
        $result = array();
        $res = "";
        foreach($data as $buff){
            $res .= '
			<div class="card demo-card-header-pic" style="float:left">
<div class="card-content card-content-padding">
<image src="'.$buff->bmd_url_gambarpromo.'" width="200px" height="200px">
<center>
<p>Umroh<p>
<p><h1 style="font-size:20px;color:#0000cc;font-style:bold;">IDR '.$buff->bmd_harga.'</h1><p>
<center>

</div>
<div class="card-footer" style="align:center">
<div data-popup=".dreamtour1-popup" class="col-50 open-popup" style="cursor: pointer;">
	 <button class="col button button-fill color-green" style="align:center">Detail</button>
	 </div>
	<a class ="link external" href="https://api.whatsapp.com/send?phone='.$buff->bmd_contact_person.'&text=Halo%20Admin%20Saya%20Mau%20Order%20paket%20Dreamtour"> <button class="col button button-fill color-blue" style="align:center">chat</button></a>
	</div>
</div>
				   ';			
        }
			//footer//<h1 style=" tex-align:center;font-size: 20px;font-weight: bold; color: blue">'.$buff->bmd_nama_vendor.'</p>

		
        $result['data'] = $res;
        
        echo json_encode($result);
    }

	
	
	
	
	
	
    public function getallPaketpesanan(){
        $id = $this->input->post('id');
        $id_extract = explode(",", $id);
        $id_jn = $id_jm = array();
        foreach($id_extract as $buff){
            $idjn = explode("_", $buff);
            $id_jn[] = $idjn[0];
            $id_jm[$idjn[0]] = $idjn[1];
        }
		
        $data = $this->fourth->get_all_hajidanumroh_by_pesanan($id_jn);
        $result = array();
        $res = "";
        $total_harga = 0;
        foreach($data as $buff){
            $res .= '<li>
                        <input type="hidden" class="bmd_id" value="'.$buff->bmd_id.'">
                        <input type="hidden" class="bmd_harga" value="'.$buff->bmd_harga.'">
                        <div class="item-content">
                            <div class="item-media"><img style="border-radius: 0px !important;" src="'.$buff->bmd_url_gambarpromo.'" width="44"></div>
                            <div class="item-inner">
                                <div class="item-title-row">
                                    <div class="item-title">'.$buff->bmd_nama_paket.' - ('.$buff->bmd_nama_vendor.')</div>
                                    <div class="item-after">
                                        <div class="item-input item-input-field not-empty-state">
                                            <select onchange="changejumlahbeli2(this, \''.$buff->bmd_harga.'\', \''.$buff->bmd_id.'_'.$id_jm[$buff->bmd_id].'\', '.$buff->bmd_id.')" class="not-empty-state jumlah_beli">
                                              <option'.((isset($id_jm[$buff->bmd_id]) && $id_jm[$buff->bmd_id] == 1) ? ' selected' : '').' value="1">1</option>
                                              <option'.((isset($id_jm[$buff->bmd_id]) && $id_jm[$buff->bmd_id] == 2) ? ' selected' : '').' value="2">2</option>
                                              <option'.((isset($id_jm[$buff->bmd_id]) && $id_jm[$buff->bmd_id] == 3) ? ' selected' : '').' value="3">3</option>
                                              <option'.((isset($id_jm[$buff->bmd_id]) && $id_jm[$buff->bmd_id] == 4) ? ' selected' : '').' value="4">4</option>
                                              <option'.((isset($id_jm[$buff->bmd_id]) && $id_jm[$buff->bmd_id] == 5) ? ' selected' : '').' value="5">5</option>
                                              <option'.((isset($id_jm[$buff->bmd_id]) && $id_jm[$buff->bmd_id] == 6) ? ' selected' : '').' value="6">6</option>
                                              <option'.((isset($id_jm[$buff->bmd_id]) && $id_jm[$buff->bmd_id] == 7) ? ' selected' : '').' value="7">7</option>
                                              <option'.((isset($id_jm[$buff->bmd_id]) && $id_jm[$buff->bmd_id] == 8) ? ' selected' : '').' value="8">8</option>
                                              <option'.((isset($id_jm[$buff->bmd_id]) && $id_jm[$buff->bmd_id] == 9) ? ' selected' : '').' value="9">9</option>
                                              <option'.((isset($id_jm[$buff->bmd_id]) && $id_jm[$buff->bmd_id] == 10) ? ' selected' : '').' value="10">10</option>
                                            </select>
                                        </div>
                                        <a href="#" onclick="delete_list_chart(\''.$buff->bmd_id.'_'.$id_jm[$buff->bmd_id].'\')" style="color: red;margin-left: 4px;margin-top: 3px;"><i class="material-icons">close</i></a>
                                    </div>
                                </div>
                                <div class="item-subtitle" style="color: #009688;">Rp.'.number_format($buff->bmd_harga, 0, ',', '.').',-</div>
                            </div>
                        </div>
                    </li>';
            // $res .= '<li>
            //             <input type="hidden" class="bmd_id" value="'.$buff->bmd_id.'">
            //             <input type="hidden" class="bmd_harga" value="'.$buff->bmd_harga.'">
            //             <div class="item-content">
            //                 <div class="item-media"><img src="'.$buff->bmd_url_gambar.'" style="width: 50px;"></div>
            //                 <div class="item-inner" style="margin-left: 0px !important;">
            //                     <div class="item-title">'.$buff->bmd_nama_barang.' - ('.$buff->bmd_warna.')</div>
            //                     <div class="item-after">
            //                         <div class="item-input item-input-field not-empty-state">
            //                             <select onchange="changejumlahbeli(this, \''.$buff->bmd_harga.'\')" class="not-empty-state jumlah_beli">
            //                               <option value="1">1</option>
            //                               <option value="2">2</option>
            //                               <option value="3">3</option>
            //                               <option value="4">4</option>
            //                               <option value="5">5</option>
            //                               <option value="6">6</option>
            //                               <option value="7">7</option>
            //                               <option value="8">8</option>
            //                               <option value="9">9</option>
            //                               <option value="10">10</option>
            //                             </select>
            //                         </div>
            //                         <a href="#" onclick="delete_list_chart('.$buff->bmd_id.')" style="color: red;margin-left: 4px;margin-top: 3px;"><i class="material-icons">close</i></a>
            //                     </div>
            //                 </div>
            //             </div>
            //         </li>';
            $total_harga = $total_harga + ($id_jm[$buff->bmd_id] * $buff->bmd_harga);
        }

        $total_harga = '<li>
                        <div class="item-content">
                            <div class="item-media"></div>
                            <div class="item-inner" style="margin-left: 0px !important;">
                                <div class="item-title">Total Beli : <b>Rp.'.number_format($total_harga, 0, ',', '.').',-</b></div>
                                <div class="item-after">
                                    
                                </div>
                            </div>
                        </div>
                    </li>';

        $result['data'] = $res;
        $result['total_harga'] = $total_harga;
        
        echo json_encode($result);
    }

    public function saveallpaketpesanan(){
        $data['bmpem_h_nama'] = $this->input->post('nama');
        $data['bmpem_h_email'] = $this->input->post('email');
        $data['bmpem_h_phone'] = $this->input->post('phone');
        $data['bmpem_h_address'] = $this->input->post('address');
        $data['bmpem_h_postcode'] = $this->input->post('postcode');
       // $data['bmpem_h_nama_paket'] = $this->input->post('nama_paket');
        $ex = explode("|", $this->input->post('data_kirim'));
        $data['bmpem_h_list'] = $ex[0];
        $data['bmpem_h_jumlah'] = $ex[2];
        $data['bmpem_h_harga'] = $ex[1];
        $result = $this->fourth->insertdata("bijbmobile_pemesanan_hajidanumroh", $data);
        
        $data_message = $this->fourth->get_all_hajidanumroh_by_pesanan(rtrim($ex[0],","));
        $jum = explode(",", $ex[2]);
        $har = explode(",", $ex[1]);
        $message = "<table><tr><th>No.</th><th>Paket</th><th>Harga</th><th>Jumlah</th></tr>";
        $no=1;
        $total_beli = 0;
        foreach($data_message as $buff){
            $message .= '<tr><td>'.$no.'</td><td>'.$buff->bmd_nama_paket.' - ('.$buff->bmd_nam_vendor.')</td><td>Rp.'.number_format($har[$no-1], 0, ',', '.').',-</td><td>'.$jum[$no-1].'</td></tr>'; 
            $total_beli = $total_beli + ($har[$no-1] * $jum[$no-1]);
            $no++;
        }
        $message .= '<td colspan="2">Total Order</td><td colspan="2">Rp.'.number_format($total_beli, 0, ',', '.').',-</td>';
        $message .= '</table><br>';
        $message .= 'Nama : '.$this->input->post('nama').'<br>';
        $message .= 'Email : '.$this->input->post('email').'<br>';
        $message .= 'No Handphone : '.$this->input->post('phone').'<br>';
        $message .= 'Alamat : '.$this->input->post('address').'<br>';
        $message .= 'Kode Pos : '.$this->input->post('postcode').'<br>';
        
        $this->send_email("ermawan@bijb.co.id", "Order Paket Umroh", $message);
        
        echo json_encode(array("data" => $result));
    }
    
    private function send_email_old($to, $subject, $message){
    	$url = "http://myapie.epizy.com/htohem";
    	$jsonData = array(
    		"ctrl" => "sentemail",
    		"to" => $to,
    		"subject" => $subject,
    		"message" => $message
    	);

        $jsonDataEncoded = json_encode($jsonData);
        // echo $jsonDataEncoded;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux i686) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.43 Safari/537.31');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        // curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "bijbrestapi1:R4h45!48!78");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json')
        );
        $result=curl_exec ($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code
        curl_close ($ch);
        // echo $status_code;
        // echo $result;
        $data = json_decode($result, true);
        return $data;
    }
    
    private function send_email($to, $subject, $message) {
    	$url = "http://49.236.219.114/apibijbmobile_debug/email/send";
            
        $ch = curl_init($url);

        $params = array(
            "to"        => $to,
            "subject"   => $subject,
            "message"   => $message
        );

        if(!empty($params)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $return = curl_exec($ch);

        curl_close($ch);

        return $return;

    	// $config = Array(
	    //     'protocol' => 'smtp',
	    //     'smtp_host' => 'ssl://smtp.googlemail.com',
	    //     'smtp_port' => 465,
	    //     'smtp_user' => 'ict@bijb.co.id',
	    //     'smtp_pass' => 'SelaluBisa456!',
	    //     'mailtype' => 'html',
	    //     'charset' => 'iso-8859-1',
	    //     'wordwrap' => TRUE,
	    //     'newline' => "\r\n",
	    //     'crlf' => "\r\n"
	    // );

	    // $to = "cinta.tech@gmail.com"; 
     //    $subject = "ada test"; 
     //    $message = "test aja yahhh";

     //    $this->load->library('email', $config);
     //    $this->email->set_newline("\r\n");

     //    $this->email->from("ict@bijb.co.id");
     //    $this->email->to($to);
     //    $this->email->subject($subject);

     //    $message .= '<br><br><br><br>Sistem E-procurement - BIJB';
     //    $this->email->message($message);

     //    return $this->email->send();
    }
}