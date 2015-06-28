#!/usr/bin/env php
<?php

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


//DB 类
class DB{
    private $o_db;
    public function __construct(){
        try{
            $this->o_db = new PDO("mysql:host=localhost;dbname=grab","root","",array(PDO::MYSQL_ATTR_INIT_COMMAND=>'SET NAMES UTF8'));
        }catch(PDOException $e){
            echo $e->getMessage();
            exit;
        }
    }

    public function insert($sql, Array $arr = array()){
        $stmt = $this->o_db->prepare($sql);
        if($stmt->execute($arr)){
            return true;
        }else{
            return false;
        }
    }

    public function select($sql, Array $arr = array()){
        $stmt = $this->o_db->prepare($sql);
        if($stmt->execute($arr)){
            if(!!$rows = $stmt->fetchAll(PDO::FETCH_ASSOC)){
                return $rows;
            }else{
                return false;
            }
        }
    }
}

function parse_html_from_file(){
    global $city_key;
    $filelist = scandir('./html/'.$city_key);
    $data_arr = array();
    foreach($filelist as $k=>$value){
        if($value == '.' || $value == '..')continue;         
        $tmp = file_get_contents('./html/'.$city_key.'/'.$value);
         
        if(!$tmp)continue;
        
        if($tmp && !strpos($tmp, 'site-nav') && !strpos($tmp, 'shop-title'))continue;
                 
        $dp_id = strstr($value, '.', true);
        
        echo "[info] {$k} {$dp_id}\n";
        
        if(!strpos($tmp, 'shop-name')){
            echo "[error] parse {$value} error";
            continue;
        }
                     
        //名称
        preg_match('/<\s*h1\s+class="shop-name">([\s\S]*?)<a[\s\S]*<\/h1>/', $tmp, $name); //橘黄
        if(!$name){
            continue;
            preg_match('/<h1\s+class="shop-title".*?>([\s\S]*?)<\/h1>/', $tmp, $name);

        }
        //地址      
        preg_match('/<span.*itemprop="street-address"\s+title="(.*)">[\s\S]*?<\/span>?/', $tmp, $addr); //橘黄
        // !$addr && preg_match('/<span.*itemprop="street-address">([\s\S]*?)<\/span>?/', $tmp, $addr);
        
        //电话
        preg_match_all('/<span\s+class="item"\s+itemprop="tel">(.*?)<\/span>/', $tmp, $tel_arr);    //橘黄      
        
        // if(!$tel_arr[1]){
        //     preg_match('/<strong\s+itemprop="tel">([\s\S]*?)<\/strong>?/', $tmp, $tel_arr);
        //     $tel_arr[1] = explode('&nbsp;', $tel_arr[1]);
        // }

        $tel = '';
        foreach($tel_arr[1] as $t_k=>$t){
            if(strpos($t, '-')){
                $tel .= 'tel:'.$t.'|';
            }else{
                $tel .= 'phone:'. $t.'|';
            }           
        }

        //缩略图
        preg_match('/<img\s+itemprop="photo"\s+src="(.*).*\s+title.*"\/>/', $tmp, $thumb);  //橘黄
//        !$thumb && preg_match('/<img.*?itemprop="photo".*src="(.*?)"\s+\/>/', $tmp, $thumb);
        
        if(is_array($thumb) && count($thumb)>0){
            $thumb = trim($thumb[1]);
        }else{
            $thumb = '';
        }
               
        //地区
        preg_match_all('/<a\s+href="(.*)"\s+itemprop="url">([\s\S]*?)<\/a>/', $tmp, $href_arr);    //橘黄
        
        foreach($href_arr[1] as $a_k=>$a_v){
            if($a_k == 1){
                $area = substr(strrchr($a_v, '/'), 1);
            }

            if($a_k == 2){
                if(strpos($a_v, '/r')){
                    $area = substr(strrchr($a_v, '/'), 1);
                }else{
                    $category = trim($href_arr[2][2]);
                }
            }
            if($a_k == 3){
                $category = trim($href_arr[2][3]);
            }
        }
        
        //点评量
        preg_match('/<span\s+class="sub-title">\((.*?)\)<\/span>/', $tmp, $comment_count);
        
        if(is_array($comment_count) && count($comment_count) > 0){
            $comment_count = $comment_count[1];
        }else{
            $comment_count = '';
        }
              
        //平均消费
        preg_match('/<span class="item">人均：(\d+)/', $tmp, $avg_cost);
        
        if(is_array($avg_cost) && count($avg_cost) > 0){
            $avg_cost = $avg_cost[1];
        }else{
            $avg_cost = '';
        }
        
        //营业时间
        preg_match('/<p\s+class="info info-indent">[\s\S]*?<span class="item">([\s\S]*?)<\/span>/', $tmp, $bus_time);
        
        if(is_array($bus_time) && count($bus_time)>0){
            $bus_time = $bus_time[1];
        }else{
            $bus_time = '';
        }
        //坐标
        preg_match('/{lng:(\d+.\d+),lat:(\d+.\d+)}/', $tmp, $baidu_map);
             
        $baidu_map_arr = array();
        if(is_array($baidu_map) && count($baidu_map)>0){
            $baidu_map_arr['lng'] = $baidu_map[1];
            $baidu_map_arr['lat'] = $baidu_map[2];
        }else{
            $baidu_map_arr['lng'] = '';
            $baidu_map_arr['lat'] = '';
        }
        
        $data_arr[$k]['name'] = trim($name[1]);
        $data_arr[$k]['addr'] = trim($addr[1]);
        $data_arr[$k]['city'] = trim($city_key);
        $data_arr[$k]['tel'] = trim(substr($tel, 0, -1));
        $data_arr[$k]['dp_id'] = trim($dp_id);
        $data_arr[$k]['thumb'] = $thumb;
        $data_arr[$k]['area'] = trim($area);
        $data_arr[$k]['category'] = trim($category);
        $data_arr[$k]['comment_count'] = $comment_count;
        $data_arr[$k]['avg_cost'] = $avg_cost;
        $data_arr[$k]['bus_time'] = trim($bus_time);
        $data_arr[$k]['lng'] = trim($baidu_map_arr['lng']);
        $data_arr[$k]['lat'] = trim($baidu_map_arr['lat']);
    }    
    return $data_arr;
}

function generate_data($data_arr){
    global $city_key;
    $db = new DB();
    foreach($data_arr as $k=>$v){

        $time = date("Y-m-d h:m:s");

        $rows = $db->select('select * from grab_sport_shop where dp_id=? and city=?',array(
            $v['dp_id'],
            $city_key
        ));

        if($rows){
            echo "[notice] dp_id {$v['dp_id']} city {$city_key} has exists.\n";
            return;
        }
        echo "[info] insert {$k}\n";
        $rows = $db->insert("insert into grab_sport_shop (
            `name`,
            `addr`,
            `city`,
            `tel`,
            `dp_id`,
            `thumb`,
            `area`,
            `category`,
            `comment_count`,
            `avg_cost`,
            `bus_time`,
            `lng`,
            `lat`,
            `create_time`
        ) values (
            ?,?,?,?,?,?,?,?,?,?,?,?,?,now()
        )", array(
            $v['name'],
            $v['addr'],
            $v['city'],
            $v['tel'],
            $v['dp_id'],
            $v['thumb'],
            $v['area'],
            $v['category'],
            $v['comment_count'],
            $v['avg_cost'],
            $v['bus_time'],
            $v['lng'],
            $v['lat']
        ));
    }
}

$dp_arr = parse_html_from_file();

generate_data($dp_arr);
