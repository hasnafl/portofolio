<?php
$starttime = microtime(true); 
function curl($url, $data){
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $output = curl_exec($ch); 
    curl_close($ch);      
    return $output;
}
$host       =   "localhost";
$user       =   "root";
$password   =   '';
$database   =   "hscode_server";

$koneksi = mysqli_connect($host, $user, $password, $database);

$content = mysqli_query($koneksi, "SELECT * from new_datatesting");
$ipaddress = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
$url = 'http://'.$ipaddress.':9090/predict';
foreach($content as $content){
    $id = $content['id'];
    echo "Content ID : ".$id."\n";
    $data = array("kind_of_goods"=>$content["content"]);
    $postdata = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $result = curl_exec($ch);
    curl_close($ch);
    $k = json_encode(array('respon'=>$result),JSON_UNESCAPED_SLASHES);
    $data = json_decode($result, TRUE);
    $confidence = $data['confidence'];
    $hscode = $data['prediction'];
    // echo $confidence.' ';
    // echo $hscode.' ';
    $update = mysqli_query($koneksi,"UPDATE new_datatesting SET confidence = '$confidence', hscode_pred = '$hscode' WHERE id = $id");
}
$endtime = microtime(true);
printf("process finish at %f seconds", $endtime - $starttime);
echo "\n";
?>