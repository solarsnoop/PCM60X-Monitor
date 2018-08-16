<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php
### experimental version not for use yet
echo shell_exec("sudo python /home/pi/pyhton/qpiri.py");
echo shell_exec("sudo python /home/pi/pyhton/qpigs.py");
$filename = '/home/pi/pyhton/qpiri.txt';
$filename2 = '/home/pi/pyhton/qpigs.txt';
$timestamp=time();
if (file_exists($filename2)) 
   {  
    $file = file_get_contents($filename2);
   }
$remove="(";
$file=str_replace($remove, "", $file); 
$einzeln = explode(' ', $file);
###################### STATUS ################################
$pvvolt = $einzeln[0]; ### PV Voltage
$power = $einzeln[5]; ### PV Watt
$volt = $einzeln[1]; ### Batt Voltage
$current = $einzeln[2]; ### Batt Ampere
$ctemp = $einzeln[6]; ### Charger Temp
if (file_exists($filename))
   {  
    $file = file_get_contents($filename);
   }
$remove="(";
$file=str_replace($remove, "", $file); 
$einzeln = explode(' ', $file);
$maxout = $einzeln[0]; ### PV Power Max 
$batsys = $einzeln[1]; ### sytem voltage
$cursys = $einzeln[2]; ### Max System Ampere Charging
$bulk = $einzeln[3]; ### Bulk Voltage
$float =  $einzeln[4]; ### Float Voltage
$einzeln[5]="00"; ### test only
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
<h1><u><center>PCM60X MONITOR</u></h><br>
<hr>
<center><font size="3" color="black">
<table width="90%" align="center" BORDER="1"><tr>
<td bgcolor=#e3e3e3 align="center" width="33%">Time: <?echo $uhrzeit;?></td>
<td bgcolor=#e3e3e3 align="center" width="33%">Charger Temp: <?echo $ctemp;?> C</td>
</tr>
</table>
<hr><center><h3>Settings</h>
 <table WIDTH=90% cellspacing="10" cellpadding="20"><tr><td>
    <table align="center" BORDER="1"><tr>
	<td bgcolor=#CCFFCC align="center"><font size="4">PV Power Max</td>
	<td bgcolor=#CCFFCC align="center"><font size="4">Battery sytem voltage</td>
    <td bgcolor=#CCFFCC align="center"><font size="4">Max charge current</td>
	<td bgcolor=#CCFFCC align="center"><font size="4">bulk voltage</td>
    <td bgcolor=#CCFFCC align="center"><font size="4">float voltage</td>
	</tr><tr>
	<td bgcolor=#CCFFCC align="center"><font size="4"><? echo $maxout." W";?></td>
	<td bgcolor=#CCFFCC align="center"><font size="4"><? echo $batsys." V";?></td>
    <td bgcolor=#CCFFCC align="center"><font size="4"><? echo $cursys." A";?></td>
	<td bgcolor=#CCFFCC align="center"><font size="4"><? echo $bulk." V";?></td>
    <td bgcolor=#CCFFCC align="center"><font size="4"><? echo $float." %";?></td>
	</tr>
	</table>

<hr><center><h3>Status</h>	   
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
	</table>
	<hr>  
?>
