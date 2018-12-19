<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Second extends CI_Controller {

    protected $secretkey = "81ebf8d85ba905a33ff04416bb683135";
    protected $urlapitiketcom = "api-sandbox.tiket.com";

    public function __Construct() {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
        parent::__Construct();
        $this->load->model('Second_model', 'firstmdl');
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

    public function get_curl($url, $query) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_USERAGENT, "twh:[24804536];[PT Bandarudara Internasional Jawa Barat];");

        $server_output = curl_exec($ch);

        curl_close($ch);

        return $server_output;
    }

    public function get_token() {
        $url = "https://".$this->urlapitiketcom."/apiv1/payexpress?method=getToken&secretkey=" . $this->secretkey . "&output=json";
        $data = array();
        $gettoken = json_decode($this->get_curl($url, $data), true);

        $result['data'] = "";
        if (isset($gettoken['token'])) {
            $result['data'] = $gettoken['token'];
        }

        echo json_encode($result);
    }

    public function get_airport() {
        $url = "https://".$this->urlapitiketcom."/flight_api/all_airport?token=" . $this->input->post('ticket_token') . "&output=json&lang=en";
        $data = array();
        $getrute = json_decode($this->get_curl($url, $data), true);

        $result = "{}";
        $dd = array();
        if (isset($getrute['all_airport']['airport']) && count($getrute['all_airport']['airport'])) {
            foreach ($getrute['all_airport']['airport'] as $buff) {
                $dd[] = '{"id":"' . $buff['airport_code'] . '","name":"' . $buff['location_name'] . ' (' . $buff['airport_code'] . ')<br><span style=\"font-size: 9px; font-weight: bold;\">' . $buff['airport_name'] . '</span>"}';
            }

            $result = "[" . implode(",", $dd) . "]";
        }

        echo $result;
    }

    public function get_flight() {
        $data['d'] = $this->input->post('from');
        $data['a'] = $this->input->post('to');
        $data['date'] = date("Y-m-d", strtotime($this->input->post('date')));
        $data['ret_date'] = '';
        $data['adult'] = $this->input->post('adults');
        $data['child'] = $this->input->post('children');
        $data['infant'] = $this->input->post('infants');

        $url = "http://".$this->urlapitiketcom."/search/flight?" . http_build_query($data) . "&token=" . $this->input->post('ticket_token') . "&v=3&output=json&lang=en";

        $getflight = json_decode($this->get_curl($url, $data), true);

        $result['data'] = "<center><b style='font-size: 12px;'>No Result</b></center>";
        $dd = array();
        if (isset($getflight['departures']['result']) && count($getflight['departures']['result'])) {
            foreach ($getflight['departures']['result'] as $buff) {
                $dd[] = '<li>
                    <a href="10.html?flightid=' . $buff['flight_id'] . '" data-flight-id="' . $buff['flight_id'] . '" data-flight="' . $buff['flight_number'] . '" class="item-link item-content chooseflight">
                        <div class="item-media"><img src="' . $buff['image'] . '" width="80" style="border-radius: 0px;"/></div>
                        <div class="item-inner">
                            <div class="item-title-row">
                                <div class="item-title">' . $buff['airlines_name'] . '</div>
                                <div class="item-after" style="font-weight: bold; color: #ff7200;">IDR ' . number_format($buff['price_adult'], 0, ',', '.') . '</div>
                            </div>
                            <div class="item-title-row" style="background-image: none;">
                                <div class="item-title" style="font-size: 14px;">' . $buff['flight_number'] . ' (' . $buff['departure_city'] . ' - ' . $buff['arrival_city'] . ')</div>
                                <div class="item-after" style="color: #000;">' . date("H:i", strtotime($buff['departure_flight_date'])) . ' - ' . date("H:i", strtotime($buff['arrival_flight_date'])) . '</div>
                            </div>
                            <div class="item-text" style="font-size: 12px;"><b>Facility:</b> ' . $buff['check_in_baggage'] . ' Kg&nbsp;&nbsp;&nbsp;&nbsp;<b>Duration:</b> ' . $buff['duration'] . '<br><b>Info Route:</b> ' . $buff['stop'] . '</div>
                        </div>
                    </a>
                </li>';
            }

            $result['data'] = implode("", $dd);
        }

        echo json_encode($result);
    }

    public function get_flight_data() {
        $url = "http://".$this->urlapitiketcom."/flight_api/get_flight_data?flight_id=" . $this->input->post('flight_id') . "&token=" . $this->input->post('ticket_token') . "&date=" . date('Y-m-d', strtotime($this->input->post('date'))) . "&lang=en&output=json";
        $data = array();
        $getflight = json_decode($this->get_curl($url, $data), true);

        $lioncaptcha = $lionsessionid = "";
        if (isset($getflight['departures']['airlines_name']) && $getflight['departures']['airlines_name'] == "LION") {
            $url_lion = "http://".$this->urlapitiketcom."/flight_api/getLionCaptcha?token=adcefff7d50618ff205473975adf9d64&output=json";
            $get_lion_captcha = json_decode($this->get_curl($url_lion, $data), true);
            $lioncaptcha = $get_lion_captcha['lioncaptcha'];
            $lionsessionid = $get_lion_captcha['lionsessionid'];
        }

        $join = $join_require = $join_order = "";
        if (isset($getflight['departures']['flight_infos']['flight_info'])) {
            file_put_contents("/tmp/debug111.txt", print_r($getflight, true));
            foreach ($getflight['departures']['flight_infos']['flight_info'] as $buff) {
                if ($buff['transit_arrival_text_city']) {
                    $join_order .= '<li>
                                <div class="item-link item-content" style="padding-right: 16px; min-height: 10px;">
                                    <div class="item-inner" style="padding-right: 16px; min-height: 10px !important;padding-top: 0px;padding-bottom: 0px;background-color: #f7f7f7;border: solid 1px #dddddd;text-align: center;font-size: 12px; font-weight: 400;">
                                        <p style="font-size: 12px;font-weight: 500;color: #00599b;margin: 8px 0;">' . $buff['transit_arrival_text_city'] . ' for ' . $buff['transit_arrival_text_time'] . '</p>
                                    </div>
                                </div>
                            </li>';
                }
                $join_order .= '<li>
                        <div class="item-link item-content">
                            <div class="item-media"><img src="' . $buff['img_src'] . '" style="border-radius: 0px;max-width: 60px;" width="80"></div>
                            <div class="item-inner">
                                <div class="item-title-row" style="background-image: none;">
                                    <div class="item-title">' . $buff['airlines_name'] . ' (' . $buff['flight_number'] . ')</div>
                                    <div class="item-after" style="font-weight: bold; color: #ff7200;"></div>
                                </div>
                                <div class="item-title-row" style="background-image: none;">
                                    <div class="item-title" style="font-size: 14px; font-weight: bold;">' . $buff['departure_city_name'] . ' - ' . $buff['arrival_city_name'] . '</div>
                                </div>
                                <div class="item-title-row" style="background-image: none;">
                                    <div class="item-title" style="font-size: 14px;">' . $buff['duration_hour'] . ' ' . $buff['duration_minute'] . '</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li style="border-top: 1px solid #eaeaea;">
                        <div class="item-link item-content">
                            <div class="item-media" style="font-size: 14px;">Time :</div>
                            <div class="item-inner">
                                <div class="item-title-row" style="background-image: none;">
                                    <div class="item-title"><i class="material-icons" style="margin-top: 2px;float: left; margin-right: 5px;">flight_takeoff</i><span style="font-size: 12px; font-weight: 500;">' . $buff['simple_departure_time'] . ' - ' . $buff['departure_city_name'] . '</span></div>
                                    <div class="item-after" style="font-weight: bold; color: #ff7200;"></div>
                                </div>
                                <div class="item-title-row" style="background-image: none;">
                                    <div class="item-title"><i class="material-icons" style="margin-top: 2px;float: left; margin-right: 5px;">flight_land</i><span style="font-size: 12px; font-weight: 500;">' . $buff['simple_arrival_time'] . ' - ' . $buff['arrival_city_name'] . '</span></div>
                                    <div class="item-after" style="font-weight: bold; color: #ff7200;"></div>
                                </div>
                            </div>
                        </div>
                    </li>';

                
                if ($buff['transit_arrival_text_city']) {
                    $join .= '<li>
                                    <div class="item-link item-content" style="padding-right: 16px; min-height: 10px;">
                                        <div class="item-inner" style="padding-right: 16px; min-height: 10px !important;padding-top: 0px;padding-bottom: 0px;background-color: #f7f7f7;border: solid 1px #dddddd;text-align: center;font-size: 12px; font-weight: 400;">
                                            <p style="font-size: 12px;font-weight: 500;color: #00599b;margin: 8px 0;">' . $buff['transit_arrival_text_city'] . ' for ' . $buff['transit_arrival_text_time'] . '</p>
                                        </div>
                                    </div>
                                </li>';
                }
                $join .= '<li>
                    <div class="item-link item-content">
                        <div class="item-inner">
                            <div class="item-title-row" style="background-image: none;">
                                <div class="item-title" style="background-position: center; background-repeat: no-repeat; background-size: cover; width: 20%; min-height: 46px; background-image: url(' . $buff['img_src'] . ')"></div>
                                <div class="item-after" style="font-weight: bold; color: #ff7200;">' . $buff['airlines_name'] . ' (' . $buff['flight_number'] . ')</div>
                            </div>
                            <div class="row no-gutter">
                                <div class="col-40" style="font-size: 14px; font-weight: 500;">
                                    ' . $buff['simple_departure_time'] . '<br>
                                    ' . $buff['departure_city_name'] . '(' . $buff['departure_city'] . ')<br>
                                    <span style="font-weight: 300; font-size: 12px;">' . $buff['string_departure_date_short'] . '</span>
                                </div>
                                <div class="col-20">
                                    <div class="icon" style="text-align: center;">
                                        <i class="material-icons" style="font-size: 15px;">arrow_forward</i>
                                        <div class="icon-name" style="font-size: 11px;">' . $buff['duration_hour'] . ' ' . $buff['duration_minute'] . '</div>  
                                    </div>
                                </div>
                                <div class="col-40" style="font-size: 14px; font-weight: 500; text-align: right;">
                                    ' . $buff['simple_arrival_time'] . '<br>
                                    ' . $buff['arrival_city_name'] . '(' . $buff['arrival_city'] . ')<br>
                                    <span style="font-weight: 300; font-size: 12px;">' . $buff['string_arrival_date_short'] . '</span>
                                </div>
                            </div>
                            <div class="item-text" style="font-size: 12px;"><b>Facility:</b> ' . $buff['check_in_baggage'] . ' ' . $buff['check_in_baggage_unit'] . '&nbsp;&nbsp;&nbsp;&nbsp;</div>
                        </div>
                    </div>
                </li>';
            }
            $child = ($getflight['departures']['count_child']) ? " , " . (($getflight['departures']['count_child']>1) ? "Childs" : "Childs") . $getflight['departures']['count_child'] : "";
            $infant = ($getflight['departures']['count_infant']) ? " , " . (($getflight['departures']['count_infant']>1) ? "Infants" : "Infant") . $getflight['departures']['count_infant'] : "";
            $join_order .= '<div class="item-after" style="font-weight: 300; padding: 5px 16px; border-top: 1px solid #eaeaea;">' . $getflight['departures']['count_adult'] . ' ' . (($getflight['departures']['count_adult']>1) ? "Adults" : "Adult") . $child . $infant . '</div>';

            $result['data_order'] = $join_order;

            if ($join) {
                $join .= '<div class="item-after" style="font-weight: 400; color: #ff7200; padding: 5px 16px;">Price ' . $getflight['diagnostic']['currency'] . ' ' . number_format($getflight['departures']['price_adult'], 0, ',', '.') . '</div>
                            <div style="background-color: #eee; border-bottom: 3px solid #0b217f;">
                                <div style="font-weight: 400; color: #888; padding: 5px 16px 0px; font-size: 14px;">Total</div>
                                <div style="font-weight: 500; color: #ff7200; padding: 0px 16px 5px; font-size: 16px;">' . $getflight['diagnostic']['currency'] . ' ' . number_format($getflight['departures']['price_value'], 0, ',', '.') . '</div>
                            </div>';
            }

            if ($lioncaptcha && $lionsessionid) {
                $join_require .= '<input type="hidden" value="' . $lioncaptcha . '" placeholder="" name="lioncaptcha"/>';
                $join_require .= '<input type="hidden" value="' . $lionsessionid . '" placeholder="" name="lionsessionid"/>';
            }
            $join_require .= '<input type="hidden" value="' . $getflight['departures']['count_adult'] . '" placeholder="" name="adult"/>';
            $join_require .= '<input type="hidden" value="' . $getflight['departures']['count_child'] . '" placeholder="" name="child"/>';
            $join_require .= '<input type="hidden" value="' . $getflight['departures']['count_infant'] . '" placeholder="" name="infant"/>';

            $data_required = array();
            $resource_country = "";
            foreach ($getflight['required'] as $key => $value) {
                if ($value['category'] == "separator") {
                    $join_require .= '<div class="content-block-title">' . $value['FieldText'] . '</div>';
                    if($value['FieldText'] == "Adult Passenger 1"){
                        $join_require .= '<label onclick="checkbox_click()" class="label-checkbox item-content" style="min-height: 0;margin-top: -15px;margin-bottom: -8px;">
                                            <input id="checkbox-same" name="checkbox-same" value="0" type="checkbox">
                                            <div class="item-media" style="min-width: 0;"><i class="icon icon-form-checkbox"></i></div>
                                            <div class="item-inner" style="min-height: 0;font-size: 12px;">
                                              <div class="item-title">Same with contact</div>
                                            </div>
                                          </label>';
                    }
                }
                if ($value['type'] == "combobox") {
                    $resource = "";
                    if ($value['FieldText'] == "Title") {
                        foreach ($value['resource'] as $buff_resource) {
                            $resource .= '<option value="' . $buff_resource['id'] . '">' . $buff_resource['name'] . '</option>';
                        }
                    } else if ($value['resource'] == "https://".$this->urlapitiketcom."/general_api/listCountry") {
                        if ($resource_country) {
                            $resource = $resource_country;
                        } else {
                            $url = "https://".$this->urlapitiketcom."/general_api/listCountry?token=" . $this->input->post('ticket_token') . "&lang=en&output=json";
                            $getlistcountry = json_decode($this->get_curl($url, $data), true);
                            if (isset($getlistcountry['listCountry'])) {
                                foreach ($getlistcountry['listCountry'] as $buff_resource) {
                                    $resource_country .= '<option ' . ($buff_resource['country_id'] == "id" ? "selected" : "") . ' value="' . $buff_resource['country_id'] . '">' . $buff_resource['country_name'] . '</option>';
                                }
                            }
                            $resource = $resource_country;
                        }
                    } else if (strpos($key, 'checkinbaggage') !== false) {
                        foreach ($value['resource'] as $buff_resource) {
                            $resource .= '<option value="' . $buff_resource['id'] . '">' . $buff_resource['name'] . '</option>';
                        }
                    }
                    $join_require .= '<li>
                                    <div class="item-content">
                                        <div class="item-inner not-empty-state" style="margin-left: 0px; padding-bottom: 0px; margin-bottom: 0px; padding-top: 0px;"> 
                                            <div class="item-title floating-label">' . $value['FieldText'] . '</div>
                                            <div class="item-input not-empty-state">
                                                <select name="' . $key . '" class="not-empty-state">
                                                    ' . $resource . '
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </li>';
                }
                if ($value['type'] == "textbox") {
                    $inputType = "text";
                    if($key == "conPhone"){
                        $inputType = "tel";
                    }
                    $join_require .= '<li>
                                    <div class="item-content">
                                        <div class="item-inner" style="margin-left: 0px; padding-bottom: 0px; margin-bottom: 0px; padding-top: 0px;"> 
                                            <div class="item-title floating-label">' . $value['FieldText'] . '</div>
                                            <div class="item-input">
                                                <input data-label="' . $value['FieldText'] . '" style="border-bottom: 1px solid #eaeaea;" type="' . $inputType . '" value="" placeholder="" id="id' . $key . '" name="' . $key . '"/>
                                            </div>
                                        </div>
                                    </div>
                                </li>';
                }
                if ($value['type'] == "datetime") {
                    $adult_child_infant = "adult";
                    if (strpos($value['category'], 'child') !== false) {
                        $adult_child_infant = "child";
                    } else if (strpos($value['category'], 'infant') !== false) {
                        $adult_child_infant = "infant";
                    }
                    if ($value['FieldText'] == "Expiry") {
                        $adult_child_infant = "expiry";
                    }
                    if ($value['FieldText'] == "Issued Date") {
                        $adult_child_infant = "issued_date";
                    }
                    $join_require .= '<li>
                                        <div class="item-content">
                                            <div class="item-inner" style="margin-left: 0px; padding-bottom: 0px; margin-bottom: 0px; padding-top: 0px;"> 
                                                <div class="item-title floating-label">' . $value['FieldText'] . '</div>
                                                <div class="item-input">
                                                    <input onclick="datetime_' . $adult_child_infant . '(\'id' . $key . '\')" class="datetime_' . $adult_child_infant . '" style="border-bottom: 1px solid #eaeaea;" type="text" value="" placeholder="" id="id' . $key . '" name="' . $key . '" data-label="' . $value['FieldText'] . '"/>
                                                </div>
                                            </div>
                                        </div>
                                    </li>';
                }
            }
        }
        $result['data'] = $join;
        $result['data_require'] = $join_require;

        echo json_encode($result);
    }

    public function flight_add_order() {
        if(isset($this->input->post('booking_full')['conPhone'])){
            $this->input->post('booking_full')['conPhone'] = str_replace("+", "%2B", $this->input->post('booking_full')['conPhone']);
        }
        if(isset($this->input->post('booking_full')['conOtherPhone'])){
            $this->input->post('booking_full')['conOtherPhone'] = str_replace("+", "%2B", $this->input->post('booking_full')['conOtherPhone']);
        }
        $url = "https://".$this->urlapitiketcom."/order/add/flight?" . http_build_query($this->input->post('booking_full')) . "&lang=en&output=json";
        $data = array();
        $flightaddorder = json_decode($this->get_curl($url, $data), true);
        $result['data'] = null;
        if(isset($flightaddorder['diagnostic']['confirm'])){
            $result['data'] = $flightaddorder['diagnostic']['confirm'];
        } else {
            $result['error'] = $flightaddorder['diagnostic']['error_msgs']; 
        }

        echo json_encode($result);
    }

    public function flight_order() {
        // $url = "https://".$this->urlapitiketcom."/order?token=71cea4145ceb72e2010eb3efe36fc0f009b8b987&output=json";
        $url = "https://".$this->urlapitiketcom."/order?token=".$this->input->post('ticket_token')."&output=json";
        $data = array();
        $flightorder = json_decode($this->get_curl($url, $data), true);

        $join = "";
        if (isset($flightorder['myorder'])) {
            $result['date_flight'] = date("D, d M Y", strtotime($flightorder['myorder']['data'][0]['detail']['real_flight_date']));
            $result['real_date_flight'] = $flightorder['myorder']['data'][0]['detail']['real_flight_date'];
            
            $join .= '<div class="item-after" style="font-weight: 400; color: #ff7200; padding: 5px 16px; border-top: 1px solid #eaeaea;">Price ' . $flightorder['diagnostic']['currency'] . ' ' . number_format($flightorder['myorder']['data'][0]['detail']['price_adult'], 0, ',', '.') . '</div>';
            $passengers = "";
            $pi = 1;
            foreach ($flightorder['myorder']['data'][0]['detail']['passengers'] as $buff) {
                foreach($buff as $pass){
                    $passengers .= '<div class="item-title-row" style="background-image: none;">
                                        <div class="item-title" style="font-size: 14px;">'.$pi.'. '.$pass['first_name'].' '.$pass['last_name'].'</div>
                                        <div class="item-after"><i class="material-icons" style="margin-top: 2px;float: left; margin-right: 5px; font-size: 15px;">work</i> '.$pass['check_in_baggage'].'</div>
                                    </div>';
                    $pi++;
                }
            }
            $join .= '<li style="border-top: 2px solid #eaeaea;">
                        <div class="item-after" style="font-weight: 500; color: rgba(0, 0, 0, 0.54); padding: 5px 16px; border-top: 1px solid #eaeaea;">Passengers</div>
                        <div class="item-link item-content">
                            <div class="item-inner">
                                '.$passengers.'
                            </div>
                        </div>
                    </li>';
            $tax_charge = ($flightorder['myorder']['total_tax']) ? $flightorder['myorder']['total_tax'] : "FREE";
            $join .= '<li>
                        <div class="item-link item-content">
                            <div class="item-inner">
                                <div class="item-title-row" style="background-image: none;">
                                    <div class="item-title" style="font-weight: 500; color: rgba(0, 0, 0, 0.54);">Service Charge</div>
                                    <div class="item-after" style="font-weight: 500; color: #ff7200; padding: 0px 16px 5px; font-size: 16px;">'.$tax_charge.'</div>
                                </div>
                            </div>
                        </div>
                    </li>';
            $join .= '<li style="background-color: #eee;">
                        <div class="item-after" style="font-weight: 500; color: rgba(0, 0, 0, 0.54); padding: 5px 16px; border-top: 1px solid #eaeaea;">Total Payment</div>
                        <div class="item-link item-content">
                            <div class="item-inner">
                                <div class="item-title-row" style="background-image: none;">
                                    <div class="item-title" style="font-weight: 500; color: rgba(0, 0, 0, 0.54);">Total</div>
                                    <div class="item-after" style="font-weight: 500; color: #ff7200; padding: 0px 16px 5px; font-size: 16px;">'.$flightorder['diagnostic']['currency'].' '.number_format($flightorder['myorder']['total'], 0, ',', '.').'</div>
                                </div>
                            </div>
                        </div>
                    </li>';
            $result['checkout'] = $flightorder['checkout']."?token=".$flightorder['token'];
            $result['order_id'] = $flightorder['myorder']['order_id'];
            $result['order_detail_id'] = $flightorder['myorder']['data'][0]['order_detail_id'];
            $result['total_payment'] = $flightorder['diagnostic']['currency']." ".number_format($flightorder['myorder']['total'], 0, ',', '.');
        }
        $result['data'] = $join;

        echo json_encode($result);
    }

    public function checkout_flight_order() {
        $url = $this->input->post('url_checkout')."&output=json";
        $result['data'] = "Data Failed";
//        $url = "https://".$this->urlapitiketcom."/order/checkout/20604252/IDR?token=4a5ef3fb7627b9f2900f1aafa46b0f9f095f6aaf";
        $data = array();
        $flightorder = json_decode($this->get_curl($url, $data), true);
        $result['data'] = $flightorder['login_status'];
        echo json_encode($result);
    }

    public function delete_order() {
        $url = "https://".$this->urlapitiketcom."/order/delete_order?order_detail_id=".$this->input->post('order_detail_id')."&token=".$this->input->post('ticket_token')."&output=json";
        $data = array();
        $flightorder = json_decode($this->get_curl($url, $data), true);
        $result['data'] = $flightorder['updateStatus'];
        echo json_encode($result);
    }
    
    public function list_payment() {
        $result['data'] = "Data Failed";
        if($this->input->post('login_status')=="false"){
            $customer = $this->input->post('customer');
            $co_cust['salutation'] = $customer['conSalutation'];
            $co_cust['firstName'] = $customer['conFirstName'];
            $co_cust['lastName'] = $customer['conLastName'];
            $co_cust['emailAddress'] = $customer['conEmailAddress'];
            $co_cust['phone'] = str_replace("+", "%2B", $customer['conPhone']);
            $co_cust['saveContinue'] = 2;
            $co_cust['token'] = $this->input->post('ticket_token');
            $url_co_cust = "https://".$this->urlapitiketcom."/checkout/checkout_customer?".http_build_query($co_cust);
            $flightorder_co_cust = json_decode($this->get_curl($url_co_cust."&output=json", $data), true);
            if($flightorder_co_cust['login_status']){
                $url_payment = "https://".$this->urlapitiketcom."/checkout/checkout_payment?token=".$this->input->post('ticket_token') . "&currency=IDR";
                $flightorder_payment = json_decode($this->get_curl($url_payment."&output=json&lang=en", $data), true);
                $payment_list = "No Result";
                if(isset($flightorder_payment['available_payment']) && count($flightorder_payment['available_payment'])){
                    $payment_list = "";
                    foreach($flightorder_payment['available_payment'] as $buff){
                        $list_img = "";
                        foreach($buff['app_images'] as $bu){
                            $list_img .= '<div class="item-subtitle" style="width: 50px; height: 50px; margin-right: 15px; float: left; background: url('.$bu.'); background-repeat: no-repeat; background-size: 100%; background-position: center;">
                                            </div>';
                        }
                        if($buff['link'] == "#"){
                            $list_bpg = "";
                            foreach($buff['payment_group'] as $bpg){
                                $list_bpg .= '<li>
                                                <a href="#" onclick="topayment(\'' . $bpg['link'] . '\')">
                                                  <div class="item-content">
                                                    <div class="item-media" style="width: 50px; height: 50px; margin-right: 15px; float: left; background: url('.$bpg['images'].'); background-repeat: no-repeat; background-size: 100%; background-position: center;"></div>
                                                    <div class="item-inner"> 
                                                      <div class="item-title" style="white-space: normal;text-overflow: unset; color: black;">'.$bpg['text'].'<br><span style="font-size: 12px; color: #888888;">'.$bpg['desc'].'</span></div>
                                                    </div>
                                                  </div>
                                                </a>
                                               </li>';
                            }
                            $payment_list .= '<li class="accordion-item">
                                      <a href="#" class="item-link item-content">
                                        <div class="item-inner" style="border-bottom: 1px solid #eaeaea;">
                                            <div class="item-title-row" style="float: left; width: 100%;">
                                                <div class="item-title" data-link="' . $buff['link'] . '">' . $buff['text'] . '</div>
                                            </div>
                                            '.$list_img.'
                                        </div>
                                      </a>
                                      <div class="accordion-item-content" style="">
                                        <div class="list-block">
                                          <ul>
                                            '.$list_bpg.'
                                          </ul>
                                        </div>
                                      </div>
                                    </li>';
                        } else {
                            $payment_list .= '<li>
                                                <a href="#" onclick="topayment(\'' . $buff['link'] . '\')" class="item-link item-content">
                                                    <div class="item-inner" style="border-bottom: 1px solid #eaeaea;">
                                                        <div class="item-title-row" style="float: left; width: 100%;">
                                                            <div class="item-title" data-link="' . $buff['link'] . '">' . $buff['text'] . '</div>
                                                        </div>
                                                        '.$list_img.'
                                                    </div>
                                                </a>
                                            </li>';
                        }
                    }
                }
                $result['data'] = $payment_list;
            }
        }
        echo json_encode($result);
    }

    public function topayment() {
        $url = $this->input->post('link_pay') . "?token=" . $this->input->post('ticket_token') . "&currency=IDR&btn_booking=0&lang=en&output=json";
        $lp = substr($this->input->post('link_pay'), strrpos($this->input->post('link_pay'), '/') + 1);
        $lp = (int) str_replace("checkout_payment?payment_type=", "", $lp);
        if (in_array($lp, array(12,42))) {
            $url = $this->input->post('link_pay') . "&token=" . $this->input->post('ticket_token') . "&currency=IDR&btn_booking=0&lang=en&output=json";
        }
        $data = array();
        $topayment = json_decode($this->get_curl($url, $data), true);
        file_put_contents("/tmp/debug111.txt", print_r($url."-".$lp."-".in_array($lp, array(2,12,42)), true));
        $result["data"] = "No Result";
        $join = "";
        if (isset($topayment['result'])) {
        	$result['date'] = date("D, d M Y", strtotime($this->input->post('date')));
            $result['order_id'] = $topayment['result']['order_id'];
            $join .= '  <li style="background-color: #eee;">
                            <div class="item-after" style="font-weight: 500; color: rgba(0, 0, 0, 0.54); padding: 5px 16px; border-top: 1px solid #eaeaea;">Total Payment</div>
                            <div class="item-link item-content">
                                <div class="item-inner">
                                    <div class="item-title-row" style="background-image: none;">
                                        <div class="item-title" style="font-weight: 500; color: rgba(0, 0, 0, 0.54); font-size: 14px;">Unique Code</div>
                                        <div class="item-after" style="font-weight: 500; color: #ff7200; padding: 0px 16px 5px; font-size: 14px;">' . $topayment['diagnostic']['currency'] . ' ' . $topayment['result']['payment_charge'] . '</div>
                                    </div>
                                    <div class="item-title-row" style="background-image: none;">
                                        <div class="item-title" style="font-weight: 500; color: rgba(0, 0, 0, 0.54);">Total</div>
                                        <div class="item-after" style="font-weight: 500; color: #ff7200; padding: 0px 16px 5px; font-size: 16px;">' . $topayment['diagnostic']['currency'] . ' ' . number_format($topayment['result']['grand_total'], 0, ',', '.') . '</div>
                                    </div>
                                </div>
                            </div>
                        </li>';

            if (in_array($lp, array(2,12,42))) {
                $join .= '<div class="atm-confirmation-notes" style="margin-left: 10px;margin-right: 10px;font-size: 12px;font-weight: bold;padding-top: 10px;padding-bottom: 10px;">
                                <div class="title">Important Information</div>
                                <div class="description" style="padding-left: 25px;">
                                    <ul style="list-style: initial;padding-left: 0px;">
                                        <li>
                                            Make sure you transfer the amount of funds in accordance with the Total Final (<strong> ' . $topayment['diagnostic']['currency'] . ' ' . number_format($topayment['result']['grand_total'], 0, ',', '.') . ' </strong>) shown below.
                                        </li>
                                        <li>Input your order ID in the news column transfer and please keep your receipt</li>
                                        <li>The transaction will be canceled if you did not make the payment at a specified time period or the nominal transferred is incompatible with the total payment</li>
                                        <li>E-Ticket and Voucher will be sent via email after the payment has been completed</li>
                                    </ul>
                                </div>
                              </div>';
            } else if ($lp == 35) {
                $join .= '<div class="atm-confirmation-notes" style="margin-left: 10px;margin-right: 10px;font-size: 12px;font-weight: bold;padding-top: 10px;padding-bottom: 10px;">
                            <div class="title">Important Information</div>
                            <div class="description" style="padding-left: 25px;">
                                <ul style="list-style: initial;padding-left: 0px;">
                                    <li>This payment method is only for payment through ATM machines that have the ATM Bersama and Prima logo</li>
                                    <li>Some banks requires minimum amount for transfer by ATM, please ensure you meet this requirement on the ATM you are using.</li>
                                    <li>E-Ticket and Voucher will be sent via email after the payment has been completed</li>
                                </ul>
                            </div>
                          </div>
                          <p class="checkout-atm-agreement" style="font-size: 12px;font-weight: bold;margin: 5px 10px 12px;">
                              If you agree with Terms &amp; Conditions and privacy policies, please click the "Complete Booking" button to confirm the booking.                            
                          </p>';
            }
            $result['data'] = $join;
        }

        echo json_encode($result);
    }
    
    public function topayment_next() {
        $url = $this->input->post('link_pay') . "?token=" . $this->input->post('ticket_token') . "&currency=IDR&btn_booking=1&lang=en&output=json";
        $lp = substr($this->input->post('link_pay'), strrpos($this->input->post('link_pay'), '/') + 1);
        $lp = (int) str_replace("checkout_payment?payment_type=", "", $lp);
        if (in_array($lp, array(12,42))) {
            $url = $this->input->post('link_pay') . "&token=" . $this->input->post('ticket_token') . "&currency=IDR&btn_booking=1&lang=en&output=json";
        }
        $data = array();
        $topayment = json_decode($this->get_curl($url, $data), true);
        file_put_contents("/tmp/debug111.txt", print_r($topayment, true));
        $result["data"] = "No Result";
        $join = "";
        if (isset($topayment['result'])) {
            $join .= '<div class="content-block-title" style="font-weight: 500; color: rgba(0, 0, 0, 0.54); text-align: center;">
                            Order ID <span style="font-weight: bold;" id="place-tgl-booking">' . $topayment['orderId'] . '</span>
                        </div>';
            $join .= '<div class="list-block media-list">
                        <ul id="place-detail-booking-flight">
                            <div class="content-block-title" style="margin-bottom: 0px;">Departing Flight<br><span style="font-weight: 400; font-size: 12px;" id="place-tgl-booking">' . date("D, d M Y", strtotime($topayment['result']['orders'][0]['departure_time'])) . '</span></div>';
            foreach ($topayment['result']['orders'] as $buff) {
                $datetime1 = new DateTime($buff['departure_time']);
                $datetime2 = new DateTime($buff['arrival_time']);
                $interval = $datetime1->diff($datetime2);
                $between = $interval->format('%hh %im');
                $order_name = explode(" - ", $buff['order_name']);
                $join .= '<li>
                    <div class="item-link item-content">
                        <div class="item-inner">
                            <div class="item-title-row" style="background-image: none;">
                                <div class="item-title">' . $buff['order_name_detail'] . '</div>
                                <div class="item-after" style="font-weight: bold; color: #ff7200;"></div>
                            </div>
                            <div class="item-title-row" style="background-image: none;">
                                <div class="item-title" style="font-size: 14px; font-weight: bold; white-space: normal;">' . $buff['order_name'] . '</div>
                            </div>
                            <div class="item-title-row" style="background-image: none;">
                                <div class="item-title" style="font-size: 14px;">' . $between . '</div>
                            </div>
                        </div>
                    </div>
                </li>
                <li style="border-top: 1px solid #eaeaea;">
                    <div class="item-link item-content">
                        <div class="item-media" style="font-size: 14px;">Time :</div>
                        <div class="item-inner">
                            <div class="item-title-row" style="background-image: none;">
                                <div class="item-title"><i class="material-icons" style="margin-top: 2px;float: left; margin-right: 5px;">flight_takeoff</i><span style="font-size: 12px; font-weight: 500;">' . date("H:i", strtotime($buff['departure_time'])) . ' - ' . $order_name[0] . '</span></div>
                                <div class="item-after" style="font-weight: bold; color: #ff7200;"></div>
                            </div>
                            <div class="item-title-row" style="background-image: none;">
                                <div class="item-title"><i class="material-icons" style="margin-top: 2px;float: left; margin-right: 5px;">flight_land</i><span style="font-size: 12px; font-weight: 500;">' . date("H:i", strtotime($buff['arrival_time'])) . ' - ' . $order_name[1] . '</span></div>
                                <div class="item-after" style="font-weight: bold; color: #ff7200;"></div>
                            </div>
                        </div>
                    </div>
                </li>';
            }
            $join .= '  <li style="background-color: #eee;">
                            <div class="item-after" style="font-weight: 500; color: rgba(0, 0, 0, 0.54); padding: 5px 16px; border-top: 1px solid #eaeaea;">Total Payment</div>
                            <div class="item-link item-content">
                                <div class="item-inner">
                                    <div class="item-title-row" style="background-image: none;">
                                        <div class="item-title" style="font-weight: 500; color: rgba(0, 0, 0, 0.54); font-size: 14px;">Unique Code</div>
                                        <div class="item-after" style="font-weight: 500; color: #ff7200; padding: 0px 16px 5px; font-size: 14px;">' . $topayment['diagnostic']['currency'] . ' ' . $topayment['result']['payment_charge'] . '</div>
                                    </div>
                                    <div class="item-title-row" style="background-image: none;">
                                        <div class="item-title" style="font-weight: 500; color: rgba(0, 0, 0, 0.54);">Total</div>
                                        <div class="item-after" style="font-weight: 500; color: #ff7200; padding: 0px 16px 5px; font-size: 16px;">' . $topayment['diagnostic']['currency'] . ' ' . number_format($topayment['result']['grand_total'], 0, ',', '.') . '</div>
                                    </div>
                                </div>
                            </div>
                        </li>';

            if (in_array($lp, array(2,12,42))) {
                $banks = "";
                if (isset($topayment['banks'])) {
                    foreach ($topayment['banks'] as $buff) {
                        $banks .= '<div class="title" style="margin-left: 10px;font-size: 14px; font-weight: bold; margin-top: 8px;">' . $buff['Bank'] . '</div>
                                    <div class="description" style="margin-left: 10px;font-size: 14px; border-bottom: 1px solid #eaeaea; padding-bottom: 15px;">
                                        <ol>
                                            <li>Account Number : <b>' . $buff['No_Rekening'] . '</b></li>
                                            <li>Name : <b>' . $buff['Nama'] . '</b></li>
                                            <li>Branch : <b>' . $buff['Cabang'] . '</b></li>
                                            <li>
                                                <div class="item-title" style="float: left; background-position: center; background-repeat: no-repeat; background-size: contain; width: 20%; min-height: 30px; background-image: url(' . $buff['photo_1'] . ')"></div>
                                                <div class="item-title" style="float: left; background-position: center; background-repeat: no-repeat; background-size: contain; width: 20%; min-height: 30px; background-image: url(' . $buff['photo_2'] . ')"></div>
                                            </li>
                                        </ol>
                                    </div>';
                    }
                }
                
                $join .= '<li style="background-color: #eaeaea;padding-top: 10px;padding-bottom: 10px;">
                            <div class="atm-confirmation-notes mt10">
                                <div class="title" style="margin-left: 10px;font-size: 14px; font-weight: bold; text-decoration: underline;">Please Transfer your Payment to :</div>
                                ' . $banks . '
                            </div>
                          </li>';
            } else if ($lp == 35) {
                $steps = "";
                if (isset($topayment['steps'])) {
                    foreach ($topayment['steps'] as $buff) {
                        $stp_list = "";
                        foreach ($buff['step'] as $stp) {
                            $stp_list .= '<li>' . $stp . '</li>';
                        }
                        $steps .= '<div class="title" style="margin-left: 10px;font-size: 14px; font-weight: bold; margin-top: 8px;">' . $buff['name'] . '</div>
                                    <div class="description" style="margin-left: 10px;font-size: 14px; border-bottom: 1px solid #eaeaea; padding-bottom: 15px;">
                                        <ol>
                                            ' . $stp_list . '
                                        </ol>
                                    </div>';
                    }
                }

                $join .= '<li style="background-color: #eaeaea;padding-top: 10px;padding-bottom: 10px;">
                            <div class="atm-confirmation-notes mt10">
                                <div class="title" style="margin-left: 10px;font-size: 14px; font-weight: bold; text-decoration: underline;">Please Transfer your Payment to :</div>
                                ' . $steps . '
                            </div>
                          </li>';
            }
            $join .= '</ul>
                </div>';
            $join .= '<p class="buttons-row" style="margin-left: 15%; margin-right: 15%;">
                            <a href="#" onclick="done_complete()" class="button button-raised" style="background-color: #ff7200 !important; color: #ffffff !important; border-radius: 25px;">Done</a>
                        </p>';
            $result['data'] = $join;
        }

        echo json_encode($result);
    }

    public function send_email() {
        $ci = & get_instance();
        $ci->load->library('email');

        $config['protocol'] = "smtps";
        $config['smtp_host'] = "ssl://smtp.gmail.com";
        $config['smtp_port'] = "465";
        $config['smtp_user'] = "ifarsolo@gmail.com";
        $config['smtp_pass'] = "ifarsolo123";
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;
        $ci->email->initialize($config);

        $ci->email->from('no-reply@ezbuild.id', 'no-reply@ezbuild.id');

        $ci->email->to("wulandani.eka@gmail.com");
        $ci->email->subject("test email");
        $ci->email->message("ada");

        if ($ci->email->send()) {
            echo "sukses";
        } else {
            echo "gagal";
        }
    }

    public function send_email2() {
        $this->load->library('email');

        $this->email->from('cinta.tech@gmail.com', 'Fay Cloud Hiruka');
        $this->email->to('wulandani.eka@gmail.com');

        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');

        if ($this->email->send()) {
            echo "sukses";
        } else {
            echo "gagal";
        }
    }

    public function contoh() {
        $url = "http://soekarnohatta-airport.co.id/id/airport/flight_information";
        $data['dates'] = "2017-11-20";
        $data['leg'] = "A";
        $data['cat'] = "D";
        $flightorder = $this->get_curl($url, $data);
        file_put_contents("/tmp/debug111.txt", print_r($flightorder, true));
        echo $flightorder;
    }

}
