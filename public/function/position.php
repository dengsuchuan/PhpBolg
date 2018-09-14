<?php
class position
{
    function getip(){
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
            $ip = getenv("HTTP_CLIENT_IP");
        } else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
            $ip = getenv("REMOTE_ADDR");
        } else if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = "unknown";
        }
        if($ip == "::1"){       //没有获取到ip地址
            $host_name = exec("hostname");
            $ip = gethostbyname($host_name); //获取本机的局域网IP
        }
        return $ip;
    }

    function getLocation($ip=''){
        empty($ip) && $ip = $this->getip();
        if($ip=="127.0.0.1") return "本机地址";
        $api = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=json&ip=$ip";
        $json = @file_get_contents($api);//调用新浪IP地址库
        $arr = json_decode($json,true);//解析json
        $country = $arr['country']; //取得国家
        $province = $arr['province'];//获取省份
        $city = $arr['city']; //取得城市
        if((string)$country == "中国"){
            if((string)($province) != (string)$city){
                $_location = $province.$city;
            }else{
                $_location = $country.$city;
            }
        }else{
            $_location = $country;
        }
        //echo $_location;
        return $_location;
    }

    function getLocationBaiDuAPI(){
        error_reporting(0); //出现错误禁止报错（如果是调试时期请注释掉这行代码，上线之后可以打开注释）
        $ip = $_POST['ip'];//结合表单提交获取用户输入的IP地址。
        $handle = fopen("http://api.map.baidu.com/location/ip?ip=61.157.243.124&ak=GnWwF6aqF6GdkfZKTx4BjM7OnLVsNC6s&coor=bd09ll","r");//打开接口地址并用制度方式强制转换为二进制
        $content = "";//先定义一个空变量来接收转化的json
        while (!feof($handle)) { //while语句：如果返回的值不为true则继续，feof的意思为测试文件指针是否到了文件结束的位置
            $content .= fread($handle, 10000);//使用二进制读取获取到的文件
        }
        fclose($handle);//关闭这个文件

        $content = json_decode($content,true);//强制将json格式转化为数组模式
        return $content;
    }

}

