<?php
function traindata(){
$host       =   "localhost";
$user       =   "root";
$password   =   '';
$database   =   "hscode_server";
$koneksi = mysqli_connect($host, $user, $password, $database);

$starttime = microtime(true);
date_default_timezone_set('Asia/jakarta');
$time = time();
$a = date('Y-m-d H:i:s', $time);
echo "Start at : ".date('H:i:s', $time);
echo "\n"; 
$ipaddress = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
$url = 'http://'.$ipaddress.':8000/trainhscode';
$data = "";

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
// $saved = $data['model_saved'];

$endtime = microtime(true);
$time2 = time();
$b = date('Y-m-d H:i:s', $time2);
echo "Finish at : ".date('H:i:s', $time2);
$sql = mysqli_query($koneksi, "INSERT INTO `log` (start_time, end_time, keterangan)
VALUES ('$a', '$b', 'training data')");

// if (mysqli_query($koneksi, $sql)) {
//     echo "New record created successfully";
// } else {
//     echo "Error: " . $sql . "<br>" . mysqli_error($koneksi);
// }

mysqli_close($koneksi);

echo "\n";
printf("process finish at %f seconds", $endtime - $starttime);
echo "\n";
// $send = train($url,$data);

}
traindata();

?>