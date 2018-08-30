<!DOCTYPE html>
<html lang="en">
<head>
<body>
<center><p>  
<title>Dashboard</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"></script>    
<script type="text/javascript">
     $(document).ready(function() {
       $("#refresh").load("refresh.php");
       var refreshId = setInterval(function() {
          $("#refresh").load('refresh.php?' + 1*new Date());
       }, 1000);
    });
</script>
</head>
<body>
   <div id="refresh" style="text-align:center;">
<?php
  include ("refresh.php");
?>
</div>
</body>
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


function out( $in, $ascii, $setnew) {
$crccode=strtoupper(dechex($in));
    $crc1=substr($crccode, 0, 2);
    IF ($crc1=="0D"){$crc1="0E";}
    IF ($crc1=="0A"){$crc1="0B";}
    IF ($crc1=="28"){$crc1="29";}
    $crc2=substr($crccode, 2, 4);
    IF ($crc2=="0D"){$crc2="0E";}
    IF ($crc2=="0A"){$crc2="0B";}
    IF ($crc2=="28"){$crc2="29";}
$hexasc="";
for ($i = 0; $i < strlen($ascii); $i++) {
    $byte = strtoupper(dechex(ord($ascii{$i})));
    $byte = str_repeat('0', 2 - strlen($byte)).$byte;
    $hexasc.=$byte;
  }
$out=$hexasc.$crc1.$crc2.'0D';
echo '<hr><center>'.$setnew;
echo '<br>HEXCODE: '.$out;
echo '</center><br>';
file_put_contents("/var/www/html/monitor/transfer.txt", $out);
$check = shell_exec("sudo python2 /var/www/html/monitor/sendcode.py");
$check = $setnew;
IF ($check!='')
   {
   echo "<center> setup successful: ".$check;
   $filename = '/var/www/html/monitor/qpiri.txt';
   $file = file_get_contents($filename);
   $remove="(";
   $file=str_replace($remove, "", $file); 
   $einzeln = explode(' ', $file);
   if ($_POST['batsysp'] <> 0 and $_POST['batsysp'] <> 11){
       $einzeln[1]=$_POST['batsysp']*12;
       }
   if ($_POST['cursysp'] <> 11){
       $einzeln[2]=$_POST['cursysp'];
       }
    if ($_POST['bulkp'] <> $_POST['bulkv']){
        $einzeln[3]=$_POST['bulkp']/$_POST['factorv'];
        }
    if ($_POST['floatp'] <> $_POST['floatv']){
        $einzeln[4]=$_POST['floatp']/$_POST['factorv'];
        }
   if ($_POST['battypep'] <> 11){
       $einzeln[5]="0".$_POST['battypep'];
       }
    $outf="(".$einzeln[0]." ".$einzeln[1]." ".$einzeln[2]." ".$einzeln[3]." ".$einzeln[4]." ".$einzeln[5];
    file_put_contents($filename, $outf); 
    echo "<hr>";
   }
else
  {
   echo "<center> setup: not successfull";
  }
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
$mode = "ASCII";
################################################################  UPDATE CHARGER ####################################################################
### Battery Voltage System ####
if (isset($_POST['batsysp'])) {
if ($_POST['batsysp']!=11) {  
    $input="PBRV0".$_POST['batsysp'];
    $input2="PBRV0".$_POST['batsysp'];
    IF ($_POST['batsysv']==0)
       {
       $batsysvn='Enable battery voltage auto sensing';
       }
       else
          {
          $batsysvn=$_POST['batsysv'];
          }
    if ($_POST['batsysp']==0)
       {
       $batsyspn='Enable battery voltage auto sensing';
       }
       else
          {
          $batsyspn=$_POST['batsysp']*12;
          }
    $batsyspn = 'Setup battery system voltage from '.$batsysvn.' V to: '.$batsyspn.' V';
    $out=out(CRC16Normal($input, 0x0000), $input2, $batsyspn); 
   }}
### Battery Current   
if (isset($_POST['cursysp'])) {
if ($_POST['cursysp']!=11) {
    $input="MCHGC0".$_POST['cursysp'];
    $input2="MCHGC0".$_POST['cursysp'];
    $cursyspn = 'Setup max. current from '.$_POST['cursysv'].' A to: '.$_POST['cursysp'].' A';
    $out=out(CRC16Normal($input, 0x0000), $input2, $cursyspn); 
   }}
###    
if (isset($_POST['bulkp']) && isset($_POST['bulkv']) && isset($_POST['factorv'])) {
if ($_POST['bulkp']!=$_POST['bulkv']) {
	$bulkp=floor($_POST['bulkp']/$_POST['factorv']*100)/100;
	IF ($bulkp<15.01 AND $bulkp>11.99){
    $bulkp=$bulkp*100;
    $vorne = substr($bulkp, 0, 2);
    $hinten= substr($bulkp, -2);
    $bulkp=$vorne.'.'.$hinten;
    $input="PBAV".$bulkp;
    $input2="PBAV".$bulkp;
    $bulkpn = 'Setup bulk voltage from '.$_POST['bulkv'].' V to: '.$_POST['bulkp'].' V';
    $out=out(CRC16Normal($input, 0x0000), $input2, $bulkpn); 
    }}
}
###    
if (isset($_POST['floatp']) && isset($_POST['floatv']) && isset($_POST['factorv'])) {
if ($_POST['floatp']!=$_POST['floatv']) {
	$floatp=floor($_POST['floatp']/$_POST['factorv']*100)/100;
	IF ($floatp<15.01 AND $floatp>11.99){
    $floatp=$floatp*100;
    $vorne = substr($floatp, 0, 2);
    $hinten= substr($floatp, -2);
    $floatp=$vorne.'.'.$hinten;
    $input="PBFV".$floatp;
    $input2="PBFV".$floatp;
    $floatpn = 'Setup float voltage from '.$_POST['floatv'].' V to: '.$_POST['floatp'].' V';
    $out=out(CRC16Normal($input, 0x0000), $input2, $floatpn);
    }}
}
###
### Battery Type ####
if (isset($_POST['battypep'])) {
if ($_POST['battypep']!=11) {
    $battypen = array('0', '1', '2');
    $typen   = array('AGM', 'Flooded', 'Customized');
    $batypen = '0'.$_POST['battypep'];
    $batypen  = str_replace($battypen, $typen, $_POST['battypep']); ### Battery Type/Modus
    $batypen = 'Setup to new Battery Type: '.$batypen;
    $input="PBT0".$_POST['battypep'];
    $input2="PBT0".$_POST['battypep'];
    $out=out(CRC16Normal($input, 0x0000), $input2, $batypen);
   }}
#######################################################################################################################################################
?>
</center></p>
<?php
$maxoutp=0;
$batsysp=0;
$cursysp=0;
$cursysp=0;
$bulkp=0;
$floatp=0;
$battypep=0;
### experimental version not for use yet
$filename = '/var/www/html/monitor/qpiri.txt';
$timestamp=time();
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
?>
</p><p><center><h1>Settings</h>
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
	<td bgcolor=#CCFFCC align="center"><font size="4"><?php echo $maxout." W";?></td>
	<td bgcolor=#CCFFCC align="center"><font size="4"><?php echo $batsys." V";?></td>
    <td bgcolor=#CCFFCC align="center"><font size="4"><?php echo $cursys." A";?></td>
	<td bgcolor=#CCFFCC align="center"><font size="4"><?php echo $bulk*$factor." V";?></td>
    <td bgcolor=#CCFFCC align="center"><font size="4"><?php echo $float*$factor." V";?></td>
    <td bgcolor=#CCFFCC align="center"><font size="4"><?php echo $batype;?></td>
	</tr>
    <tr>
    <?php
    $maxout=$maxout/4*$factor;
    ?>	
    <td bgcolor=#CCFFCC align="center"><font size="4"><?php echo "$batsys V: $maxout W";?></td>
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
<input type="hidden" name="cursysv" value="<?php echo $cursys;?>">
<input type="hidden" name="batsysv" value="<?php echo $batsys;?>">
</td></tr></table>
<center><p><button name="subject" value="setup" class="styled">Reload - Save new settings</button>&nbsp;&nbsp;&nbsp;&nbsp;<button name="subject2" value="refresh" class="styled">Refresh monitor</button></p>
</form>
</td></tr></table>
</div>
</td></tr></table><br></th></tr></table><p></center>
</center></p>
</body>
</html>
