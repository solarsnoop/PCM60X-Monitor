</script><script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<!DOCTYPE html>
<html lang="en">
<head>
<style>
.styled {
    border: 0;
    line-height: 2.5;
    padding: 0 20px;
    font-size: 1rem;
    text-align: center;
    color: #fff;
    text-shadow: 1px 1px 1px #000;
    border-radius: 10px;
    background-color: rgba(220, 0, 0, 1);
    background-image: linear-gradient(to top left,
                                      rgba(0, 0, 0, .2),
                                      rgba(0, 0, 0, .2) 30%,
                                      rgba(0, 0, 0, 0));
    box-shadow: inset 2px 2px 3px rgba(255, 255, 255, .6),
                inset -2px -2px 3px rgba(0, 0, 0, .6);
}

.styled:hover {
    background-color: rgba(255, 0, 0, 1);
}

.styled:active {
    box-shadow: inset -2px -2px 3px rgba(255, 255, 255, .6),
                inset 2px 2px 3px rgba(0, 0, 0, .6);
}
</style>
</head>
<?php
$maxoutp=0;
$batsysp=0;
$cursysp=0;
$cursysp=0;
$bulkp=0;
$floatp=0;
$battypep=0;
### experimental version not for use yet
$filename2 ='/var/www/html/monitor/qpigs.txt';
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
$current = $pvwatts/$volt; ### Batt Ampere
$ctemp = floatval($einzeln[6]); ### Charger Temp
$zeit=time();
$uhrzeit = date("H:i:s",$zeit);
$factor=1;
if ($volt>18 and $volt < 36){$factor=2;}
if ($volt>36){$factor=4;}
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
          ['Volt', <?php echo $volt;?>]
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
          data.setValue(0, 1, <?php echo $volt;?>);
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
          ['Ampere', <?php echo $current;?>]
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
          data.setValue(0, 1, <?php echo $current;?>);
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
          ['Watt', <?php echo $pvwatts;?>]
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
          data.setValue(0, 1, <?php echo $pvwatts;?>);
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
          ['Volt', <?php echo $pvvolt;?>]
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
          data.setValue(0, 1, <?php echo $pvvolt;?>);
          chart.draw(data, options);
        },0);
      }
     </script> 
  </body>
<hr>
<p><center>
<h1><u><center>PCM60X MONITOR</u></h><br></p>
<table width="90%" align="center" BORDER="1"><tr>
<td bgcolor=#e3e3e3 align="center" width="33%">Time: <?php echo $uhrzeit;?></td>
<td bgcolor=#e3e3e3 align="center" width="33%">Charger Temp: <?php echo $ctemp;?> C</td>
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
    <td align=center><img src="<?php echo $battgraf;?>" width="60" height="120"></td>
	</tr><tr>
	<td bgcolor=#CCFFCC align="center"><font size="4"><?php echo $volt." V";?></td>
	<td bgcolor=#CCFFCC align="center"><font size="4"><?php echo $current." A";?></td>
    <td bgcolor=#CCFFCC align="center"><font size="4"><?php echo $pvwatts." W";?></td>
	<td bgcolor=#CCFFCC align="center"><font size="4"><?php echo $pvvolt." V";?></td>
    <td bgcolor=#CCFFCC align="center"><font size="4"><?php echo $ladestatus." %";?></td>
	</tr>
	</table></td></tr></table><hr>
