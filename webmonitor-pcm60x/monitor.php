<p><center><b><font color="red">DEMO MODE<br><br></font></b>code to PXMX60:
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php
define( 'CRC16POLYN', 0x1021 );
define( 'CRC16POLYI', 0x8408 );

function CRC16Normal( $buffer, $result ) {
if ( ( $length = strlen( $buffer ) ) > 0 ) {
for ( $offset = 0; $offset < $length; $offset++ ) {
$result ^= ( ord( $buffer[$offset] ) << 8 );
for ( $bitwise = 0; $bitwise < 8; $bitwise++ ) {
if ( ( $result <<= 1 ) & 0x10000 ) $result ^= CRC16POLYN;
$result &= 0xFFFF;
}
}
}
return $result;
}

function CRC16Kermit( $buffer ) {
$result = 0;
for ( $x=0; $x<strlen( $buffer ); $x++ ) {
$result = $result ^ ord( $buffer[$x] );
for ( $y = 0; $y < 8; $y++ ) {
if ( ( $result & 0x0001 ) == 0x0001 ) $result = ( ( $result >> 1 ) ^ CRC16POLYI );
else $result = $result >> 1;
}
}

$lowBit = ( $result & 0xff00 ) >> 8;
$highBit = ( $result & 0x00ff ) << 8;
$result = $highBit | $lowBit;

return $result;
}


function out( $in, $ascii) {
$crccode=strtoupper(dechex($in));
    $crc1=substr($crccode, 0, 2);
    IF ($crc1=="0d"){$crc1="0e";}
    IF ($crc1=="0a"){$crc1="0b";}
    IF ($crc1=="28"){$crc1="29";}
    $crc2=substr($crccode, 2, 4);
    IF ($crc2=="0d"){$crc2="0e";}
    IF ($crc2=="0a"){$crc2="0b";}
    IF ($crc2=="28"){$crc1="29";}
$hexasc="";
for ($i = 0; $i < strlen($ascii); $i++) {
    $byte = strtoupper(dechex(ord($ascii{$i})));
    $byte = str_repeat('0', 2 - strlen($byte)).$byte;
    $hexasc.="\x".$byte;
  }
echo $hexasc."\x".$crc1."\x".$crc2;
echo '\x0D';
}

if (isset($_POST['mode'])) {

switch ($_POST['mode']) {
case 'hex':
$mod = "HEX";
break;
case 'dec':
$mod = "DEC";
break;
default:
$mode = "ASCII";
break;
}
}
$maxoutp=0;
$batsysp=0;
$cursysp=0;
$cursysp=0;
$bulkp=0;
$floatp=0;
$battypep=0;
$mode = "ASCII";
################################################################  UPDATE CHARGER ####################################################################
### Battery Voltage System ####
if (isset($_POST['batsysp'])) {
if ($_POST['batsysp']!=11) {
    $input="PBRV0".$_POST['batsysp'];
    $input2="PBRV0".$_POST['batsysp'];
    $batsysp=out(CRC16Normal($input, 0x0000), $input2);
    echo $batsysp."<br>";
   }}
### Battery Current   
if (isset($_POST['cursysp'])) {
if ($_POST['cursysp']!=11) {
    $input="MCHGC0".$_POST['cursysp'];
    $input2="MCHGC0".$_POST['cursysp'];
    $cursysp=out(CRC16Normal($input, 0x0000), $input2);
    echo $cursysp."<br>";
   }}
###    
if (isset($_POST['bulkp']) && isset($_POST['bulkv']) && isset($_POST['factorv'])) {
if ($_POST['bulkp']!=$_POST['bulkv']) {
	$bulkp=floor($_POST['bulkp']/$_POST['factorv']*100)/100;
	IF ($bulkp<15.01 AND $bulkp>11.99){
    $input="PBAV".$bulkp;
    $input2="PBAV".$bulkp;
    $bulkp=out(CRC16Normal($input, 0x0000), $input2);
    echo $bulkp."<br>";
    }}
}
###    
if (isset($_POST['floatp']) && isset($_POST['floatv']) && isset($_POST['factorv'])) {
if ($_POST['floatp']!=$_POST['floatv']) {
	$floatp=floor($_POST['p']/$_POST['factorv']*100)/100;
	IF ($loatp<15.01 AND $floatp>11.99){
    $input="PBFV".$floatp;
    $input2="PBFV".$floatp;
    $bulkp=out(CRC16Normal($input, 0x0000), $input2);
    echo $floatp."<br>";
    }}
}
###
### Battery Type ####
if (isset($_POST['battypep'])) {
if ($_POST['battypep']!=11) {
    $input="PBT0".$_POST['battypep'];
    $input2="PBT0".$_POST['battypep'];
    $battypep=out(CRC16Normal($input, 0x0000), $input2);
    echo $battypep."<br>";
   }}
#######################################################################################################################################################
?>
</center></p>
<?php
### experimental version not for use yet
#echo shell_exec("sudo python /home/pi/pyhton/qpiri.py");
#echo shell_exec("sudo python /home/pi/pyhton/qpigs.py");
#$filename = '/home/pi/pyhton/qpiri.txt';
#$filename2 = '/home/pi/pyhton/qpigs.txt';
$filename = 'qpiri.txt';
$filename2 = 'qpigs.txt';
$timestamp=time();
if (file_exists($filename2)) 
   {  
    $file = file_get_contents($filename2);
   }
$remove="(";
$file=str_replace($remove, "", $file); 
$einzeln = explode(' ', $file);
###################### STATUS ################################
$pvvolt = floatval($einzeln[0]); ### PV Voltage
$pvwatts= floatval($einzeln[5]); ### PV Watt
$volt = floatval($einzeln[1]); ### Batt Voltage
$current = floatval($einzeln[2]); ### Batt Ampere
$ctemp = floatval($einzeln[6]); ### Charger Temp
if (file_exists($filename))
   {  
    $file = file_get_contents($filename);
   }
$remove="(";
$file=str_replace($remove, "", $file); 
$einzeln = explode(' ', $file);
$maxout = floatval($einzeln[0]); ### PV Power Max 
$batsys = floatval($einzeln[1]); ### sytem voltage
$cursys = floatval($einzeln[2]); ### Max System Ampere Charging
$bulk = floatval($einzeln[3]); ### Bulk Voltage
$float =  floatval($einzeln[4]); ### Float Voltage
$battype = array('00', '01', '02');
$type   = array('AGM', 'Flooded', 'Customized');
$batype  = str_replace($battype, $type, $einzeln[5]); ### Battery Type/Modus
$zeit=time();
$uhrzeit = date("H:i:s",$zeit);
$factor = $batsys/12;
$battm1 = 122/10*$factor;
$batth = 155/10*$factor;
$bathm2 = 125/10*$factor;
$batt1 = 11*$factor;
#### SOC Volt based
     IF ($volt/$factor >128/10)
        {
        $ladestatus=100;  
        }
ELSE If ($volt/$factor <115/10)
        {
        $ladestatus=0;  
     	}
     ELSE
        {
		$ladestatus=($volt/$factor-11.5)/0.013;  
     	}
$ladestatus=floor($ladestatus);
if ($ladestatus>75){$color="green";$hexcol="7CFC00";$battgraf="battery1.jpg";}
elseif ($ladestatus>50){$color="gold";$hexcol="#FFFF00";$battgraf="battery2.jpg";}
elseif ($ladestatus>30){$color="orange";$hexcol="#FFFF00";$battgraf="battery3.jpg";}
else {$color="red";$hexcol="#FF0000";$battgraf="battery4.jpg";}
 ?>
<body>
<script type="text/javascript">
      google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Volt', <? echo $volt;?>]
        ]);

        var options = {
          width: 120, height: 120,
          greenFrom: <?php echo 12*$factor;?>, greenTo: <?php echo 15*$factor;?>,
          yellowFrom: <?php echo 11*$factor;?>, yellowTo: <?php echo 12*$factor;?>,
          redFrom: 0, redTo: <?php echo 11*$factor;?>,
          max: <?php echo 15*$factor;?>,
          minorTicks: 8
        };
        var chart = new google.visualization.Gauge(document.getElementById('chart_div3'));
        chart.draw(data, options);

        setInterval(function() {
          data.setValue(0, 1, <? echo $volt;?>);
          chart.draw(data, options);
        },0);
      }
     </script>
<script type="text/javascript">
      google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Ampere', <? echo $current;?>]
        ]);

        var options = {
          width: 120, height: 120,
          greenFrom: 0, greenTo: 200,
          yellowFrom:-20, yellowTo: 0,
          redFrom:-200, redTo: -20,
          max: 200,
		  min: -200,
          minorTicks: 8
        };
        var chart = new google.visualization.Gauge(document.getElementById('chart_div1'));
        chart.draw(data, options);

        setInterval(function() {
          data.setValue(0, 1, <? echo $current;?>);
          chart.draw(data, options);
        },0);
      }
     </script>
<script type="text/javascript">
      google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Watt', <? echo $pvwatts;?>]
        ]);

        var options = {
          width: 120, height: 120,
          greenFrom: 300, greenTo: 3500,
          yellowFrom:100, yellowTo: 300,
          redFrom:0, redTo: 100,
          max: 3500,
          minorTicks: 8
        };
        var chart = new google.visualization.Gauge(document.getElementById('chart_div2'));
        chart.draw(data, options);

        setInterval(function() {
          data.setValue(0, 1, <? echo $pvwatts;?>);
          chart.draw(data, options);
        },0);
      }
     </script> 

<script type="text/javascript">
      google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Volt', <? echo $pvvolt;?>]
        ]);

        var options = {
          width: 120, height: 120,
          greenFrom: 50, greenTo: 115,
          yellowFrom: 0, yellowTo: 50,
          redFrom: 116, redTo: 145,
          max: 150,
          minorTicks: 8
        };
        var chart = new google.visualization.Gauge(document.getElementById('chart_div4'));
        chart.draw(data, options);

        setInterval(function() {
          data.setValue(0, 1, <? echo $pvvolt;?>);
          chart.draw(data, options);
        },0);
      }
     </script> 
  </body>
<hr>
<p><center>
<h1><u><center>PCM60X MONITOR</u></h><br></p>
<table width="90%" align="center" BORDER="1"><tr>
<td bgcolor=#e3e3e3 align="center" width="33%">Time: <?echo $uhrzeit;?></td>
<td bgcolor=#e3e3e3 align="center" width="33%">Charger Temp: <?echo $ctemp;?> C</td>
</tr>  
</table>
<hr><center><h4>Status</h>
<table WIDTH=90% cellspacing="10" cellpadding="20"><tr><td>
    <table align="center" BORDER="1"><tr>
	<td bgcolor=#CCFFCC align="center"><font size="4">Voltage Battery</td>
	<td bgcolor=#CCFFCC align="center"><font size="4">Ampere Battery</td>
    <td bgcolor=#CCFFCC align="center"><font size="4">PV Watt</td>
	<td bgcolor=#CCFFCC align="center"><font size="4">PV Voltage</td>
    <td bgcolor=#CCFFCC align="center"><font size="4">SOC Voltage based</td>
	</tr><tr>
    <td><div id="chart_div3" style="width: 120px; height: 120px;"></div></td>
    <td><div id="chart_div1" style="width: 120px; height: 120px;"></div></td>
    <td><div id="chart_div2" style="width: 120px; height: 120px;"></div></td>
    <td><div id="chart_div4" style="width: 120px; height: 120px;"></div></td>
    <td align=center><img src="<? echo $battgraf;?>" width="60" height="120"></td>
	</tr><tr>
	<td bgcolor=#CCFFCC align="center"><font size="4"><? echo $volt." V";?></td>
	<td bgcolor=#CCFFCC align="center"><font size="4"><? echo $current." A";?></td>
    <td bgcolor=#CCFFCC align="center"><font size="4"><? echo $pvwatts." W";?></td>
	<td bgcolor=#CCFFCC align="center"><font size="4"><? echo $pvvolt." V";?></td>
    <td bgcolor=#CCFFCC align="center"><font size="4"><? echo $ladestatus." %";?></td>
	</tr>
	</table></td></tr></table>
<hr><center><h4>Settings</h>
<table WIDTH=90% cellspacing="10" cellpadding="20"><tr><td>
    <form align=center method="post">
    <table align="center" BORDER="1"><tr>
	<td bgcolor=#CCFFCC align="center"><font size="4">PV Power Max</td>
	<td bgcolor=#CCFFCC align="center"><font size="4">Battery sytem voltage</td>
    <td bgcolor=#CCFFCC align="center"><font size="4">Max charge current</td>
	<td bgcolor=#CCFFCC align="center"><font size="4">bulk voltage</td>
    <td bgcolor=#CCFFCC align="center"><font size="4">float voltage</td>
    <td bgcolor=#CCFFCC align="center"><font size="4">battery type</td>
	</tr><tr>
	<td bgcolor=#CCFFCC align="center"><font size="4"><? echo $maxout." W";?></td>
	<td bgcolor=#CCFFCC align="center"><font size="4"><? echo $batsys." V";?></td>
    <td bgcolor=#CCFFCC align="center"><font size="4"><? echo $cursys." A";?></td>
	<td bgcolor=#CCFFCC align="center"><font size="4"><? echo $bulk*$factor." V";?></td>
    <td bgcolor=#CCFFCC align="center"><font size="4"><? echo $float*$factor." V";?></td>
    <td bgcolor=#CCFFCC align="center"><font size="4"><? echo $batype;?></td>
	</tr>
    <tr>
    <?php
    $maxout=$maxout/4*$factor;
    ?>	
    <td bgcolor=#CCFFCC align="center"><font size="4"><? echo "$batsys V: $maxout W";?></td>
	<td bgcolor=#CCFFCC align="center"><font size="4">
	<select type="text" name="batsysp" size="4">
	<option selected value="11"><?php echo $batsys." V";?></option>
      <option value="0">Enable battery voltage auto sensing</option>
      <option value="1">voltage 12V</option>
      <option value="2">voltage 24V</option>
      <option value="4">voltage 48V</option>
    </select>
	</td>	
    <td bgcolor=#CCFFCC align="center"><font size="4">
	<select type="text" name="cursysp" size="4">
	<option selected value="11"><?php echo $cursys." A";?></option>
      <option value="60">60 A</option>
      <option value="50">50 A</option>
      <option value="40">40 A</option>
      <option value="30">30 A</option>
      <option value="20">20 A</option>
      <option value="10">10 A</option>
    </select>
	</td>
	<td bgcolor=#CCFFCC align="center"><font size="4"><input name="bulkp" type="number" min="<?php echo 12*$factor;?>" max="<?php echo 15*$factor;?>" step="0.01" value="<?php echo $bulk*$factor;?>"></td>
    <td bgcolor=#CCFFCC align="center"><font size="4"><input name="floatp" type="number" min="<?php echo 12*$factor;?>" max="<?php echo 15*$factor;?>" step="0.01" value="<?php echo $float*$factor;?>"></td>
	<td bgcolor=#CCFFCC align="center"><font size="4">
	<select type="text" name="battypep" size="4">
	<option selected value="11"><?php echo $batype;?></option>
      <option value="0">AGM</option>
      <option value="1">Flooded</option>
      <option value="2">USER</option>
    </select>
	</td>
</tr>
<input type="hidden" name="mode" value="ascii">
<input type="hidden" name="bulkv" value="<?php echo $bulk*$factor;?>">
<input type="hidden" name="floatv" value="<?php echo $float*$factor;?>">
<input type="hidden" name="factorv" value="<?php echo $factor;?>">
</td></tr></table>
<center><button type="submit">save new settings</button></form>
</td></tr></table>
<hr>
