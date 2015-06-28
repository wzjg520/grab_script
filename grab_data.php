#!/usr/bin/env php
<?php
require 'simple_html_dom.php';


if(count($argv) != 2){
    $city_key = 'shanghai';
}else{
    $city_key = $argv[1];
}

$config_city = array(
    'shanghai' => 1,
    'beijing' => 2
);

if(!array_key_exists($city_key, $config_city)){
    echo "[error] {$city_key} is not in the config_city.\n";
    exit;
}


//curl 请求数据
function get_data($url, $ip_index){
    $proxy = array(
        '0' => '60.16.210.118:80',
        '1' => '183.62.60.100:80',
        '2' => '58.215.185.46:82',
        '3' => '223.4.21.184:80',
        '4' => '61.53.143.179:80',
        '5' => '42.121.105.155:8888',
        '6' => '115.29.184.17:82',
        '7' => '183.131.144.204:443',
        '8' => '121.199.30.110:82',
        '9' => '113.207.130.166:80',
        '10' => '124.202.181.226:8118',
        '11' => '116.236.216.116:8080',
        '12' => '114.255.183.173:8080',
        '13' => '202.108.50.75:80',
        '14' => '122.96.59.106:82',
        '15' => '122.96.59.106:83',
        '16' => '1.202.74.121:8118',
        '17' => '114.255.183.164:8080',
        '18' => '111.13.136.59:843',
        '19' => '122.96.59.106:843',
        '20' => '101.71.27.120:80',
        '21' => '122.96.59.106:81',
        '22' => '111.1.36.6:80',
        '23' => '114.255.183.174:8080',
        '24' => '120.198.243.111:80',
        '25' => '218.240.156.82:80',
        '26' => '61.184.192.42:80',
        '27' => '119.6.144.74:83',
        '28' => '119.6.144.74:843',
        '29' => '124.202.217.134:8118',
        '30' => '221.10.102.203:83',
        '31' => '119.6.144.74:82',
        '32' => '119.6.144.74:80',
        '33' => '58.252.72.179:3128',
        '34' => '60.24.122.236:8118',
        '35' => '203.192.10.66:80',
        '36' => '221.10.102.203:81',
        '37' => '211.141.130.96:8118',
        '38' => '124.88.67.13:843',
        '39' => '119.6.144.74:81',
        '40' => '222.33.41.228:80',
        '41' => '221.10.102.203:843',
        '42' => '111.7.129.133:80',
        '43' => '124.88.67.13:83',
        '44' => '61.156.3.166:80',
        '45' => '218.204.140.212:8001',
        '46' => '116.236.203.238:8080',
        '47' => '122.96.59.106:80',
        '48' => '182.118.23.7:8081',
        '49' => '222.45.194.122:8118',
        '50' => '123.171.119.52:80',
        '51' => '183.22.132.149:8090',
        '52' => '49.90.21.103:80',
        '53' => '218.86.138.91:8090',
        '54' => '49.88.78.250:8090',
        '55' => '183.246.69.39:808',
        '56' => '115.228.50.212:3128',
        '57' => '123.163.125.32:9000',
        '58' => '115.228.52.135:3128',
        '59' => '223.167.223.75:8090',
        '60' => '220.175.254.244:9000',
        '61' => '122.232.230.12:3128',
        '62' => '218.0.177.134:8090',
        '63' => '112.64.28.146:8090',
        '64' => '219.129.159.156:8090',
        '65' => '221.235.80.9:8090',
        '66' => '124.200.38.46:8181',
        '67' => '180.166.56.47:80',
        '68' => '114.255.183.173:8080',
        '69' => '124.202.169.134:8181',
        '70' => '122.96.59.102:81',
        '71' => '115.159.5.247:80',
        '72' => '218.89.170.114:8888',
        '73' => '61.184.192.42:80',
        '74' => '182.254.153.54:80',
        '75' => '218.204.140.105:8118',
        '76' => '120.132.52.88:8888',
        '77' => '116.228.80.186:8888',
        '78' => '120.203.159.18:8118',
        '79' => '122.70.178.242:8118',
        '80' => '121.40.50.8:8090',
        '81' => '122.96.59.107:81',
        '82' => '112.195.80.159：80',
        '83' => '101.71.27.120:80',
        '84' => '61.234.249.126:8118',
        '85' => '111.161.65.83:80',
        '86' => '221.10.102.203:81',
        '87' => '124.202.169.50:8118',
        '88' => '101.4.136.66:80',
        '89' => '114.202.183.170',
        '90' => '124.202.176.194:8118',
        '91' => '218.204.143.83:8118',
        '92' => '124.202.178.182:8118',
        '93' => '115.29.77.207:8888',
        '94' => '211.141.130.114:8118',
        '95' => '116.27.60.126:8090',
        '96' => '115.208.28.117:8090',
        '97' => '223.150.76.230:9000',
        '98' => '221.235.83.103:8090',
        '99' => '123.148.162.99:8090',
        '100' => '115.228.52.142:3128'       
    );

    $ip_long = array(  
        array('607649792', '608174079'), //36.56.0.0-36.63.255.255  
        array('1038614528', '1039007743'), //61.232.0.0-61.237.255.255  
        array('1783627776', '1784676351'), //106.80.0.0-106.95.255.255  
        array('2035023872', '2035154943'), //121.76.0.0-121.77.255.255  
        array('2078801920', '2079064063'), //123.232.0.0-123.235.255.255  
        array('-1950089216', '-1948778497'), //139.196.0.0-139.215.255.255  
        array('-1425539072', '-1425014785'), //171.8.0.0-171.15.255.255  
        array('-1236271104', '-1235419137'), //182.80.0.0-182.92.255.255  
        array('-770113536', '-768606209'), //210.25.0.0-210.47.255.255  
        array('-569376768', '-564133889'), //222.16.0.0-222.95.255.255  
    );
    
    $rand_key = mt_rand(0, 9); 
    $ip= long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));//随机生成国内某个ip

    $header = array(   
        "CLIENT-IP:{$ip}",   
        "X-FORWARDED-FOR:{$ip}",
    );

    $rand_ip = $proxy[$ip_index];
    
    $cookie_jar = tempnam('./cookies','cookie');
       
    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_HEADER  => 0,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_ENCODING =>  'gzip,deflate',
        CURLOPT_FOLLOWLOCATION => 1, //302
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.9; rv:33.0) Gecko/20100101 Firefox/33.0',
        CURLOPT_PROXY => 'http://'.$rand_ip,
        CURLOPT_REFERER => "http://www.baidu.com",
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_COOKIEJAR => $cookie_jar,
        CURLOPT_TIMEOUT => 60
    );
    
    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $html = curl_exec($ch);
    curl_close($ch);
    unset($ch);
    return $html;
    
}

function init_grap(){

    global $city_key;
    global $config_city;

    if(!file_exists('./data/query_'.$city_key.'.json')){

        $try_count = 100; //请求到非法数据时，重复100次                 
        while($try_count >= 0){
            $start = time();
            $req_url = 'http://www.dianping.com/search/category/'.$config_city[$city_key].'/45';

            $tmp = get_data($req_url, rand(0, 100));          
            $end = time();

            if($try_count < 100){
                echo "[notice] ".($end-$start)."s get ".$req_url ." repeat ". (100 - $try_count)."\n";
            }                
             
            if($tmp && strpos($tmp, 'site-nav')){
                echo "[info] ". ($end-$start) ."s get ".$req_url." success\n";
                break;
            };
            if($tmp && strpos($tmp, 'nav-category')){
                echo "[info] ". ($end-$start) ."s get ".$req_url." success\n";
                break;
            }

            sleep(1);
            $try_count--;
        }

        //解析请求数据
        $o_html = new simple_html_dom();
        $o_html->load($tmp);
        
        $url_arr = array();
        //分类
        foreach($o_html->find('#classfy a') as $a){
            $url_arr[] = 'http://www.dianping.com'.$a->href;
        }
        //热门商区
        foreach($o_html->find('#bussi-nav a') as $a){
            $url_arr[] = 'http://www.dianping.com'.$a->href;
        }
        //行政区
        foreach($o_html->find('#region-nav a') as $a){
            $url_arr[] = 'http://www.dianping.com'.$a->href;
        }
        //地铁线
        foreach($o_html->find('#metro-nav a') as $a){
            $url_arr[] = 'http://www.dianping.com'.$a->href;
        }
        file_put_contents('./data/query_'.$city_key.'.json', json_encode($url_arr));
        $o_html->clear();
        return $url_arr;
    }else{
        $str = file_get_contents('./data/query_'.$city_key.'.json');
        return json_decode($str);
    } 
}

function get_details_url(Array $url_arr, $is_refresh=false){
    global $city_key;
    touch('./data/detail_url_'.$city_key.'.txt');
    touch('./data/req_url_'.$city_key.'.txt');
    
    if($is_refresh == true){
        if(is_array($url_arr)){

            //解析请求数据
            $o_html = new simple_html_dom();      
            foreach($url_arr as $key=>$value){

                $try_count = 10; //请求到非法数据时，重复十次 
                while($try_count >= 0){
                    
                    $start = time();
                    $tmp = get_data($value, rand(0, 100));                    
                    $end = time();
                    
                    if($try_count < 10){                       
                        echo "[notice] ". ($end-$start) ."s get ".$value ." repeat ". (10 - $try_count)."\n";
                    }
                    
                    if($tmp && strpos($tmp, 'site-nav')){
                        echo "[info] ". ($end-$start) ."s get ".$value." success\n";        
                        break;
                    }
                    sleep(1);               
                    $try_count--;
                }
                
                $o_html->load($tmp);
                //首页获取每条详情页面的url
                $tmp_arr = file('./data/detail_url_'.$city_key.'.txt', FILE_IGNORE_NEW_LINES);
                
                foreach($o_html->find('#shop-all-list li') as $li){
                    $item_url = 'http://www.dianping.com'.$li->find('.pic', 0)->find('a',0)->href;
                    if(in_array($item_url, $tmp_arr))continue; //如果文件中已保存跳过
                    file_put_contents('./data/detail_url_'.$city_key.'.txt', $item_url."\n", FILE_APPEND);               
                }
                               
                //获取最后一页
                $page_arr = array();
                foreach($o_html->find('a.PageLink') as $a){
                    $page_arr[] = $a->innertext;
                };
                                
                if(is_array($page_arr) && count($page_arr) >= 2){                 
                    $page_total = array_pop($page_arr);
                    $page_url_arr = array();
                    //获取分页获取页面url
                    foreach(range(2, $page_total) as $k=>$v){

                        if(strpos($value, '#')){
                            $page_url_arr[] = strstr($value, '#', true).'p'.$v;
                        }else{
                            $page_url_arr[] = $value.'p'.$v;
                        }
                    }
                                      
                    //遍历分页url
                    foreach($page_url_arr as $v1){

                        $req_detail_arr = file('./data/req_url_'.$city_key.'.txt' ,FILE_IGNORE_NEW_LINES); 
                        //已经请求过的url直接跳过
                        if(in_array($v1, $req_detail_arr)){
                            echo "[notice] {$v1} is requested\n";
                            continue;
                        }

                        $try_count = 100; //请求到非法数据时，重复十次
                        while($try_count >= 0){

                            $req_detail_arr = file('./data/req_url_'.$city_key.'.txt' ,FILE_IGNORE_NEW_LINES); 
                            //已经请求过的url直接跳过
                            if(in_array($v1, $req_detail_arr)){
                                echo "[notice] {$v1} is requested\n";
                                break;
                            }                   
                            $start = time();
                            $tmp_page = get_data($v1, rand(0, 100));
                            $end = time();
                        
                            if($try_count < 100){
                                echo "[notice] ". ($end-$start) ."s get ".$v1 ." repeat ". (100 - $try_count)."\n";
                            }
                        
                            if($tmp_page && strpos($tmp_page, 'site-nav')){
                                echo "[info] ". ($end-$start) ."s get ".$v1." success \n";
                                break;
                            };
                            sleep(1);
                            $try_count--;
                        }
                        
                        $req_detail_arr = file('./data/req_url_'.$city_key.'.txt' ,FILE_IGNORE_NEW_LINES); 
                        //已经请求过的url直接跳过
                        if(in_array($v1, $req_detail_arr)){
                            echo "[notice] {$v1} is requested\n";
                            continue;
                        } 
                        

                        $o_html->load($tmp_page);
                        
                        
                        foreach($o_html->find('#shop-all-list li') as $li){
                            $item_url = 'http://www.dianping.com'.$li->find('.pic', 0)->find('a',0)->href;
                            
                            //首页获取每条详情页面的url
                            $tmp_arr = file('./data/detail_url_'.$city_key.'.txt', FILE_IGNORE_NEW_LINES);
                            if(in_array($item_url, $tmp_arr)){
                                echo "[notice] {$item_url} is exists in detail_url_bejing.txt\n";
                                continue; //如果文件中已保存跳过
                            }
                            file_put_contents('./data/detail_url_'.$city_key.'.txt', $item_url."\n", FILE_APPEND);
                        }

                        //记录当前状态，用于错误时恢复现场
                        $tmp_req_arr = file('./data/req_url_'.$city_key.'.txt', FILE_IGNORE_NEW_LINES);
                        if(!in_array($value, $tmp_req_arr)){
                            file_put_contents('./data/req_url_'.$city_key.'.txt', $v1."\n", FILE_APPEND);
                        }

                        $o_html->clear();
                    }
                }               
                $o_html->clear();
            }
        }
        
        
    }
    return file('./data/detail_url_'.$city_key.'.txt', FILE_IGNORE_NEW_LINES);
}

function parse_detail(Array $url_arr){

    global $city_key;
    if(is_array($url_arr)){
        $data_arr = array();
        if(!is_dir('./html/'.$city_key)){
            if(!mkdir('./html/'.$city_key)){
                echo "[error] can\'t mkdir ./html/{$city_key}\n";
                exit;
            }
        }
        $filelist = scandir('./html/'.$city_key);
        foreach($url_arr as $k=>$value){
            if(in_array(substr(strrchr($value, '/'), 1).'.html', $filelist))continue;
      
            $try_count = 10; //请求到非法数据时，重复十次                 
            while($try_count >= 0){
                $start = time();
                $tmp = get_data($value, rand(0, 100));
               
                $end = time();
                if($try_count < 10){
                    echo "[notice] ".($end-$start)."s get ".$value ." repeat ". (10 - $try_count)."\n";
                }                
                 
                if($tmp && strpos($tmp, 'site-nav')){
                    echo "[info] ". ($end-$start) ."s get ".$value." success\n";
                    break;
                };
                if($tmp && strpos($tmp, 'shop-title')){
                    echo "[info] ". ($end-$start) ."s get ".$value." success\n";    //红色模板
                    break;
                }

                sleep(1);
                $try_count--;
            }
           
            if(!$tmp){
                echo "[error] get ".$value." error \n";
                continue;
            };
         
            if($tmp && !strpos($tmp, 'site-nav') && !strpos($tmp, 'shop-title')){
                echo "[error] get ".$value." error \n";
                continue;
            }

            file_put_contents('html/'.$city_key.'/'.substr(strrchr($value, '/'), 1).'.html', $tmp);           
        }
        echo "[info] get all success\n";
        
    }else{
        exit('执行出错');
    }
}

//初始化抓取url
$url_arr = init_grap();

//生成商店url
$detail_url_arr = get_details_url($url_arr, false);

//生成缓存html文件
parse_detail($detail_url_arr);










