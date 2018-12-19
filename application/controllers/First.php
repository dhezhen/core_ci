<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class First extends CI_Controller {

    public function __Construct() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
        parent::__Construct();
        $this->load->model('First_model', 'firstmdl');
//        $api_key = array("NiKUjd4HUKKK892J7Y23");
//        $apikey = $this->input->post('api_key');
//        if (!in_array($apikey, $api_key)) {
//            echo "die";
//            die();
//        }
    }

    public function index() {
        return null;
    }
    
    public function get_posting_bijb_sejarah() {
        $ch = curl_init();
        $url_d = "https://bijb.co.id/sejarah/?json=1";
        curl_setopt($ch, CURLOPT_URL, $url_d);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        $rest = json_decode($server_output, true);
        $res['result'] = str_replace("\n","",str_replace('\"', '"', $rest['page']['content']));

        echo json_encode($res);
    }
    
    public function get_posting_bijb_visi() {
        $ch = curl_init();
        $url_d = "https://bijb.co.id/visi/?json=1";
        curl_setopt($ch, CURLOPT_URL, $url_d);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        $rest = json_decode($server_output, true);
        $res['result'] = str_replace("\n","",str_replace('\"', '"', $rest['page']['content']));

        echo json_encode($res);
    }
    
    public function get_posting_bijb_tata_kelola() {
        $ch = curl_init();
        $url_d = "https://bijb.co.id/tata-kelola/?json=1";
        curl_setopt($ch, CURLOPT_URL, $url_d);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        $rest = json_decode($server_output, true);
        $res['result'] = str_replace("\n","",str_replace('\"', '"', $rest['page']['content']));

        echo json_encode($res);
    }
        
    public function get_posting_bijb_investment_plan() {
        $ch = curl_init();
        $url_d = "https://bijb.co.id/investment-plan/?json=1";
        curl_setopt($ch, CURLOPT_URL, $url_d);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        $rest = json_decode($server_output, true);
        $res['result'] = str_replace("\n","",str_replace('\"', '"', $rest['page']['content']));

        echo json_encode($res);
    }
    
    public function get_posting_bijb_struktur_organisasi() {
        $ch = curl_init();
        $url_d = "http://bijb.co.id/struktur-organisasi/?json=1";
        curl_setopt($ch, CURLOPT_URL, $url_d);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        $rest = json_decode($server_output, true);
        $res['result'] = str_replace("\n","",str_replace('\"', '"', $rest['page']['content']));

        echo json_encode($res);
    }

    public function get_posting_bijb_direksi() {
        $ch = curl_init();
        $url_d = "http://bijb.co.id/direksi/?json=1";
        curl_setopt($ch, CURLOPT_URL, $url_d);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        $rest = json_decode($server_output, true);
        $res['result'] = str_replace("\n","",str_replace('\"', '"', $rest['page']['content']));

        echo json_encode($res);
    }
    
    public function get_posting_bijb_komisaris() {
        $ch = curl_init();
        $url_d = "http://bijb.co.id/komisaris/?json=1";
        curl_setopt($ch, CURLOPT_URL, $url_d);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        $rest = json_decode($server_output, true);
        $res['result'] = str_replace("\n","",str_replace('\"', '"', $rest['page']['content']));

        echo json_encode($res);
    }

    public function get_posting_bijb() {
        $ch = curl_init();

        // $url_d = "http://bijb.co.id/wp-json/wp/v2/posts?categories=2";
        $url_d = "http://bijb.co.id/?json=1";
        curl_setopt($ch, CURLOPT_URL, $url_d);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        $rest = json_decode($server_output, true);

        $data = "";
        foreach ($rest['posts'] as $buff) {
            $data .= '<div class="card ks-card-header-pic">
                        <div style="background-image:url(' . $buff['thumbnail_images']['medium_large']['url'] . ')" valign="bottom" class="card-header color-white no-border"></div>
                        <div class="card-content"> 
                            <div class="card-content-inner"> 
                                <p class="color-gray">Posted on ' . date("F, d Y", strtotime($buff['date'])) . '</p><h3>' . $buff['title'] . '</h3>
                                ' . $buff['content'] . '
                            </div>
                        </div>
                    </div>';
        }
        // foreach ($rest as $buff) {
        //     $data .= '<div class="card ks-card-header-pic">
        //                 <div style="background-image:url(' . $buff['better_featured_image']['source_url'] . ')" valign="bottom" class="card-header color-white no-border"></div>
        //                 <div class="card-content"> 
        //                     <div class="card-content-inner"> 
        //                         <p class="color-gray">Posted on ' . date("F, d Y", strtotime($buff['date'])) . '</p><h3>' . $buff['title']['rendered'] . '</h3>
        //                         ' . $buff['content']['rendered'] . '
        //                     </div>
        //                 </div>
        //             </div>';
        // }

        $res['result'] = $data;

        echo json_encode($res);
    }

    public function signup() {
        $result['data'] = array();

        $data_users['bmu_name'] = $this->input->post('name');
        $data_users['bmu_email'] = $this->input->post('email');
        $data_users['bmu_phone'] = $this->input->post('phone');
        $data_users['bmu_type'] = 1;
        $data_users['bmu_idtenant'] = "";
        $data_users['bmu_password'] = md5($this->input->post('password'));
        $data_users['bmu_datecreate'] = date("Y-m-d H:i:s");
        $data_users['bmu_dateupdate'] = date("Y-m-d H:i:s");
        $data_users['bmu_lastlogin'] = date("Y-m-d H:i:s");
        $data_users['bmu_sharefrom'] = $this->input->post('shareid');
        // $data_users['bmu_deviceid'] = $this->input->post('deviceid');

        $cek_email = $this->firstmdl->check_users("bmu_email", $data_users['bmu_email']);
        $cek_phone = $this->firstmdl->check_users("bmu_phone", $data_users['bmu_phone']);

        if ($cek_email) {
            $result['data'] = "Your email already used";
        } else if ($cek_phone) {
            $result['data'] = "Your phone already used";
        } else {
            $id = $this->firstmdl->insertdatareturnid("bijbmobile_users", $data_users);

            $data_shareid['bmu_shareid'] = strtoupper(substr($this->input->post('name'), 0, 2)) . $id;
            $upd_shareid = $this->firstmdl->updatedata("bijbmobile_users", $data_shareid, "bmu_id = " . $id);

            $data_users_device['bmu_id'] = $id;
            $data_users_device['bmud_lastlogin'] = date("Y-m-d H:i:s");

            $upd = $this->firstmdl->updatedata("bijbmobile_users_device", $data_users_device, "bmud_mobileid = '" . $this->input->post('mobileid') . "'");
            if ($upd == "sukses") {
                $data_point['bmu_id'] = $id;
                $data_point['bmp_point'] = 1;
                $data_point['bmp_description'] = "Form Registration";
                $data_point['bmp_ip'] = $this->getipaddress();
                $this->firstmdl->insertdata("bijbmobile_point", $data_point);

                $result['data'] = "Success";
            } else {
                $result['data'] = "Failed";
            }
        }

        echo json_encode($result);
    }

    public function signin() {
        $data_users['bmu_phone'] = $this->input->post('phone');
        $data_users['bmu_password'] = md5($this->input->post('password'));

        $result['data'] = "Failed";
        if (isset($_POST['mobileid']) && $this->input->post('mobileid')) {
            $cek_login = $this->firstmdl->check_login($data_users);
            if (count($cek_login)) {
                $data_users_upd["bmu_lastlogin"] = date("Y-m-d H:i:s");
                $upd1 = $this->firstmdl->updatedata("bijbmobile_users", $data_users_upd, "bmu_id = '" . $cek_login[0]->bmu_id . "'");

                $data_users_device['bmu_id'] = $cek_login[0]->bmu_id;
                $data_users_device['bmud_ip'] = $this->getipaddress();
                $data_users_device['bmud_lastlogin'] = date("Y-m-d H:i:s");

                $upd2 = $this->firstmdl->updatedata("bijbmobile_users_device", $data_users_device, "bmud_mobileid = '" . $this->input->post('mobileid') . "'");

                $result['data'] = "Success";
                $result['result'] = $cek_login;
            } else {
                $result['data'] = "Failed, your Mobile Number or Password incorrect";
            }
        } else {
            $result['data'] = "Update your apps to new version";
        }

        echo json_encode($result);
    }

    public function signinwithgoogle() {
        $result['data'] = array();

        $data_users['bmu_name'] = $this->input->post('name');
        $data_users['bmu_email'] = $this->input->post('email');
        $data_users['bmu_phone'] = $this->input->post('phone');
        $data_users['bmu_type'] = 1;
        $data_users['bmu_idtenant'] = "";
        $data_users['bmu_password'] = md5($this->input->post('password'));
        $data_users['bmu_with'] = 'google';
        $data_users['bmu_token'] = $this->input->post('token');
        $data_users['bmu_datecreate'] = date("Y-m-d H:i:s");
        $data_users['bmu_dateupdate'] = date("Y-m-d H:i:s");
        $data_users['bmu_lastlogin'] = date("Y-m-d H:i:s");
        // $data_users['bmu_deviceid'] = $this->input->post('deviceid');

        $cek_email = $this->firstmdl->check_users("bmu_email", $data_users['bmu_email']);

        if ($cek_email) {
            $cek_login = $this->firstmdl->get_users("bmu_email", $data_users['bmu_email']);

            $data_users_upd["bmu_lastlogin"] = date("Y-m-d H:i:s");
            $upd1 = $this->firstmdl->updatedata("bijbmobile_users", $data_users_upd, "bmu_id = '" . $cek_login[0]->bmu_id . "'");

            $data_users_device['bmu_id'] = $cek_login[0]->bmu_id;
            $data_users_device['bmud_ip'] = $this->getipaddress();
            $data_users_device['bmud_lastlogin'] = date("Y-m-d H:i:s");

            $upd2 = $this->firstmdl->updatedata("bijbmobile_users_device", $data_users_device, "bmud_mobileid = '" . $this->input->post('mobileid') . "'");

            $result['data'] = "Success";
            $result['result'] = $cek_login;
        } else {
            $id = $this->firstmdl->insertdatareturnid("bijbmobile_users", $data_users);

            $data_shareid['bmu_shareid'] = strtoupper(substr($this->input->post('name'), 0, 2)) . $id;
            $upd_shareid = $this->firstmdl->updatedata("bijbmobile_users", $data_shareid, "bmu_id = " . $id);

            $data_users_upd["bmu_lastlogin"] = date("Y-m-d H:i:s");
            $upd1 = $this->firstmdl->updatedata("bijbmobile_users", $data_users_upd, "bmu_id = '" . $id . "'");

            $data_users_device['bmu_id'] = $id;
            $data_users_device['bmud_ip'] = $this->getipaddress();
            $data_users_device['bmud_lastlogin'] = date("Y-m-d H:i:s");

            $upd = $this->firstmdl->updatedata("bijbmobile_users_device", $data_users_device, "bmud_mobileid = '" . $this->input->post('mobileid') . "'");
            if ($upd == "sukses") {
                $data_point['bmu_id'] = $id;
                $data_point['bmp_point'] = 1;
                $data_point['bmp_description'] = "Form Registration";
                $data_point['bmp_ip'] = $this->getipaddress();
                $this->firstmdl->insertdata("bijbmobile_point", $data_point);

                $cek_login = $this->firstmdl->get_users('bmu_id', $id);

                $result['data'] = "Success";
                $result['result'] = $cek_login;
            } else {
                $result['data'] = "Failed";
            }
        }

        echo json_encode($result);
    }

    public function signinwithfacebook() {
        $result['data'] = array();

        $data_users['bmu_name'] = $this->input->post('name');
        $data_users['bmu_email'] = $this->input->post('email');
        $data_users['bmu_phone'] = $this->input->post('phone');
        $data_users['bmu_type'] = 1;
        $data_users['bmu_idtenant'] = "";
        $data_users['bmu_password'] = md5($this->input->post('password'));
        $data_users['bmu_with'] = 'facebook';
        $data_users['bmu_token'] = $this->input->post('token');
        $data_users['bmu_datecreate'] = date("Y-m-d H:i:s");
        $data_users['bmu_dateupdate'] = date("Y-m-d H:i:s");
        $data_users['bmu_lastlogin'] = date("Y-m-d H:i:s");
        // $data_users['bmu_deviceid'] = $this->input->post('deviceid');

        $cek_email = $this->firstmdl->check_users("bmu_email", $data_users['bmu_email']);

        if ($cek_email) {
            $cek_login = $this->firstmdl->get_users("bmu_email", $data_users['bmu_email']);

            $data_users_upd["bmu_lastlogin"] = date("Y-m-d H:i:s");
            $upd1 = $this->firstmdl->updatedata("bijbmobile_users", $data_users_upd, "bmu_id = '" . $cek_login[0]->bmu_id . "'");

            $data_users_device['bmu_id'] = $cek_login[0]->bmu_id;
            $data_users_device['bmud_ip'] = $this->getipaddress();
            $data_users_device['bmud_lastlogin'] = date("Y-m-d H:i:s");

            $upd2 = $this->firstmdl->updatedata("bijbmobile_users_device", $data_users_device, "bmud_mobileid = '" . $this->input->post('mobileid') . "'");

            $result['data'] = "Success";
            $result['result'] = $cek_login;
        } else {
            $id = $this->firstmdl->insertdatareturnid("bijbmobile_users", $data_users);

            $data_shareid['bmu_shareid'] = strtoupper(substr($this->input->post('name'), 0, 2)) . $id;
            $upd_shareid = $this->firstmdl->updatedata("bijbmobile_users", $data_shareid, "bmu_id = " . $id);

            $data_users_upd["bmu_lastlogin"] = date("Y-m-d H:i:s");
            $upd1 = $this->firstmdl->updatedata("bijbmobile_users", $data_users_upd, "bmu_id = '" . $id . "'");

            $data_users_device['bmu_id'] = $id;
            $data_users_device['bmud_ip'] = $this->getipaddress();
            $data_users_device['bmud_lastlogin'] = date("Y-m-d H:i:s");

            $upd = $this->firstmdl->updatedata("bijbmobile_users_device", $data_users_device, "bmud_mobileid = '" . $this->input->post('mobileid') . "'");
            if ($upd == "sukses") {
                $data_point['bmu_id'] = $id;
                $data_point['bmp_point'] = 1;
                $data_point['bmp_description'] = "Form Registration";
                $data_point['bmp_ip'] = $this->getipaddress();
                $this->firstmdl->insertdata("bijbmobile_point", $data_point);

                $cek_login = $this->firstmdl->get_users('bmu_id', $id);

                $result['data'] = "Success";
                $result['result'] = $cek_login;
            } else {
                $result['data'] = "Failed";
            }
        }

        echo json_encode($result);
    }

    public function update_mobilenumber() {
        $result['data'] = array();

        $data_users_upd["bmu_phone"] = $this->input->post('phone');
        $data_users_upd["bmu_dateupdate"] = date("Y-m-d H:i:s");
        $upd1 = $this->firstmdl->updatedata("bijbmobile_users", $data_users_upd, "bmu_id = '" . $this->input->post('id') . "'");
        if ($upd1 == "sukses") {
            $result['data'] = "Success";
        } else {
            $result['data'] = "Failed";
        }

        echo json_encode($result);
    }

    public function update_email() {
        $result['data'] = array();

        $data_users_upd["bmu_email"] = $this->input->post('email');
        $data_users_upd["bmu_dateupdate"] = date("Y-m-d H:i:s");
        $upd1 = $this->firstmdl->updatedata("bijbmobile_users", $data_users_upd, "bmu_id = '" . $this->input->post('id') . "'");
        if ($upd1 == "sukses") {
            $result['data'] = "Success";
        } else {
            $result['data'] = "Failed";
        }

        echo json_encode($result);
    }

    public function update_deviceid() {
        $result['data'] = array();

        $data_asktoyou["bmaty_deviceid"] = $this->input->post('deviceid');
        $check_upd2 = $this->firstmdl->check_users_asktoyou("bmaty_deviceid", $this->input->post('deviceid'));
        $upd2 = "gagal";
        if ($check_upd2) {
            $upd2 = $this->firstmdl->updatedata("bijbmobile_asktoyou", $data_asktoyou, "bmaty_phone = '" . $this->input->post('phone') . "'");
        }

        $data_users_upd["bmu_deviceid"] = $this->input->post('deviceid');
        $upd1 = $this->firstmdl->updatedata("bijbmobile_users", $data_users_upd, "bmu_id = " . $this->input->post('id') . "");
        if ($upd2 == "sukses") {
            $result['data'] = "Success";
        } else {
            $result['data'] = "Failed";
        }

        echo json_encode($result);
    }

    public function send_registration() {
        $data['bmud_mobileid'] = $this->input->post('id');
        $data['bmud_ip'] = $this->getipaddress();
        if ($this->input->post('action') == "insert") {
            $this->firstmdl->insertdata("bijbmobile_users_device", $data);
        } else if ($this->input->post('action') == "update") {
            $this->firstmdl->updatedata("bijbmobile_users_device", $data, "m_user_id = '" . $this->input->post('id_old') . "'");
        } else if ($this->input->post('action') == "update_id") {
            $data_id['user_id'] = $this->input->post('id_key');
            $this->firstmdl->updatedata("bijbmobile_users_device", $data_id, "m_user_id = '" . $this->input->post('id') . "'");
        }
    }

    private function getipaddress() {
        $ip_address = "";
        if ($_SERVER['HTTP_CLIENT_IP']) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ($_SERVER['HTTP_X_FORWARDED_FOR']) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        return $ip_address;
    }

    public function update_point() {
        $result['data'] = "Failed";

        $data_point['bmu_id'] = $this->input->post('id');
        $data_point['bmp_point'] = 0.1;
        $data_point['bmp_description'] = "Share Apps";
        $data_point['bmp_ip'] = $this->getipaddress();
        $addpoint = $this->firstmdl->insertdata("bijbmobile_point", $data_point);
        if ($addpoint == "sukses") {
            $result['data'] = "Success";
        }


        echo json_encode($result);
    }

    public function getpoint() {
        $id = $this->input->post('id');
        $result['data'] = $this->firstmdl->get_point($id);

        echo json_encode($result);
    }

    public function getpoint_list() {
        $hasil = "";
        $id = $this->input->post('id');
        $data = $this->firstmdl->get_point_list($id);
        foreach ($data as $buff) {
            $hasil .= '<div class="chip">
                        <div class="chip-media bg-red" style="font-size: 12px;"><b>' . $buff->bmp_point . '</b></div>
                        <div class="chip-label">' . $buff->bmp_description . '</div>
                    </div>&nbsp;&nbsp;';
        }

        $result['data'] = $hasil;

        echo json_encode($result);
    }

    public function getpoint_new() {
        $id = $this->input->post('id');
        $result['data'] = $this->firstmdl->get_point_new($id);

        echo json_encode($result);
    }

    public function getpoint_list_new() {
        $hasil = "";
        $id = $this->input->post('id');
        $data = $this->firstmdl->get_point_list_new($id);
        foreach ($data as $buff) {
            $hasil .= '<div class="chip">
                        <div class="chip-media bg-red" style="font-size: 12px;"><b>10</b></div>
                        <div class="chip-label">Share Apps</div>
                    </div>&nbsp;&nbsp;';
        }

        $result['data'] = $hasil;

        echo json_encode($result);
    }

    public function getpointlistpemenang() {
        // $data = $this->firstmdl->get_point_list_pemenang();
        // $p1 = $p2 = $p3 = array();
        // $i=1;
        // foreach($data as $buff){
        //     if($i<=10){
        //         $p1[] = array($buff->bmu_name, ($buff->jml2*10));
        //     }
        //     if($i>10 && $i<=20){
        //         $p2[] = array($buff->bmu_name, ($buff->jml2*10));
        //     }
        //     if($i>20 && $i<=30){
        //         $p3[] = array($buff->bmu_name, ($buff->jml2*10));
        //     }
        //     $i++;
        // }
        // $data_p1 = "";
        // if(count($p1)){
        //     $p1_no = 1;
        //     foreach($p1 as $buff){
        //         $data_p1 .= '<tr>
        //                         <td class="numeric-cell">'.$p1_no.'</td>
        //                         <td class="label-cell">'.$buff[0].'</td>
        //                         <td class="numeric-cell">'.$buff[1].'</td>
        //                     </tr>';
        //         $p1_no++;
        //     }
        // }
        // $data_p2 = "";
        // if(count($p2)){
        //     $p2_no = 1;
        //     foreach($p2 as $buff){
        //         $data_p2 .= '<tr>
        //                         <td class="numeric-cell">'.$p2_no.'</td>
        //                         <td class="label-cell">'.$buff[0].'</td>
        //                         <td class="numeric-cell">'.$buff[1].'</td>
        //                     </tr>';
        //         $p2_no++;
        //     }
        // }
        // $data_p3 = "";
        // if(count($p3)){
        //     $p3_no = 1;
        //     foreach($p3 as $buff){
        //         $data_p3 .= '<tr>
        //                         <td class="numeric-cell">'.$p3_no.'</td>
        //                         <td class="label-cell">'.$buff[0].'</td>
        //                         <td class="numeric-cell">'.$buff[1].'</td>
        //                     </tr>';
        //         $p3_no++;
        //     }
        // }
        $data_p1 = '<tr>
                        <td class="numeric-cell" colspan="3" style="text-align: center !important; font-weight: bold;">Data sedang dalam validasi penentuan pemenang</td>
                    </tr>';
        $data_p2 = '<tr>
                        <td class="numeric-cell" colspan="3" style="text-align: center !important; font-weight: bold;">Data sedang dalam validasi penentuan pemenang</td>
                    </tr>';
        $data_p3 = '<tr>
                        <td class="numeric-cell" colspan="3" style="text-align: center !important; font-weight: bold;">Data sedang dalam validasi penentuan pemenang</td>
                    </tr>';

        $data_join["p1"] = $data_p1;
        $data_join["p2"] = $data_p2;
        $data_join["p3"] = $data_p3;

        echo json_encode($data_join);
    }

    public function getrute() {
        $search = $this->input->post('search');

        $getrute = $this->firstmdl->get_rute($search);
        $result = "{}";
        $dd = array();
        if (count($getrute)) {
            foreach ($getrute as $buff) {
                $dd[] = '{"id":"' . $buff['bmr_code'] . '","name":"' . $buff['bmr_city'] . ' (' . $buff['bmr_code'] . ')<br><span style=\"font-size: 9px; font-weight: bold;\">' . $buff['bmr_airport'] . '</span>"}';
            }

            $result = "[" . implode(",", $dd) . "]";
        }

        echo $result;
    }

    public function getflight() {
//        $url = "http://klikmbc.co.id/json/getflights-json";
//        $data['username'] = $this->config->item('uname_mbc');
//        $data['password'] = $this->config->item('upass_mbc');
//        $data['from'] = 'CGK';//$this->input->post('from');
//        $data['to'] = 'DPS';$this->input->post('to');
//        $data['date'] = '15-09-2017';//date('d-m-Y',strtotime($this->input->post('date']));
//
//        $result['hasil'] = $this->get_curl($url, $data);
//        
//        echo json_encode($result);
        $data = array(
            array(
                "flight" => "AirAsia",
                "flight_code" => "QZ-7682",
                "flight_image" => "https://da8hvrloj7e7d.cloudfront.net/imageResource/2015/12/17/1450349174390-23151020ad74cd0811255b320fcea754.png",
                "flight_from" => "CGK",
                "flight_to" => "SUB",
                "flight_route" => "CGK-SUB",
                "flight_date" => "2016-06-08",
                "flight_transit" => "Nonstop",
                "flight_infotransit" => "CGK - SUB (08:45 - 10:00)",
                "flight_datetime" => "08:45 - 10:00",
                "flight_price" => "350000",
                "flight_publishfare" => 0,
                "flight_baggage" => "15 Kg",
                "flight_facilities" => "-"
            ),
            array(
                "flight" => "Lion Air",
                "flight_code" => "JT-690",
                "flight_image" => "https://da8hvrloj7e7d.cloudfront.net/imageResource/2015/12/17/1450349861201-09ec8f298222a73d66e8e96aa3b918f0.png",
                "flight_from" => "CGK",
                "flight_to" => "SUB",
                "flight_route" => "CGK-SUB",
                "flight_date" => "2016-06-08",
                "flight_transit" => "Nonstop",
                "flight_infotransit" => "CGK-SUB (05:00 - 06:30) ",
                "flight_datetime" => "05:00 - 06:30",
                "flight_price" => "440000",
                "flight_publishfare" => "385000",
                "flight_baggage" => "20 Kg",
                "flight_facilities" => "-"
            ),
            array(
                "flight" => "Lion Air",
                "flight_code" => "JT-748",
                "flight_image" => "https://da8hvrloj7e7d.cloudfront.net/imageResource/2015/12/17/1450349861201-09ec8f298222a73d66e8e96aa3b918f0.png",
                "flight_from" => "CGK",
                "flight_to" => "SUB",
                "flight_route" => "CGK-SUB",
                "flight_date" => "2016-06-08",
                "flight_transit" => "Nonstop",
                "flight_infotransit" => "CGK-SUB (06:00 - 07:30) ",
                "flight_datetime" => "06:00 - 07:30",
                "flight_price" => "440000",
                "flight_publishfare" => "385000",
                "flight_baggage" => "20 Kg",
                "flight_facilities" => "-"
            )
        );
        echo json_encode($data);
    }

    public function updatelonglat() {
        $cek_device = $this->firstmdl->check_users_device("bmud_mobileid", $this->input->post('mobileid'));
        $data_result = array();
        if ($cek_device) {
            $data_users_device['longitude'] = $this->input->post('lon');
            $data_users_device['latitude'] = $this->input->post('lat');
            $upd = $this->firstmdl->updatedata("bijbmobile_users_device", $data_users_device, "bmud_mobileid = '" . $this->input->post('mobileid') . "'");
            $data_result['hasil'] = $upd;
        } else {
            if ($this->input->post('id')) {
                $data_inst['bmud_id'] = $this->input->post('id');
            }
            $data_inst['bmud_ip'] = $this->getipaddress();
            $data_inst['bmud_mobileid'] = $this->input->post('mobileid');
            $data_inst['longitude'] = $this->input->post('lon');
            $data_inst['latitude'] = $this->input->post('lat');
            $upd = $this->firstmdl->insertdata("bijbmobile_users_device", $data_inst);
            $data_result['hasil'] = $upd;
        }
        echo json_encode($data_result);
    }

    public function getflightinfo() {
        // $url = "https://api.flightradar24.com/common/v1/airport.json?code=cgk&plugin[]=&plugin-setting[schedule][mode]=&plugin-setting[schedule][timestamp]=".time()."&page=1&limit=100&token=";
        // $data["code"] = "cgk";
        // $data["plugin"][] = "";
        // $data["plugin-setting"]["schedule"]["mode"] = "";
        // $data["plugin-setting"]["schedule"]["timestamp"] = time();
        // $data["page"] = 1;
        // $data["limit"] = 100;
        // $data["token"] = "";
        $url = "https://www.flightstats.com/go/weblet";
        $data['guid'] = "14d6f78bd2189d22:5e327235:15900ac400d:41d6";
        $data['weblet'] = "status";
        $data['action'] = "AirportFlightStatus";
        $data['airportCode'] = "CGK";

        $result['hasil'] = $this->get_curl($url, $data);

        echo json_encode($result);
    }

    public function getflightinformation() {
        $code = $this->input->post('fcode');
        $flg = $this->input->post('fflg');

        $data_json = "";
        include_once('simplehtmldom/simple_html_dom.php');
        $dd = file_get_html('https://www.radarbox24.com/data/airports/' . strtoupper($code));

        //$ff = $dd->find('[id=template-airport-dep]');
        foreach ($dd->find('[id=template-airport-' . $flg . '] tr') as $tr) {
            $ff = str_get_html($tr->find('td', 3));
            $data_json .= '<tr>
                        <td class="label-cell">' . $tr->find('td', 0)->plaintext . '/<br>' . $tr->find('td', 1)->plaintext . '</td>
                        <td class="numeric-cell">
                            ' . $tr->find('td', 2)->plaintext . '<br>
                            <div class="row no-gutter">
                                <div class="col-20">' . str_replace('style="display:none;visibility:hidden;"', "", str_replace("data-cfsrc", "src", implode("", $ff->find("a img[data-cfsrc]")))) . '</div>
                                <div class="col-80"><span class="airl">' . $tr->find('td', 3)->plaintext . '</span></div>
                            </div>
                        </td>
                        <td class="numeric-cell">' . $tr->find('td', 4)->plaintext . '</td>
                        <td class="numeric-cell">' . $tr->find('td', 6)->plaintext . '</td>
                    </tr>';
        }

        echo $data_json;
    }

    public function getbaggageinformation() {
        $date = date("jM", strtotime($this->input->post('df')));
        $pnr = $this->input->post('pnr');
        $databgtracker = $this->input->post('databgtracker');
        $url_get = "http://202.78.200.148:8080/rest_bagtag/index.php";
        $jsonData1_get_r = array(
            'date' => $date, //'5feb',
            'pnr' => $pnr//'A7ZL6P'
        );

        $jsonDataEncoded_get = json_encode($jsonData1_get_r);
        $ch_get = curl_init($url_get);
        curl_setopt($ch_get, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch_get, CURLOPT_POSTFIELDS, $jsonDataEncoded_get);
        curl_setopt($ch_get, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_get, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'api_auth_key: f99aecef3d12e02dcbb6260bbdd35189c89e6e73')
        );
        $server_output = curl_exec($ch_get);

        curl_close($ch_get);

        $data_baggage = json_decode($server_output, true);
        $hasil = "";
        $simpan = array();
        if (count($data_baggage['result'])) {
            $rrr = $data_baggage['result'][0];
            $hasil = '<div class="card">
                            <div class="card-content"> 
                                <div class="card-content-inner">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td colspan="3"><b>Details Baggage</b></td>
                                            </tr>
                                            <tr>
                                                <td class="label-cell">Flight No</td>
                                                <td class="numeric-cell">:</td>
                                                <td class="numeric-cell"><b>' . $rrr['FlightNo'] . '</b></td>
                                            </tr>
                                            <tr>
                                                <td class="label-cell">Name</td>
                                                <td class="numeric-cell">:</td>
                                                <td class="numeric-cell"><b>' . $rrr['Nama'] . '</b></td>
                                            </tr>
                                            <tr>
                                                <td class="label-cell">PNR</td>
                                                <td class="numeric-cell">:</td>
                                                <td class="numeric-cell"><b>' . $rrr['PNR'] . '</b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>';
            $simpan = (object) array(array(trim($rrr['FlightNo']), trim($rrr['Nama']), trim($rrr['PNR']), trim($this->input->post('df'))));
            if ($databgtracker) {
                $simpan = (array) json_decode($databgtracker);
                $ffn = array();
                $tdk = 1;
                foreach ($simpan as $buff) {
                    $ffn[] = $buff;
                    if ($buff[0] == trim($rrr['FlightNo'])) {
                        $tdk = 0;
                    }
                }
                if ($tdk) {
                    $ffn[] = array(trim($rrr['FlightNo']), trim($rrr['Nama']), trim($rrr['PNR']), trim($this->input->post('df')));
                }
                $a = array_reverse($ffn);
                $ffn = array_slice($a, 0, 10);
                $simpan = (object) $ffn;
            }

            foreach ($data_baggage['result'] as $buff) {
                $prepare_time = "-";
                $prepare_day = "";
                $prepare_month = "";

                $makeup_time = "-";
                $makeup_day = "";
                $makeup_month = "";
                if ($buff['MAKE_UP_TIME']) {
                    $prepare_time = date('H:i', (strtotime($buff['MAKE_UP_TIME']) - (3 * 60)));
                    $prepare_day = date('d', (strtotime($buff['MAKE_UP_TIME']) - (3 * 60)));
                    $prepare_month = date('M', (strtotime($buff['MAKE_UP_TIME']) - (3 * 60)));

                    $makeup_time = date('H:i', (strtotime($buff['MAKE_UP_TIME'])));
                    $makeup_day = date('d', (strtotime($buff['MAKE_UP_TIME'])));
                    $makeup_month = date('M', (strtotime($buff['MAKE_UP_TIME'])));
                }

                $airc_time = "-";
                $airc_day = "";
                $airc_month = "";
                if ($buff['Airc_Scan_Time']) {
                    $airc_time = date('H:i', (strtotime($buff['Airc_Scan_Time'])));
                    $airc_day = date('d', (strtotime($buff['Airc_Scan_Time'])));
                    $airc_month = date('M', (strtotime($buff['Airc_Scan_Time'])));
                }

                $hasil .= '
                        <div class="card">
                            <div class="card-header">
                                <b>Bag No : ' . $buff['BagNo'] . '</b>
                            </div>
                            <div class="card-content"> 
                                <div class="card-content-inner">
                                    <div class="timeline">
                                        <div class="timeline-item timeline-item-left">
                                            <div class="timeline-item-date">' . $prepare_time . ' ' . $prepare_day . ' <small>' . $prepare_month . '</small></div>
                                            <div class="timeline-item-divider"></div>
                                            <div class="timeline-item-content">
                                                <div class="timeline-item-inner ' . ((!$makeup_day && !$airc_day) ? "ontimeselect" : "") . '">Prepare Your Baggage</div>
                                            </div>
                                        </div>
                                        <div class="timeline-item timeline-item-left">
                                            <div class="timeline-item-date">' . $makeup_time . ' ' . $makeup_day . ' <small>' . $makeup_month . '</small></div>
                                            <div class="timeline-item-divider"></div>
                                            <div class="timeline-item-content">
                                                <div class="timeline-item-inner ' . ((!$airc_day) ? "ontimeselect" : "") . '">on Makeup Area</div>
                                            </div>
                                        </div>
                                        <div class="timeline-item timeline-item-left">
                                            <div class="timeline-item-date">' . $airc_time . ' ' . $airc_day . ' <small>' . $airc_month . '</small></div>
                                            <div class="timeline-item-divider"></div>
                                            <div class="timeline-item-content">
                                                <div class="timeline-item-inner ' . (($airc_day) ? "ontimeselect" : "") . '">on Your Plane</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>';
            }
        } else {
            $hasil = '<div class="card">
                            <div class="card-content"> 
                                <div class="card-content-inner" style="font-size: 11px;"><center>There are no baggage available</center></div>
                            </div>
                        </div>';
        }

        echo json_encode(array('data' => $hasil, 'simpan' => $simpan));
    }

    public function getflightdata_fromflightnumber() {
        $fno = $this->input->post('fno');
        $ddp = date("Ymd", strtotime($this->input->post('ddp')));
        $usr = $this->input->post('user');
        // $url_airlines = "https://www.radarbox24.com/data/airlines/id";
        $url_departure = "https://www.radarbox24.com/data/flights/" . strtolower($fno);
        // $url_flight = "https://www.flightstats.com/v2/flight-tracker/GA/983/2018/4/10";
        // $url_flight = "https://www.trip.com/flights/status-info-GA240-20180410-CGK";//https://www.kayak.com/tracker/ID-ID6242/2018-04-10";

        $data_json = "";
        include_once('simplehtmldom/simple_html_dom.php');
        // $dd_airlines = file_get_html($url_airlines);
        // $airlines = $dd_airlines->find('section[class=header] h1');

        $dd_departure = file_get_html($url_departure);
        $dep1 = $dd_departure->find('.aporgia a', 0);
        $arr1 = $dd_departure->find('.apdstia a', 0);
        $data = '';
        if ($dep1) {
            $link_array = explode('/', $dep1->href);
            $departure = end($link_array);
            $depa = preg_replace('#(<small.*?>).*?(</small>)#', '$1$2', $dep1->innertext);
            $arri = preg_replace('#(<small.*?>).*?(</small>)#', '$1$2', $arr1->innertext);

            $url_flight = "https://www.trip.com/flights/status-info-" . $fno . "-" . $ddp . "-" . $departure;
            $dd_flight = file_get_html($url_flight);
            $fno = $dd_flight->find('.detail_hd .title h2');
            $fnm = $dd_flight->find('.detail_hd .title p');
            $fliht_t = $dd_flight->find('.fliht_t');
            $date_t = $dd_flight->find('.date_t');
            $timec = $dd_flight->find('.time_c');
            $fliht_time = $dd_flight->find('.fliht_time');
            $fly_title = $dd_flight->find('.fly_title');
            $gate = $dd_flight->find('.dynamic_info li strong');
            $fc = $dd_flight->find('.flight_change strong');

            $cek = $this->firstmdl->get_alert_flight_list($usr, $fno, date("Y-m-d", strtotime($this->input->post('ddp'))));
            $data_alert_flight['bmu_id'] = $usr;
            $data_alert_flight['bmaf_flight_date'] = date("Y-m-d", strtotime($this->input->post('ddp')));
            $data_alert_flight['bmaf_logo'] = substr($fno[0]->plaintext, 0, 2);
            $data_alert_flight['bmaf_flight_number'] = $fno[0]->plaintext;
            $data_alert_flight['bmaf_flight_name'] = $fnm[0]->plaintext;
            $data_alert_flight['bmaf_schedule_status'] = $fly_title[0]->plaintext;
            $data_alert_flight['bmaf_takeoff_desc'] = str_replace("Acutual", "Actual", $fliht_t[0]->plaintext);
            $data_alert_flight['bmaf_takeoff_date'] = $date_t[0]->plaintext;
            $data_alert_flight['bmaf_takeoff_time'] = $timec[0]->plaintext;
            $data_alert_flight['bmaf_takeoff_location'] = trim(strip_tags($depa));
            $data_alert_flight['bmaf_takeoff_code'] = $fc[0]->plaintext;
            $data_alert_flight['bmaf_landed_desc'] = $fliht_t[1]->plaintext;
            $data_alert_flight['bmaf_landed_date'] = $date_t[1]->plaintext;
            $data_alert_flight['bmaf_landed_time'] = $timec[2]->plaintext;
            $data_alert_flight['bmaf_landed_location'] = trim(strip_tags($arri));
            $data_alert_flight['bmaf_landed_code'] = $fc[1]->plaintext;
            $data_alert_flight['bmaf_departure_gate'] = $gate[0]->plaintext;
            $data_alert_flight['bmaf_checkin_counter_gate'] = $gate[1]->plaintext;
            $data_alert_flight['bmaf_baggage_gate'] = $gate[2]->plaintext;
            if (!count($cek)) {
                $this->firstmdl->insertdata("bijbmobile_alert_flight", $data_alert_flight);
            } else {
                unset($data_alert_flight['bmu_id']);
                unset($data_alert_flight['bmaf_flight_date']);
                unset($data_alert_flight['bmaf_flight_number']);
                $this->firstmdl->updatedata("bijbmobile_alert_flight", $data_alert_flight, "bmu_id = '" . $usr . "' AND bmaf_flight_number = '" . $fno[0]->plaintext . "' AND bmaf_flight_date = '" . date("Y-m-d", strtotime($this->input->post('ddp'))) . "'");
            }
            $data = "ada";
        }

        echo json_encode(array("data" => $data));
    }

    public function getflightalertlist() {
        $usr = $this->input->post('user');
        include_once('simplehtmldom/simple_html_dom.php');
        $data = "";
        $cek = $this->firstmdl->get_alert_flight_list($usr, "", "", "10");
        file_put_contents("test/debug111.txt", print_r($cek, true));
        foreach ($cek as $buff) {
            $buff = (array) $buff;
            $fno = $buff['bmaf_flight_number'];
            $ddp = date("Ymd", strtotime($buff['bmaf_flight_date']));
            $url_departure = "https://www.radarbox24.com/data/flights/" . strtolower($fno);
            // $url_flight = "https://www.flightstats.com/v2/flight-tracker/GA/983/2018/4/10";
            // $url_flight = "https://www.trip.com/flights/status-info-GA240-20180410-CGK";//https://www.kayak.com/tracker/ID-ID6242/2018-04-10";

            $data_json = "";
            // $dd_airlines = file_get_html($url_airlines);
            // $airlines = $dd_airlines->find('section[class=header] h1');

            $dd_departure = file_get_html($url_departure);
            $dep1 = $dd_departure->find('.aporgia a', 0);
            $arr1 = $dd_departure->find('.apdstia a', 0);
            if ($dep1) {
                $link_array = explode('/', $dep1->href);
                $departure = end($link_array);
                $depa = preg_replace('#(<small.*?>).*?(</small>)#', '$1$2', $dep1->innertext);
                $arri = preg_replace('#(<small.*?>).*?(</small>)#', '$1$2', $arr1->innertext);

                $url_flight = "https://www.trip.com/flights/status-info-" . $fno . "-" . $ddp . "-" . $departure;
                $dd_flight = file_get_html($url_flight);
                $fno = $dd_flight->find('.detail_hd .title h2');
                $fnm = $dd_flight->find('.detail_hd .title p');
                $fliht_t = $dd_flight->find('.fliht_t');
                $date_t = $dd_flight->find('.date_t');
                $timec = $dd_flight->find('.time_c');
                $fliht_time = $dd_flight->find('.fliht_time');
                $fly_title = $dd_flight->find('.fly_title');
                $gate = $dd_flight->find('.dynamic_info li strong');
                $fc = $dd_flight->find('.flight_change strong');

                $data_alert_flight['bmaf_logo'] = substr($fno[0]->plaintext, 0, 2);
                $data_alert_flight['bmaf_flight_name'] = $fnm[0]->plaintext;
                $data_alert_flight['bmaf_schedule_status'] = $fly_title[0]->plaintext;
                $data_alert_flight['bmaf_takeoff_desc'] = str_replace("Acutual", "Actual", $fliht_t[0]->plaintext);
                $data_alert_flight['bmaf_takeoff_date'] = $date_t[0]->plaintext;
                $data_alert_flight['bmaf_takeoff_time'] = $timec[0]->plaintext;
                $data_alert_flight['bmaf_takeoff_location'] = trim(strip_tags($depa));
                $data_alert_flight['bmaf_takeoff_code'] = $fc[0]->plaintext;
                $data_alert_flight['bmaf_landed_desc'] = $fliht_t[1]->plaintext;
                $data_alert_flight['bmaf_landed_date'] = $date_t[1]->plaintext;
                $data_alert_flight['bmaf_landed_time'] = $timec[2]->plaintext;
                $data_alert_flight['bmaf_landed_location'] = trim(strip_tags($arri));
                $data_alert_flight['bmaf_landed_code'] = $fc[1]->plaintext;
                $data_alert_flight['bmaf_departure_gate'] = $gate[0]->plaintext;
                $data_alert_flight['bmaf_checkin_counter_gate'] = $gate[1]->plaintext;
                $data_alert_flight['bmaf_baggage_gate'] = $gate[2]->plaintext;

                $this->firstmdl->updatedata("bijbmobile_alert_flight", $data_alert_flight, "bmu_id = '" . $usr . "' AND bmaf_flight_number = '" . $fno[0]->plaintext . "' AND bmaf_flight_date = '" . date("Y-m-d", strtotime($this->input->post('ddp'))) . "'");

                $data .= '<li>
                        <div class="card">
                            <div class="card-content"> 
                                <div class="item-link item-content">
                                    <div class="item-inner">
                                        <a href="#" style="position: absolute; right: 13px; line-height: 0; background-color: #f44336; color: #fff; border-radius: 4px;" onclick="delete_alert(' . $buff['bmaf_id'] . ')"><i class="material-icons">clear</i></a>
                                        <div class="row no-gutter">
                                            <div class="col-100">
                                                <div class="col-30" style="background-position: center; background-repeat: no-repeat; background-size: cover; width: 20%; min-height: 46px; background-image: url(https://cdn.radarbox24.com/airline_logos/m/' . substr($fno[0]->plaintext, 0, 2) . '.png); float: left;">

                                                </div>
                                                <div class="col-70" style="font-weight: bold; color: #ff7200; float: right;">
                                                    ' . $fno[0]->plaintext . '<br><span style="color: #999;font-size: 12px !important;font-weight: 400;">' . $fnm[0]->plaintext . '</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row no-gutter" style="border-bottom: dashed #ccc 1px; margin-bottom: 3px;">
                                            <div class="col-95" style="font-size: 14px; font-weight: 500; color: #6c6; text-align: center; position: absolute">
                                                ' . $fly_title[0]->plaintext . '
                                            </div>
                                            <div class="col-45" style="font-size: 14px; font-weight: 500;">
                                                <span style="font-weight: 300; font-size: 12px; font-weight: 400;">' . str_replace("Acutual", "Actual", $fliht_t[0]->plaintext) . '</span><br>
                                                <span style="font-weight: 300; font-size: 12px;">' . $date_t[0]->plaintext . '</span><br>
                                                <span style="font-weight: 500; font-size: 20px;">' . $timec[0]->plaintext . '</span><br>
                                                ' . trim(strip_tags($depa)) . '(' . $fc[0]->plaintext . ')<br>
                                                <span style="font-weight: 300; font-size: 12px;">' . $fliht_time[0]->plaintext . '</span>
                                            </div>
                                            <div class="col-10">
                                                <div class="icon" style="text-align: center; margin-top: 20px;">
                                                    <i class="material-icons" style="font-size: 15px; transform: rotate(90deg);">flight</i>
                                                    <div class="icon-name" style="font-size: 11px;"></div>  
                                                </div>
                                            </div>
                                            <div class="col-45" style="font-size: 14px; font-weight: 500; text-align: right;">
                                                <span style="font-weight: 300; font-size: 12px; font-weight: 400;">' . $fliht_t[1]->plaintext . '</span><br>
                                                <span style="font-weight: 300; font-size: 12px;">' . $date_t[1]->plaintext . '</span><br>
                                                <span style="font-weight: 500; font-size: 20px;">' . $timec[2]->plaintext . '</span><br>
                                                ' . trim(strip_tags($arri)) . '(' . $fc[1]->plaintext . ')<br>
                                                <span style="font-weight: 300; font-size: 12px;">' . $fliht_time[1]->plaintext . '</span>
                                            </div>
                                        </div>
                                        <div class="row no-gutter">
                                            <div class="col-33" style="text-align: center; border-right: 1px solid #ccc;">
                                                <span style="font-weight: 300; font-size: 12px; font-weight: 400;">Depature Gate</span><br>
                                                <span style="font-weight: 500; font-size: 20px;">' . $gate[0]->plaintext . '</span>
                                            </div>
                                            <div class="col-33" style="text-align: center;">
                                                <span style="font-weight: 300; font-size: 12px; font-weight: 400;">Check-in Counter</span><br>
                                                <span style="font-weight: 500; font-size: 20px;">' . $gate[1]->plaintext . '</span>
                                            </div>
                                            <div class="col-33" style="text-align: center; border-left: 1px solid #ccc;">
                                                <span style="font-weight: 300; font-size: 12px; font-weight: 400;">Baggage</span><br>
                                                <span style="font-weight: 500; font-size: 20px;">' . $gate[2]->plaintext . '</span>
                                            </div>
                                        </div>
                                        <div class="row" style="border-top: dashed #ccc 1px; margin-top: 3px; padding-top: 5px;">
                                            <div class="col-50">
                                                <a href="15_1.html" class="button button-fill color-green"><i class="material-icons" style="float: left; margin-top: 5px;">map</i> Road Guide</a>
                                            </div>
                                            <div class="col-50">
                                                <a href="14.html" class="button button-fill color-indigo"><i class="material-icons" style="float: left; margin-top: 5px;">directions_bus</i> Transport</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>';
            }
        }

        echo json_encode(array("data" => $data));
    }

    public function delete_flight_alert() {
        $id = $this->input->post("id_alert");
        $usr = $this->input->post("user");
        $hasil = $this->firstmdl->updatedata("bijbmobile_alert_flight", array("bmaf_status" => "0"), "bmaf_id = " . $id . " AND bmu_id = '" . $usr . "'");

        echo json_encode(array("data" => $hasil));
    }

    public function insert_parking() {
        $data_first = $this->input->post();

        $data = array();
        $data['bmu_id'] = $data_first['bmu_id'];
        $data['bmp_plate_number'] = $data_first['plate_number'];
        $data['bmp_date_parking'] = date("Y-m-d H:i:s", strtotime($data_first['date_parking']));
        $data['bmp_airport'] = 1;
        $data['bmp_vehicle_type'] = $data_first['vehicle_type'];
        $data['bmp_parking_type'] = $data_first['parking_type'];
        $data['bmp_qrcode_id'] = $data_first['plate_number'];

        $hasil = $this->firstmdl->insertdata("bijbmobile_parking", $data);

        return $hasil;
    }

    public function check_deviceid() {
        $data = $this->input->post();
        $cek_deviceid = 1; //$this->firstmdl->check_users_asktoyou("bmaty_deviceid", $data['deviceid']);

        $date_now = date("Y-m-d H:i:s");
        $result = "no";
        if ($date_now <= "2018-05-23 23:59:59") {
            $result = "yes";
        }

        echo json_encode(array("data" => $cek_deviceid, "check_date" => $result));
    }

    public function check_deviceid_user() {
        $data = $this->input->post();
        $cek_deviceid = $this->firstmdl->check_users("bmu_deviceid", $data['deviceid']);

        echo json_encode(array("data" => $cek_deviceid));
    }

    public function submit_asktoyou() {
        $data_first = $this->input->post();

        $data = array();

        $data['bmu_id'] = $data_first['user'];
        $data['bmaty_name'] = $data_first['aty_name'];
        $data['bmaty_email'] = $data_first['aty_email'];
        $data['bmaty_phone'] = $data_first['aty_phone'];
        $data['bmaty_city'] = $data_first['aty_city'];
        $data['bmaty_when'] = $data_first['aty_when'];
        $data['bmaty_where'] = $data_first['aty_where'];
        $data['bmaty_what'] = $data_first['aty_what'];
        $data['bmaty_doyou'] = $data_first['aty_doyou'];
        $data['bmaty_howmany'] = $data_first['aty_howmany'];
        $data['bmaty_deviceid'] = $data_first['aty_deviceid'];
        file_put_contents("test/debug111.txt", print_r($data, true));
        $cek_email = $this->firstmdl->check_users_asktoyou("bmaty_email", $data['bmaty_email']);
        $cek_phone = $this->firstmdl->check_users_asktoyou("bmaty_phone", $data['bmaty_phone']);

        $hasil = "Failed to submit your data";
        if ($cek_email) {
            $hasil = "Your email already used";
        } else if ($cek_phone) {
            $hasil = "Your phone already used";
        } else {
            $hasil = $this->firstmdl->insertdata("bijbmobile_asktoyou", $data);
        }

        echo json_encode(array("data" => $hasil));
    }

    public function get_youtube_bijb() {
        $api_key = 'AIzaSyCXSRvIjzvmtnWe5ztGYruaY_tLykJ_w2Q';

        $playlist_id = 'playlist_id';

        $api_url = 'https://www.googleapis.com/youtube/v3/activities?part=snippet%2CcontentDetails&channelId=UCRwvCf-dJNYNgNbMbYBqBIQ&maxResults=25&key=' . $api_key;
        $playlist = json_decode(file_get_contents($api_url));
        $p = 0;
        $hasil = "";
        foreach ($playlist->items as $buff) {
            $title = $buff->snippet->title;
            $description = $buff->snippet->description;
            $thumbnail = $buff->snippet->thumbnails->medium->url;
            $urlvideo = $buff->contentDetails->upload->videoId;

            // if($p%2==0){
            //     $hasil .= '<div class="row no-gutter">';
            // }

            $hasil .= '
                        <!--<div class="col-50">-->
                            <a href="#" onclick="playyoutubecinema(\'' . $urlvideo . '\')">
                                <div class="card ks-card-header-pic">
                                    <div style="background-image:url(' . $thumbnail . ')" valign="bottom" class="card-header color-white no-border"></div>
                                    <div class="card-content"> 
                                        <div class="card-content-inner"> 
                                            <p style="font-size: 12px;"><b>' . $title . '</b></p>
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="card ks-card-header-pic">
                                    <div style="background-image:url(' . $thumbnail . ');background-repeat: no-repeat;background-size: cover;height: 23vw !important;" valign="bottom" class="card-header color-white no-border"></div>
                                    ' . $title . '
                                </div>-->
                            </a>
                        <!--</div>-->
                    ';

            // if($p%2==1){
            //     $hasil .= '</div>';
            // }
            $p++;
        }
        echo json_encode(array("data" => $hasil));
    }

    public function getweatherinfo() {
        $data_json = "";
        include_once('simplehtmldom/simple_html_dom.php');
        $dd = file_get_html('https://forecast7.com/en/n6d78108d29/majalengka-regency/');

        $data_json['icon'] = trim($dd->find('.icon svg', 0));
        $data_json['derajat'] = str_replace("&deg;C", "", trim($dd->find('.current-conditions .temp', 0)->plaintext));
        $data_json['ket'] = trim($dd->find('.current-conditions .summary', 0)->plaintext);
        $rain = $dd->find('.more-details .row span', 6)->plaintext;
        $data_json['rain'] = str_replace("chance of rain:", "", $rain) . " Rain";

        echo json_encode($data_json);
    }

    public function sendotp() {
        $url = "https://numverify.com/php_helper_scripts/phone_api.php?secret_key=ed17a5becbf3c49ce9903971de7dd4b5&number=6281390334321";
        $data = $this->get_curl($url, array());
        file_put_contents("test/debug111.txt", print_r($data, true));
    }

    public function checkdate_mobile() {
        $date_now = date("Y-m-d H:i:s");
        $result = "no";
        if ($date_now <= "2018-05-23 23:59:59") {
            $result = "yes";
        }

        echo json_encode(array("data" => $result));
    }

    public function get_curl($url, $query) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        return $server_output;
    }

}
