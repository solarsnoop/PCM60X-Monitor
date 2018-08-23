# this code based on a forum entry: https://www.szenebox.org/archive/index.php/t-4319.html user NIMBUS
# the code is simple modify only for the output, but the calculation of CRC is based on this code
<pre>
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
    # special rules for the charger thank you to https://github.com/njfaria
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
    $hexasc.="\x".$byte;
  }
echo $hexasc."\x".$crc1."\x".$crc2;
echo '\x0D';
}
if (isset($_POST['mode']) && isset($_POST['input'])) {
switch ($_POST['mode']) {
case 'hex':
$input2= $_POST['input'];
$input = hex2bin($_POST['input']);
$mod = "HEX";
break;
case 'dec':
$input = decbin($_POST['input']);
$mod = "DEC";
break;
default:
$input = $_POST['input'];
$mode = "ASCII";
break;
}
echo "CRC-CCITT (XModem) -> ".$_POST['input'].":\t";
echo "Send code to PIP: ";
$input2=$_POST['input'];
$code=out(CRC16Normal($input, 0x0000), $input2);
echo $code;
}
?>

<form method="post">
<input type="text" name="input">
<select name="mode">
<option value="ascii">ASCII</option>
<input type="submit">
</select>
</form>
</pre>
