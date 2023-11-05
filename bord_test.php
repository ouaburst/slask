<?php 
session_start();

//DB Connect
include "config.php";


//session destory
if (isset($_GET['del'])){
	session_destroy();
	header("Location: userlogin.php");
	
exit;
}

if (isset($_GET['change_day'])){

	unset($_SESSION['bord_show']);
	header("Location: bord_test.php");
	exit;	
}

if (isset($_GET['makuleraSwhish'])){
	
	db_connect("INSERT INTO remail (email, bokningsnr, time) VALUES ('MAKULERA SERVERING', '$boknr', NOW())");	

}



if (isset($_GET['login'])){

	ob_clean();
	header('Content-type: text/html; charset=iso8859-1');
	sleep(1);
	
	if ($_GET['login'] == "bokning"){
		
		$boknr = $_GET['boknr'];
		$email = $_GET['mail_bokning'];
		
		$sql = db_connect("SELECT custid, showid FROM bokings WHERE bokningsnr = '$boknr'");
		if (mysql_num_rows($sql) == 0){
		
		
			echo "error||<span class='error'><img src='Bilder/down.gif.png'>Ingen bokning hittades</span>";
			
			exit;
		}
				
		$custid = mysql_result($sql, 0, 'custid');
		$showid = mysql_result($sql, 0, 'showid');
		
		$bord = db_connect("SELECT * FROM borden WHERE Shownr = '$showid' AND antpers = 0 LIMIT 1");
		if (mysql_num_rows($bord) == 0){
		
			echo "error||<span class='error'><img src='Bilder/down.gif.png'>Inga lediga bord hittades</span>";
			
			exit;
		}
				
		$sql = db_connect("SELECT * FROM customer WHERE teleno = '$custid' AND email = '$email'");
		if (mysql_num_rows($sql) == 0){
		
			echo "error||<span class='error'><img src='Bilder/down.gif.png'>Ingen bokning hittades</span>";
			
			exit;
		}
				
		$_SESSION['bord_boknr'] = $boknr;
		$_SESSION['bord_show'] = $showid;

		//Kund hittad
		$company = mysql_result($sql, 0, 'foretag'); 
		$name = mysql_result($sql, 0, 'fornamn')." ".mysql_result($sql, 0, 'name'); 
		$ort = mysql_result($sql, 0, 'city'); 
		$bokningar = mysql_result($sql, 0, 'teleno'); 
		$kundtyp = mysql_result($sql, 0, 'kundtyp'); 
		$custid = mysql_result($sql, 0, 'teleno');
		$email = mysql_result($sql, 0, 'email');
		$kundtyp = "V";
			
		$_SESSION['access_company'] = $company;
		$_SESSION['access_name'] = $name;
		$_SESSION['access_ort'] = $ort;
		$_SESSION['access_bokningar'] = $bokningar;
		$_SESSION['access_kundtyp'] = $kundtyp;
		$_SESSION['custid'] = $custid;
	   	$_SESSION['kundtyp'] = $kundtyp;
 	    $_SESSION['eva_user'] = $_POST['pass'];
		
		echo "result||"; ?>
		
		<div id="kund_box_info">
		  <table width="480" border="0" cellpadding="0" cellspacing="0" class="b_box">
		    <tr class="b_box_titel">
 		     <td colspan="3"><strong>Kundinfo:</strong></td>
		    </tr>
		    <tr>
 		     <td width="87" height="29">Kundnr <?php echo $custid; ?></td>
 		     <td width="289">E-post <?php echo $email; ?> </td>
 		     <td width="72"><a href="?del">Logga ut > </a></td>
 		   </tr>
			<tr>
			 <td height="29">&nbsp;</td>
 		     <td>Namn: <?php echo $name; ?> </td>
 		     <td>&nbsp;</td>
 		   </tr>
		   
		  </table>
		</div>
		
		<?php
		
		exit;		
	
	}
	
	if ($_GET['login'] == "kund"){
	
		$id = $_GET['kundnr'];
		$email = $_GET['email'];
		
		//$sql = db_connect("SELECT * FROM customer WHERE teleno = '$id' AND email = '$email' ORDER BY teleno ASC LIMIT 1");		
		$sql = db_connect("SELECT * FROM customer WHERE (teleno = '$id' OR losen = '$id') AND email = '$email' ORDER BY teleno ASC LIMIT 1");
		
		//Ingen kund hittades
		if (mysql_num_rows($sql) == 0){
		
			echo "error||<span class='error'><img src='Bilder/down.gif.png'> Ingen kund hittades</span>";
			
			exit;
		}
		
		//Kund hittad
		$company = mysql_result($sql, 0, 'foretag'); 
		$name = mysql_result($sql, 0, 'fornamn')." ".mysql_result($sql, 0, 'name'); 
		$ort = mysql_result($sql, 0, 'city'); 
		$bokningar = mysql_result($sql, 0, 'teleno'); 
		$kundtyp = mysql_result($sql, 0, 'kundtyp'); 
		$custid = mysql_result($sql, 0, 'teleno');
		$email = mysql_result($sql, 0, 'email');
		$foretag = mysql_result($sql, 0, 'foretag');
		$kundtyp = "V";
			
		$_SESSION['access_company'] = $company;
		$_SESSION['access_name'] = $name;
		$_SESSION['access_ort'] = $ort;
		$_SESSION['access_bokningar'] = $bokningar;
		$_SESSION['access_kundtyp'] = $kundtyp;
		$_SESSION['custid'] = $custid;
	   	$_SESSION['kundtyp'] = $kundtyp;
	   	$_SESSION['company'] = $foretag;
 	    $_SESSION['eva_user'] = $_POST['pass'];
		
		echo "result||"; ?>
		
		<div id="kund_box_info">
		<table width="480" border="0" cellpadding="0" cellspacing="0" class="b_box">
		    <tr class="b_box_titel">
 		     <td colspan="3"><strong>Kundinfo:</strong></td>
		    </tr>
		    <tr>
 		     <td width="87" height="29">Kundnr <?php echo $custid; ?></td>
 		     <td width="289">E-post <?php echo $email; ?> </td>
 		     <td width="72"><a href="?del">Logga ut > </a></td>
 		   </tr>
			<tr>
			 <td height="25">&nbsp;</td>
 		     <td>Namn: <?php echo $name; ?> </td>
 		     <td>&nbsp;</td>
 		   </tr>
		   		   <?php
		   $sql = db_connect("SELECT * FROM borden WHERE kundnr = '{$_SESSION['custid']}'");
		   if (mysql_num_rows($sql)){
		   
		   while($row = mysql_fetch_array($sql)){
		   	
				$boknr = $row['bokningsnr'];
				if (!isset($gammal_bokning[$boknr])){
		   			
					$antal_gammla++;
					$gammal_bokning[$boknr] = $boknr;
				}
		   }
		   ?>
			<tr class="b_box_extra">
			  <td height="29" class="b_box_extra">&nbsp;</td>
			  <td align="center" class="b_box_extra"><p class="style1">Du har <?php echo $antal_gammla; ?> tidigare bokningar, <a href="?old">Visa > </a> </p></td>
			  <td class="b_box_extra">&nbsp;</td>
			  </tr>
			  <?php } ?>
		  </table>
		</div>
		
		<?php
		
		exit;		
		
		
	}

}


if (isset($_GET['old_pay']) && !empty($_GET['old_pay'])){

	$orderid = $_GET['old_pay'];
	
	$sql = db_connect("SELECT totsum FROM servering WHERE boknr = '$orderid'");
	if (mysql_num_rows($sql) == 0){
	
		header("Location: bord_test.php?old");
		
		exit;
	}
	

	// =========== Dibs easy pay ============
	
	$amount = mysql_result($sql, 0, 'totsum');
	header("Location: https://shopwps.evarydberg.se/dibs_betala_bord_test.php?amount=$amount&orderid=$orderid");

	// =========== Swish betalning ============
	
	exit;
} 

if (isset($_GET['del_old_bord'])){
	
	$boknr = $_GET['del_old_bord'];
	db_connect("UPDATE borden SET bokningsnr = 0, kundnr = 0, antpers = 0, bokningsnrbiljett = 0, betald = 0, bokadtid = 0, min = 0 WHERE bokningsnr = '$boknr' AND kundnr = '{$_SESSION['custid']}' AND betald < 4");
	
        //db_connect("DELETE from servering WHERE boknr = '$boknr' ");
	db_connect("UPDATE servering SET betalsatt = 0 WHERE boknr = '$boknr' ");
	

	header("Location: bord_test.php?old");
	
	exit;
}


if (isset($_GET['accept_old_new_show_state'])){

	ob_clean();
	header('Content-type: text/html; charset=iso8859-1');
	
	sleep(2);
	
	$boknr = $_SESSION['bord_boknr_old'];
	$showid = $_SESSION['bord_old_new_showid'];
	$timer = $_SESSION['bord_old_new_timer'];
	
	$sql = db_connect("SELECT datum, namn FROM shows WHERE showid = '$showid'");
	$datum = mysql_result($sql, 0, 'datum');
	$namn = mysql_result($sql, 0, 'namn');	
	
	if ($timer == "60") $tid = "Tid: 60 min innan föreställning";
	if ($timer == "30") $tid = "Tid: 30 min innan föreställning";
	if ($timer == "0") $tid = "Tid: Under pausen av föreställningen";
	
	$sql = db_connect("SELECT * FROM borden WHERE bokningsnr = $boknr AND kundnr = '{$_SESSION['custid']}'");
	$antal = mysql_num_rows($sql);
	
	$info['bokningsnr'] = mysql_result($sql, 0, 'bokningsnr');
	$info['kundnr'] = mysql_result($sql, 0, 'kundnr');
	$info['antpers'] = mysql_result($sql, 0, 'antpers');
	$info['bokningsnrbiljett'] = mysql_result($sql, 0, 'bokningsnrbiljett');
	$info['betald'] = mysql_result($sql, 0, 'betald');
	$info['bokadtid'] = mysql_result($sql, 0, 'bokadtid');
	
	if ($timer == 60 || $timer == 30){
	
		$timekiller = "F";
	}
	else{
	
		$timekiller = "P";
	}
	
	
	$sql = db_connect("SELECT * FROM borden WHERE bokningsnr = '0' AND Shownr = '$showid' AND tid = '$timekiller' LIMIT $antal");
	$antal_lediga = mysql_num_rows($sql);
	
	if ($antal_lediga < $antal){
	
		echo "error||Ett fel uppstod!";
		
		exit;
	}
	
	db_connect("UPDATE borden SET bokningsnr = 0, kundnr = 0, antpers = 0, bokningsnrbiljett = 0, betald = 0, bokadtid = 0, min = 0 WHERE bokningsnr = '{$info['bokningsnr']}'");
	$sql = db_connect("SELECT * FROM borden WHERE bokningsnr = '0' AND Shownr = '$showid' AND tid = '$timekiller' LIMIT $antal");
	while($row = mysql_fetch_array($sql)){
	
		$id = $row['idBorden'];
		echo $id;
		db_connect("UPDATE borden SET bokningsnr = '{$info['bokningsnr']}', kundnr = '{$info['kundnr']}', antpers = '{$info['antpers']}', bokningsnrbiljett = '{$info['bokningsnrbiljett']}', betald = '{$info['betald']}', bokadtid = '{$info['bokadtid']}', min = '$timer' WHERE idBorden = '$id'");
		}
	
	db_connect("UPDATE servering SET showid = '$showid' WHERE boknr = '{$info['bokningsnr']}'");
	$sql = db_connect("SELECT Bordnr FROM borden WHERE bokningsnr = '$boknr' ORDER BY Bordnr ASC");
	while($row = mysql_fetch_array($sql)){
				
		$bord = $row['Bordnr'];
		$new_tabel .=  "Bord: $bord<br>";
	}
	
	echo "result||Datum: $datum||Föreställning: $namn||$tid||<br>Reserverade bord: <br> $new_tabel||<b><br><br>Er bokning är uppdaterad!<br><br></b>";
	
	
	exit;
}


if (isset($_GET['yes_no_change_time'])){

	ob_clean();
	header('Content-type: text/html; charset=iso8859-1');
	
	$_SESSION['bord_old_new_timer'] = $_GET['timer_old'];
	$timer = $_SESSION['bord_old_new_timer'];

	$showid = $_SESSION['bord_old_new_showid'];

	$sql = db_connect("SELECT datum FROM shows WHERE showid = '$showid'");
	$datum = mysql_result($sql, 0, 'datum');
	
	if ($timer == "0"){

		$data = "Ny tid till bokningen $datum | I pausen av föreställningen<br>";
	}
	else{
	
		$data = "Ny tid till bokningen $datum | $timer minuter innan förställningen<br>";
	}
	
	echo "result||";
	echo $data;
	echo "<a href='#' onClick='acceptOldNewShow()'>Verkställ ändring</a> | <a href='?old'>Avbryt</a>";
	
	exit;
}

if (isset($_GET['old_show_result_data'])){

	
	ob_clean();
	header('Content-type: text/html; charset=iso8859-1');
	echo "result||";
	$showid = $_GET['old_show_result_data'];

	$boknr = $_SESSION['bord_boknr_old'];
	$_SESSION['bord_old_new_showid'] = $showid;
	
	$sql = db_connect("SELECT * FROM borden WHERE bokningsnr = '$boknr'");
	$now_bord = mysql_num_rows($sql);

	
	if (isset($_GET['timer_old'])){
		
		$timer = $_GET['timer_old'];
		
		if ($timer == 60 || $timer == 30){
	
			$timekiller = "F";
		}
		else{
	
			$timekiller = "P";
		}
		
		$sql = db_connect("SELECT * FROM borden WHERE Shownr = '$showid' AND antpers = 0 AND tid = '$timekiller'");
		$antal = mysql_num_rows($sql);
		$timer_60 = 0;
		$timer_30 = 0;
		$timer_00 = 0;
		$timer_show = "";
		
		while($row = mysql_fetch_array($sql)){
		
			if ($row['tid'] == "F"){
			
				$timer_60++;
			}
			
			if ($row['tid'] == "F"){
			
				$timer_30++;
			}
			
			if ($row['tid'] == "P"){
			
				$timer_00++;
			}
		}
		
		if ($timer_60 >= $now_bord){
		
			$timer_show .= "<input id='radio' name='timer' type='radio' value='60' onClick='timerAndDateOldStatus(60)'>	1 timme f&ouml;re f&ouml;rest&auml;llningen<br>";
		}
		
		if ($timer_30 >= $now_bord){
		
			$timer_show .= "<input id='timer_30' name='timer' type='radio' value='30' onClick='timerAndDateOldStatus(30)'> 30 min f&ouml;re f&ouml;rest&auml;llningen<br>";
		}

		if ($timer_00 >= $now_bord){
		
			$timer_show .= "<input id='timer_pause' name='timer' type='radio' value='pause' onClick='timerAndDateOldStatus(0)'>	I pausen";
		}		
	}
	else{
	
		$sql = db_connect("SELECT * FROM borden WHERE Shownr = '$showid' AND antpers = 0");
		$antal = mysql_num_rows($sql);
		$timer_60 = 0;
		$timer_30 = 0;
		$timer_00 = 0;
		$timer_show = "";
		
		while($row = mysql_fetch_array($sql)){
		
			if ($row['tid'] == "F"){
			
				$timer_60++;
			}
			
			if ($row['tid'] == "F"){
			
				$timer_30++;
			}
			
			if ($row['tid'] == "P"){
			
				$timer_00++;
			}
		}
		
		if ($timer_60 >= $now_bord){
		
			$timer_show .= "<input id='radio' name='timer' type='radio' value='60' onClick='timerAndDateOldStatus(60)'>	1 timme f&ouml;re f&ouml;rest&auml;llningen<br>";
		}
		
		if ($timer_30 >= $now_bord){
		
			$timer_show .= "<input id='timer_30' name='timer' type='radio' value='30' onClick='timerAndDateOldStatus(30)'> 30 min f&ouml;re f&ouml;rest&auml;llningen<br>";
		}

		if ($timer_00 >= $now_bord){
		
			$timer_show .= "<input id='timer_pause' name='timer' type='radio' value='pause' onClick='timerAndDateOldStatus(0)'>	I pausen";
		}
	}
	
	if ($antal < $now_bord){
	
		echo "<br><div class='red_box'><b>Fullbokat på vald tid och datum!</b></div><br>";
		
		exit;
	}
	
	echo "<br><table width='270' border='0' cellspacing='0' cellpadding='0' align='center'>
		<tr>
			<td width='377'><strong>V&auml;lj tid:</strong><br>
			
			$timer_show 
						
			</td> 
		</tr>
	</table><br><br><div id='yes_no_change_time'></div>";

	if (isset($_GET['timer_old'])){
		echo "
		<br>
		<table width='270' border='0' cellspacing='0' cellpadding='0' align='center'>
			<tr>
				<td width='377'><strong>V&auml;lj tid:</strong><br>
				<a href='#'>Jag vill ändra tid</a>
				</td> 
			</tr>
		</table><br><br>";
	}	
	
	
	exit;

}

if (isset($_GET['get_old'])){
	
	ob_clean();
	header('Content-type: text/html; charset=iso8859-1');
	echo "result||";
	
	$boknr = $_GET['get_old'];
	
	$_SESSION['bord_boknr_old'] = $boknr;
	
	$sql = db_connect("SELECT * FROM borden WHERE bokningsnr = '$boknr'");
	$tid = mysql_result($sql, 0, 'min');
	$antal = mysql_result($sql, 0, 'antpers');
	$showid = mysql_result($sql, 0, 'Shownr');
	$betald = mysql_result($sql, 0, 'betald');
	
	//Show
	$sql = db_connect("SELECT datum, namn FROM shows WHERE showid = '$showid'");
	$datum = mysql_result($sql, 0, 'datum');
	$namn = mysql_result($sql, 0, 'namn');
	
	 if ($tid == "60" || $tid == "30"){
	 
	 	$tid = $tid." min innan föreställning";
	 }

	 else{
	 
	 	$tid ="I pausen";
	 }
	
	?>
<table width="508" border="0" cellpadding="0" cellspacing="0" class="b_box">
      <tr>
        <td height="16" colspan="2" align="center" valign="top">
          <p align="center" class="b_box_titel"><span class="style2"><font face="Verdana">Bokning: <strong><?php echo $boknr; ?></strong></font></span></td>
  </tr>
      <tr>
        <td height="23" colspan="2" align="center" valign="top"><br>
		<?php 
			if ($betald > "3"){
						
				echo "<div class='green_box'><img src='/bokningar/icon/checkmark.png' width='13' height='12' border='0' title='Bokning Betald'> *Bokningen är betald</div><br><br>";
			}
			else{

				echo "<div class='red_box'><b>Din bokning är INTE betald&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='?old_pay=$boknr'><img src='bokningar/icon/money.png'> Betala med kort</a></b></div><br><br>";
			 } 
		?>
		</td>
      </tr>
      <tr>
        <td height="48" colspan="2" align="center" valign="middle" class="b_box">
		&Auml;ndra tidpunkt eller f&ouml;rest&auml;llning?<br>
		<div id="old_show_change"><strong><a href="#" onClick="getShowOld()">Klicka h&auml;r</a></strong></div>
		</td>
      </tr>
      <tr>
        <td height="12" colspan="2" align="center" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="28" align="center" valign="top">
		<strong><br><div id="old_bok_datum">Datum: <?php echo $datum; ?> </div></strong>
		</td>
        <td height="28" align="center" valign="top">
		<strong><br><div id="old_bok_showname">F&ouml;rest&auml;llning: <?php echo $namn; ?> </div></strong>
		</td>
      </tr>
      <tr>
        <td height="30" colspan="2" align="center" valign="middle"><br></td>
      </tr>
      <tr>
        <td height="51" colspan="2" align="center" valign="middle" class="b_box">
		<span class="style1">
		<div id="old_bok_time">Tid: <?php echo $tid; ?></div>
		<br>
        <br>
		Antal personer: <?php echo $antal; ?> st 
		</span>
		</td>
      </tr>
      <tr>
        <td height="43" colspan="2" align="center" valign="middle"><hr></td>
      </tr>
      <tr>
        <td width="258" height="77" align="center" valign="top"><div class="divbox2" id="old_show_bord"> <strong><br>
		  Reserverade bord:</strong> <br>
		<?php 
			$sql = db_connect("SELECT * FROM borden WHERE bokningsnr = '$boknr'");
			while($row = mysql_fetch_array($sql)){
				
				$bord = $row['Bordnr'];
				echo "Bord: $bord<br>";
			}
		?>
        </div></td>
        <td width="248" height="77" align="center" valign="top">		
		<div class="divbox2">
		<strong><br>Beställda artiklar:</strong> <br>
		<?php 
			$sql = db_connect("SELECT * FROM servering WHERE boknr = '$boknr'");
			while($row = mysql_fetch_array($sql)){
				
				$vara = $row['produkt'];
				$st = $row['antal'];
				echo "$st st $vara<br>";
			}
		?>

		</div>	
	</td>
      </tr>
      <tr>
        <td height="2" colspan="2" align="center" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="9" colspan="2" align="center" valign="top"><img src="Bilder/printer.png" width="16" height="16"><a href="JavaScript:window.print();" class="style1">Skriv ut sidan </a></td>
      </tr>
      <tr>
        <td height="18" colspan="2" align="center" valign="top">&nbsp; </td>
      </tr>
      <tr>
        <td height="18" colspan="2" align="center" valign="top"><img src="Bilder/5-arrow_135.gif" width="12" height="13"><a href="?old" class="style1">G&aring; tillbaka > </a></td>
      </tr>
      <tr>
        <td height="18" colspan="2" align="center" valign="top">&nbsp;</td>
      </tr>
</table>
	<?php

	
	exit;

}

if (isset($_GET['old_change'])){

	ob_clean();
	sleep(1);
	header('Content-type: text/html; charset=iso8859-1');
	echo "result||";
	echo "<br><br>";
	echo "V&auml;lj datum ";
	echo "<select name='shows' id='shows'>";
	echo "<option value='0'>V&auml;lj datum</option>",
		
	$sql = db_connect("SELECT * FROM shows WHERE internet = '1' and datum >'2020-08-17' or internet = '5' and datum >'2023-08-18'  ORDER BY datum ASC");
	while($row = mysql_fetch_array($sql)){
		 			
		echo "<option value='{$row['showid']}'>{$row['datum']}</option>";
	}
			
	echo "</select>";
	echo "&nbsp;&nbsp;<input type='button' name='Submit' onClick='oldShowResultDate()' value='N&auml;sta'>";
	echo "<br><div id='old_show_result_data'></div><br>";
	
	
	exit;
}




if (isset($_GET['getmeny'])){

	ob_clean();
	header('Content-type: text/html; charset=iso8859-1');

	$_SESSION['bord_timer'] = $_GET['time'];
	$_SESSION['bord_antal'] = $_GET['antal'];
	$_SESSION['bord_num_artiklar'] = 0;
	
	$batch_sql_servering = "0";
	
	if ($_GET['time'] != "pause"){
	
		$batch_sql_servering = "1";
	}
	
	$tid = time_convert($_GET['time']);
	$bord = bord_check($_GET['antal'], "V", $tid, $_SESSION['bord_show']);

	if ($bord == "Nej"){
	
		echo "error||<img src='Bilder/down.gif.png'><b>Fullbokat Inte tillräckligt med bord för {$_GET['antal']} personer</b>";
		
		exit;
	}

	
	echo "result||";
	?>
		<table width="480" border="0" align="center" cellpadding="0" cellspacing="0" class="b_box">
		  <tr class="b_box_titel">
		    <td height="23" colspan="4" class="line">Välj från vår sommarmeny, minst en artikel per person<br>(Vin och starköl serveras endast i pausen)</td>
		    <td class="line">&nbsp;</td>
		    <td class="line">&nbsp;</td>
		    <td class="line">&nbsp;</td>
	      </tr>
          <?php 
		  $sql = db_connect("SELECT * FROM sommarmenyn WHERE t_k = 1  ORDER BY nr ASC");
		  
		  while ($row = mysql_fetch_array($sql)){
		  $idname = "produkt".$row['nr'];
		  ?>
		  <tr class="line">
            <td width="30" height="23" class="line"><?php echo $row['nr']; ?></td>
            <td width="280" class="line">
			<?php 
			if (file_exists("Bilder/meny/Servering{$row['nr']}.jpg")){
				echo "<a href='#' rel='balloon".$row['nr']."'>".$row['besk']."</a>";
			}
			
			else{
				echo $row['besk'];
			}
			?>
			&nbsp;
			</td>
            <td width="51" class="line">
			<?php 
			if ($row['pris'] > 0){
			echo $row['pris'].":-"; 
			}
			?>
			&nbsp;
			</td>
            <td width="65" class="line">
            <?php 

			if ($row['bestallningsbar'] == 1){
			
				$data_type = "text";
			}
			else{
				
				$data_type = "text";	
			}
			
			if ($batch_sql_servering == 1 && $row['sprit'] == "1"){ 
				
				$data_type = "hidden"; 
			}
			else{ 
			
				$data_type = "text";	
			}
			if ($row['bestallningsbar'] == 0){
					$data_type = "hidden"; 
			}
			?>
            
			<input id="prod<?php echo $row['nr']; ?>" name="produkt<?php echo $row['nr']; ?>" value="<?php echo $_POST[$idname]; ?>" type="<?php echo $data_type; ?>" size="2" maxlength="2" onChange="summa()">
            <?php if ($data_type == "text") echo "st"; else echo "&nbsp;"; ?>
            </td>
            <td width="66" class="line">
			<div id="balloon<?php echo $row['nr']; ?>" class="balloonstyle">
				<img src="/Bilder/meny/Servering<?php echo $row['nr']; ?>.jpg"/>
			</div>
			 
			</td>
		  </tr>
		  <?php } ?>
		  <tr>
		  	<td height="30" class="b_box_titel">&nbsp;</td>
			<td height="30" class="b_box_titel"><div id="totalcost">Summa: 0 Kr</div></td>
			<td height="30" colspan="3" class="b_box_titel"><span id="numartiklar"><?php echo $_SESSION['bord_num_artiklar']; ?></span> artiklar till <?php echo $_SESSION['bord_antal']; ?> personer </td>
		  </tr>
        </table>
		<br>
		
		<table width="440" border="0" align="center">
			<tr>
			<td align="left" valign="top">
				
				<input id="betala1" name="betala" type="radio" value="1"> 
					Ja tack, jag vill best&auml;lla och betalar med  swish eller kreditkort/bankkort<br><br> 
				<!--
				<input id="betala2" name="betala" type="radio" value="2"> 
					Ja tack, jag vill best&auml;lla och betala med Swish<br><br> 
				
				
				<span class="formInfo">
				<a href="?width=410&help=betala" class="jTip" id="four" name="Betala">?</a>
				</span>
				-->
				<input type="submit" name="pay" value=" G&aring; vidare ">
			</td>
			</tr>
		</table>
	
	<?php
	
	exit;
}

//Välj föreställning
if (isset($_GET['useshow'])){

	
	ob_clean();
	header('Content-type: text/html; charset=iso8859-1');
	
	$showid = $_GET['useshow'];
	
	
	$sql = db_connect("SELECT * FROM borden WHERE Shownr = '$showid' AND antpers = 0 LIMIT 1");
	
	if (mysql_num_rows($sql) > 0){
	
		$_SESSION['bord_show'] = $_GET['useshow'];
		
		exit;
	}
	else{
	
		echo "error|| Ingar bord lediga!";
		
		exit;
	}
}


//Hämta Boknings Data
if (isset($_GET['bokningsdata'])){

	ob_clean();
	header('Content-type: text/html; charset=iso8859-1');
	sleep(1);
	
	$del = isset($_SESSION['bord_show']) ? "<a href='?change_day'><img src='/images/cross.png' title='Byt F&ouml;rest&auml;llning' width='16' height='16'></a>":"&nbsp;";
	
	echo "result||";
		
		echo "
	    <table width='480' border='0' cellpadding='0' cellspacing='0' class='b_box'>
        <tr class='b_box_titel'>
        <td width='455'><p>F&ouml;rest&auml;llning </p></td>
        <td width='25'  align='right'>".$del."</td>
    	</tr>
    	<tr>
      	<td height='29'>
	    <div align='center'><div id='show_info'></div>";        


		if (!isset($_SESSION['bord_show'])){
			
			echo "V&auml;lj datum ";
			echo "<select name='shows' id='shows'>";
			echo "<option value='0'>V&auml;lj datum </option>";
		
			
			if(date("Hi") > "1500"){
				$today = date("Y-m-d"); 
				$today .= " 19 00";
				
				$sql = db_connect("SELECT * FROM shows WHERE internet = '1' and datum >'".$today."' or internet = '5' and datum >'".$today."'  ORDER BY datum ASC");	
			}			
			else		
				$sql = db_connect("SELECT * FROM shows WHERE internet = '1' and datum >'2020-08-17' or internet = '5' and datum >'2020-08-17'  ORDER BY datum ASC");
			
			
			while($row = mysql_fetch_array($sql)){
		 			
				echo "<option value='{$row['showid']}'>{$row['datum']}</option>";
			}
			
			echo "</select>";
			echo "&nbsp;&nbsp;<input type='button' name='Submit' onClick='useShow()' value='N&auml;sta'>";
		}
		else {
		
			$sql = db_connect("SELECT * FROM shows WHERE showid = '{$_SESSION['bord_show']}'");
			
			echo mysql_result($sql, 0, 'namn')." | ".mysql_result($sql, 0, 'dag');
		}
		
		echo "	
      	</div></td>
    	</tr>
  		</table>
  		<br>";
		
		if (isset($_SESSION['bord_show'])){
		
		echo "
  		<table width='480' border='0' cellpadding='0' cellspacing='0' class='b_box'>
    	<tr class='b_box_titel'>
      	<td colspan='2'><p>Tidpunkt</p></td>
    	</tr>
    	<tr>
        <td width='51' height='18'>
        <div align='center'></div></td>
        <td width='427'>&nbsp;</td>
    	</tr>
    	<tr>
        <td height='48'>&nbsp;</td>
        <td height='48'>
		Jag vill ha min best&auml;llning framdukad
		<br>
<br>
        <input id='timer_60' name='timer' type='radio' value='60' onClick='timeAndNum()'>
		1 timme f&ouml;re f&ouml;rest&auml;llningen<br>
		<input id='timer_30' name='timer' type='radio' value='30' onClick='timeAndNum()'>
		30 min f&ouml;re f&ouml;rest&auml;llningen<br>
		<input id='timer_pause' name='timer' type='radio' value='pause' onClick='timeAndNum()'>
		I pausen
		<br>
<br>	
	Klicka önskat alternativ.
		</td>
		
    	</tr>
        <tr>
        <td height='29' colspan='2'><hr></td>
    	</tr>
    	<tr align='center'>
      	<td height='29' colspan='2'><strong>Antal personer i s&auml;llskapet <br>
        </strong><br>
        Vi &auml;r 
        <select id='antal_p' name='antal_p' onChange='timeAndNum()'>";
		foreach(range(0, 50) as $di){
		
				echo "<option value='$di'>$di</option>";
		}
		echo "
        </select>        
         personer (3 platser vid varje bord) <br><br> <b></b></td>
    	</tr>
	    </table>";
		}
	
	
		exit;
}

//Hjälpfunktion
if (isset($_GET['help'])){ 
	
	//Header info
	header('Content-type: text/html; charset=iso8859-1');
	$how = $_GET['help'];
	$help = array();
	
	//Hjälp texter
	$help['help'] = "? betyder att det finns en förklaring";
	$help['boknr'] = "Boknings numret ni fick vid bokningen av föreställnings  biljetter";
	$help['email'] = "Mail adressen som ni använde vid bokningen av föreställnings  biljetter";
	$help['time'] = "";
	$help['pers'] = "";
	$help['meny'] = "Du måste välja minst en artikel från Sommarmenyn.<br><br> För att se en bild av artikeln dra musen över namnet på artikel";
	$help['betala'] = "Klicka på betala för att gå vidare till betalningen med kreditkort/bankkort<br> eller betala via bankgiro. <br><br> <b>Obs ! Din betalning via bankgiro måste vara oss tillhanda inom 5 dagar,<br> för att bordreservationen säkert ska finnas kvar.<b>";
	
	//Return help
	echo $help[$how];
	
	exit;
}

//Sommarmeny (Ver 0.2)///////////////////////////////////////////////////////////////////////////////////////////


function sendmail($email, $boknr, $text){

	$insert = $text.$boknr;
	$sql = db_connect("SELECT * FROM remail WHERE bokningsnr = '$insert'");
	
	if (mysql_num_rows($sql) == 0){
		db_connect("INSERT INTO remail (email, bokningsnr, time) VALUES ('$email', '$insert', NOW())");	
		
	}
}

// --------------------------------- Sendmail Swish --------------------------------------------

function sendmailSwish($email, $boknr, $text){

	$sql = db_connect("SELECT * FROM remail WHERE bokningsnr = '$insert'");
	
	if (mysql_num_rows($sql) == 0){
		db_connect("INSERT INTO remail (email, bokningsnr, time) VALUES ('$email', '$boknr', NOW())");			
	}
}

// --------------------------------------------------------------------------------------------

function siffror($str){
 
	$tecken = str_split($str);
	foreach($tecken as $id => $data){
		if (!preg_match('/^[0-9]{1,}$/', $data)){
			$data = "";
		}
		$fixt = $fixt.$data;
	}
	return $fixt;
}

//Letar fel
function error(){
	
	//Pris Kontrol
	foreach(range(1, 600) as $id){
		$pris = $pris + $_POST['produkt'.$id];
	}
	
	//Error inget valt från menyn
	if ($pris == 0){
		$error[] = "Du måste välja minst en artikel från Sommarmenyn!";
	}
	
	
	//Slut på error check skickar resultat
	return $error;
}


//Hämta data från shows
function shows($show, $data){
	
	$sql = db_connect("SELECT $data FROM shows WHERE showid = '$show'");
	
	$showdata = mysql_result($sql, 0, $data);
	
	return $showdata;
	
}

//Betala mot Bankgiro
function bankgiro($boknr){
	
	function bank_kassa($boknr){
		$sql = db_connect("SELECT * FROM servering where boknr = '$boknr' LIMIT 1");
		$summa = mysql_result($sql, 0, 'totsum');
		$custid = mysql_result($sql, 0, 'kundnr');
		
		if ($summa > 0){
		$datum = date("Y-m-d");
			db_connect("INSERT INTO kassa (datum, saltkr, moms, betalningssatt, bokningsnr, text, presnr, tidstampling, showid, kommentar, userid, dator, kontering, T_K, custid) VALUES ('$datum', '$summa', '0', '9', $boknr, 'servering', '', NOW(), '0', '', '992', '', '', 1, '$custid')");
			
			$status = "good";
		}
		else{ 
			$status = "fail";
		}
		
		return $status;
	}

}

//Convert html => sql time
function sql_tid($tid){
	$type = array("60" => "60", "30" => "30", "pause" => "0");
	return $type[$tid];
}

//Boka bord
function bord_boka($antal, $sida, $tid, $kundnr, $boknr, $biljetnr, $payway){
	
	//Rätt Sida
	$showid = $_SESSION['bord_show'];
	$sqltid = sql_tid($_POST['timer']);
	$antal_bord = ceil($antal / 3);
	
	wait("borden");
	
	$sql = db_connect("SELECT * FROM borden WHERE Shownr = '$showid' AND antpers = 0 AND VH = '$sida' AND tid = '$tid' LIMIT $antal_bord");
	
	$toma_bord = mysql_num_rows($sql);
		
	if ($toma_bord == $antal_bord){
		while($row = mysql_fetch_array($sql)){
			
			db_connect("UPDATE borden SET bokningsnr = '$boknr', kundnr = '$kundnr', antpers = '$antal', bokningsnrbiljett = '$biljetnr', betald = '$payway', bokadtid = NOW(), min = '$sqltid' WHERE idBorden = '{$row['idBorden']}'");
			}
	}
	
	//Andra Sidan
	elseif ($toma_bord < $antal_bord){
	
		//Byt Sida
		if ($sida == "H") $sida = "V";
		else  $sida = "H";
		
		//Sätt Bord
		$sql = db_connect("SELECT * FROM borden WHERE Shownr = '$showid' AND antpers = 0 AND VH = '$sida' AND tid = '$tid' LIMIT $antal_bord");
		
		while($row = mysql_fetch_array($sql)){
			echo $row['idBorden'];
			db_connect("UPDATE borden SET bokningsnr = '$boknr', kundnr = '$kundnr', antpers = '$antal', bokningsnrbiljett = '$biljetnr', betald = '$payway', bokadtid = NOW(), min = '$sqltid' WHERE idBorden = '{$row['idBorden']}'");
			
		}
	}
	
	//Fel
	else $status = "Fail UPDATE borden";
	
	go();
	
	return $status;
}

//Kontrolera att det finns lediga bord
function bord_check($antal, $sida, $tid, $showid){
	
	//Rätt Sida
	$sql = db_connect("SELECT * FROM borden WHERE Shownr = '$showid' AND antpers = 0 AND VH = '$sida' AND tid = '$tid'");
	
	//Fel Sida
	$else = db_connect("SELECT * FROM borden WHERE Shownr = '$showid' AND antpers = 0 AND VH != '$sida' AND tid = '$tid'");
	
	//Data
	$antal_bord = ceil($antal / 3);
	$toma_bord = mysql_num_rows($sql);
	$toma_bord2 = mysql_num_rows($else);
	

	//Bord På Rätt Sida
	if ($toma_bord >= $antal_bord){
		$ledigt = "Ja";
	}
	
	//Bord På Fel Sida
	elseif ($toma_bord2 >= $antal_bord){
		$ledigt = "Ja";
	}
	
	//Inte tillräckligt
	else {
		$ledigt = "Nej";
	}

	
	return $ledigt;
}

//Kontrol av sida i salongen
function sido_check($boknr){
	$sql = db_connect("SELECT seatid FROM bokings WHERE bokningsnr = '$boknr' ORDER BY seatid ASC LIMIT 1");
	
	$seat = mysql_result($sql, 0, 'seatid');
	$plats = $seat;
	
	foreach(range(1, 1700) as $id){
		if ($plats >= 60){
			$plats = $plats - 60;
			$rad++;
		} 
	}
	if ($plats >= 30) $status = "H";
	
	if ($plats < 30) $status  = "V";
	
	return $status;
}

//---------- Dibs - Skicka data ---------------

function dibs($amount, $orderid){
	
	$data = "amount=".$amount."&orderid=".$orderid;
	header("Location: https://shopwps.evarydberg.se/dibs_betala_bord_test.php?".$data);
	
	exit;
} 

//---------- Swish - Skicka data ---------------

function swish($amount, $orderid){
	
	$data = "amount=".$amount."&orderid=".$orderid;
	header("Location: https://shopwps.evarydberg.se/betala_swish.php?".$data);
	
	exit;
} 

//Summa från Menyn
function sommarmenyn(){
	
	$sql = db_connect("SELECT * FROM sommarmenyn WHERE t_k = 1 ORDER BY nr ASC");
	
	$num = mysql_num_rows($sql);
	while ($row = mysql_fetch_array($sql)){
		
		$nr = $row['nr'];
		$kr = $row['pris'];
		$antal = $_POST['produkt'.$nr];
		
		//Data
		$kassa['meny'][$nr] = $antal * $kr;
		$kassa['summa'] = $kassa['summa'] + $kassa['meny'][$nr];
		
	}
	return $kassa;
}

//Konverterar till db data
function time_convert($convert){
	$alt = array("60" => "F", "30" => "F", "pause" => "P");
	return $alt[$convert];
}

//Hämta information när bokning är klar
function done($boknr){
	
	//Om boknings nummer finns
	if (!empty($boknr)){

		//Hämta bord
		$sql = db_connect("SELECT * FROM borden WHERE bokningsnr = '$boknr' AND betald = '9'");
		
		//Bord hittades
		if (mysql_num_rows($sql) > 0){
			
			//Hämta bord info
			while($row = mysql_fetch_array($sql)){
				$bordnr = $row['idBorden'];
				$done['bord'][$bordnr]['nr'] = $row['Bordnr'];
				$done['bord'][$bordnr]['sida'] = $row['VH'];
				$done['antalbord']++;
				$done['pers'] = $row['antpers'];
				$done['tid'] = $row['min'];
				$done['showid'] = $row['Shownr'];
				$done['boknr'] = $row['bokningsnr'];
			}
			
			//Hämta show info
			$done['date'] = shows($done['showid'], "datum");
			$done['name'] = shows($done['showid'], "namn");
			
			//Convert time from sql to html
			$tider = array("0" => "I pausen av föreställningen", "30" => "30 min innan föreställningen", "60" => "1 timme innan föreställningen");
			$done['tid'] = $tider[$done['tid']];
			
			//Hämta sommar meny
			$sql = db_connect("SELECT * FROM servering WHERE boknr = '$boknr'");
			
			while($row = mysql_fetch_array($sql)){
				$done['meny'][$row['idServering']] = $row['antal']."st ".$row['produkt'];
			}
		}
	
		//Inget bord hittades
		else {
			$done = "Inget bokning hittades";
		}
	
		//Slut på done()
		return $done;
	}
}

//Hela funktionen
//function servering($summa, $domain){
function servering($payway){
	
	$error = error();	
	$sida = sido_check("0");
	$tid = time_convert($_POST['timer']);
	$showid = $_SESSION['bord_show'];
	$bokningnr = bokningsnr();
	$bord = bord_check($_POST['antal_p'], $sida, $tid, $showid);
	$kundnr = $_SESSION['custid'];
	//$payway = "1";

	
	if(empty($error) && $bord == "Ja"){
	
		if (isset($_SESSION['company']) && !empty($_SESSION['company'])){
		
			$tag_name = $_SESSION['company']." // ";
		}
		else{
		
			$tag_name = "";
		}
		
		// ------------- Insert Servering -------------------
		

		$sql = db_connect("SELECT * FROM sommarmenyn WHERE t_k = 1 ORDER BY nr ASC");
		$num = mysql_num_rows($sql);
		
		$kassa = sommarmenyn();

		
		while ($row = mysql_fetch_array($sql)){
			
			$info['nr'] = $row['nr'];
			$info['boknr'] = $bokningnr;
			$info['showid'] = $showid;
			$info['antpers'] = $_POST['antal_p'];
			$info['produkt'] = $row['besk'];
			$info['produktnr'] = $row['idSommarMenyn'];
			$info['kr'] = $row['pris'];
			$info['moms'] = "0";
			$info['summa'] = $kassa['meny'][$info['nr']];
			$info['totsum'] = $kassa['summa'];
			$info['bordnr'] = "1";
			$info['t_k'] = "1";
			$info['betalsatt'] = "1";
			$info['antal'] = siffror($_POST['produkt'.$info['nr']]);
			$info['custid'] = $_SESSION['custid'];
			$info['kund_namn'] = $tag_name.$_SESSION['access_name'];
				
			if($info['antal'] > 0){
				
				db_connect("INSERT INTO servering (nr, boknr, showid, antpers, produkt, produktnr, kr, moms, summa, totsum, vg, t_k, tid, betalsatt, antal, kundnr, kundnamn, min) VALUES ('{$info['nr']}', '{$info['boknr']}',  '{$info['showid']}', '{$info['antpers']}', '{$info['produkt']}', '{$info['produktnr']}', '{$info['kr']}', '{$info['moms']}', '{$info['summa']}', '{$info['totsum']}', '0', '{$info['t_k']}', NOW(), '{$info['betalsatt']}', '{$info['antal']}', '{$info['custid']}', '{$info['kund_namn']}', '$tid')");
			}

		}


		//----------------------------------------------------------
			
		//Updatera Bord
		
		$bokabord = bord_boka($_POST['antal_p'], $sida, $tid, $kundnr, $bokningnr, 0, $payway);
		
		//Klar
		$status['done'] = "yes";
		
		// ------------ Betala med DIBS ---------------
		if($payway == 1){
			dibs($kassa['summa'], $bokningnr);
		}
		
		// ------------ Betala mot Bankgiro  ---------------
		if ($payway == 2){
			sendmail("SERVERING", $bokningnr, "");
			header("Location: ?bankgiro=$bokningnr");			
			exit;
		}
		
		// ------------ Betala med Swish ---------------
		if($payway == 3){
			sendmailSwish("Swish Servering", $bokningnr, "");
			swish($kassa['summa'], $bokningnr);		
		}
		
		
	}
	
	//Fel Uppstår
	else{
		echo "Error!";
		if ($bord == "Nej") $error[] = "Det finns inte tillräckligt  med bord!";
		$status['error'] = $error;
		$status['done'] = "no";
	}
	return $status;
}

/*
if (isset($_POST['pay'])){

	//$servering = servering("", $domain);
	
}

if (isset($_POST['dibsPay'])){
	
	$payWay = 1;			// Dibs
	servering($payWay);
}

if (isset($_POST['swishPay'])){
	
	$payWay = 3;			// Swish
	servering($payWay);
}
*/


if (isset($_POST['betala'])){
	
	if($_POST['betala'] == 1){
		$payWay = 1;			// Dibs
		servering($payWay);		
	}else{
		$payWay = 3;			// Swish
		servering($payWay);		
	}
}


$done = done($_REQUEST['done']);

?>
<html>
	
<head>
	
<link rel="stylesheet" href="popup/main.css">    	

<style type="text/css">
	body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	}
div.inputform {
	width: 500px;
	background-color: #fafaff;
	margin-left: 40px;
	border: 1px solid #DDDDDD;
	padding-top: 8px;
	padding-bottom: 8px;
	padding-left: 8px;
}
div.row {
  clear: both;
  padding-top: 1px;
}
div.row span.label {
	float: left;
	width: 160px;
	text-align: right;
	padding-right: 3px;
}

div.row span.input {
	float: right;
	width: 310px;
	text-align: left;
}
input.submit {
	background-color: #00A;
	color: #FFF;
	width: 6em;
	font-size: 100%;
	border-left: 1px solid #AAE;
	border-top: 1px solid #AAE;
	border-right: 2px solid #AAF;
	border-bottom: 2px solid #AAF;
	margin-top: 3px;
	padding-top: 0.1em;
}
input.formtextok {
	background-color: #FAFAFA;
	padding-left: 2px;
	padding-right: 2px;
	border: 1px solid #CCCCAA;
	width: 240px;
	height: 1.4em;
	font-family: Verdana, Arial, Helvetica, Sans-Serif;
}
input.formnrok {
	background-color: #FAFAFA;
	padding-left: 2px;
	padding-right: 2px;
	border: 1px solid #CCCCAA;
	width: 40px;
	height: 1.4em;
	font-family: Verdana, Arial, Helvetica, Sans-Serif;
}
p.formtexterror {
	background-color: yellow;
}
input.formtexterror, {
	background-color: yellow;
	padding-left: 2px;
	padding-right: 2px;
	border: 1px solid #CCCCAA;
	width: 240px;
	height: 1.8em;
	font-family: Verdana, Arial, Helvetica, Sans-Serif;
	font-size: 80%;
}
input.formnrerror {
	background-color: yellow;
	padding-left: 2px;
	padding-right: 2px;
	border: 1px solid #CCCCAA;
	width: 40px;
	height: 1.8em;
	font-family: Verdana, Arial, Helvetica, Sans-Serif;
	font-size: 80%;
}
.box {
	border: 1px solid #000000;
	margin: 2px;
	padding: 2px;
	margin:4px;
}
.summa {
	border: 0px solid #FFFFFF;
	font-weight: bold;
	text-align: right;
}
.line {
	border-bottom-width: 1px;
	border-bottom-style: solid;
	border-bottom-color: #999999;
	padding-left: 4px;	
}
.balloonstyle{
position:absolute;
top: -500px;
left: 0;
padding: 3px;
visibility: hidden;
border:1px solid black;
font:normal 12px Verdana;
line-height: 18px;
z-index: 100;
background-color: white;
width: 170px;
/*Remove below line to remove shadow. Below line should always appear last within this CSS*/
filter: progid:DXImageTransform.Microsoft.Shadow(color=gray,direction=135,Strength=5);
}

#arrowhead{
z-index: 99;
position:absolute;
top: -500px;
left: 0;
visibility: hidden;
}
.error {
	font-weight: bold;
	color: #FF0000;
}
.good {
	font-weight: bold;
	color: #00CC00;
}
.style1 {font-weight: bold}

.input {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 11px;
	font-weight: bold;
	border: 1px solid #666666;
}
.style6 {font-size: 16px}
.divbox {
	width: 200px;
	height: 200px;
	border: 1px solid #333333;
	margin: 4px;
	padding: 4px;
}
.divbox2 {
	width: 240px;
	height: 200px;
	border: 1px solid #333333;
	margin: 4px;
	padding: 4px;
}
.style7 {color: #FFFFFF}
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}

.kund_id_box {
	margin: 10px;
	padding: 4px;
	height: 100px;
	width: 500px;
}

.head_box {
	margin: 5px;
	padding: 4px;
}

.b_box_titel {
	background-color: #000066;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight: bold;
	color: #FFFFFF;
	margin: 4px;
	padding-left: 2px;
	padding-top: 1px;
	padding-bottom: 1px;
}

.b_box {
	border: 1px solid #000066;
	background-color: #F7F7F7;
}

.b_box_extra {
	background-color: #F7F7F7;
	border-top: 1px solid #000066;
}

.error_box{
	width: 400px;
	height: 50px;
	margin: 4px;
	border: 1px solid #FF0000;
	text-align: center;
	padding-top: 10px;
	background-color: #FFE6E6;
}
.red_box {
	background-color: #FFA8A8;
	border-bottom:1px solid #333333;
}
.green_box {
	background-color: #B7FFB7;
	border-bottom:1px solid #333333;

}
</style>



<style>@import "css/global.css";</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.js"></script> 
<!-- <script src="js/jquery.js" type="text/javascript"></script> -->
<script src="js/jtip.js" type="text/javascript"></script>
<script src="/js/balloontip.js" type="text/javascript"></script>



<script type="text/javascript">

var xmlHttp; 

function createXMLHttpRequest() 
{ 
   if(window.ActiveXObject) 
   { 
      xmlHttp = new ActiveXObject("Microsoft.XMLHTTP"); 
   } 
   else if(window.XMLHttpRequest) 
   { 
      xmlHttp = new XMLHttpRequest(); 
   } 
} 


function loginKund() { 
	
	var kundnr = document.getElementById('kundnr').value;
	var email = document.getElementById('mail_kund').value;
	
	if (kundnr != "" && email != ""){
		
		document.getElementById('result_kund').innerHTML = "<b><img src='/bokningar/icon/roller.gif' width='16' height='16'> Hämta kund information...</b>"; 
		createXMLHttpRequest(); 
    	xmlHttp.onreadystatechange = loginKundState; 
    	xmlHttp.open("GET", "?login=kund&kundnr="+kundnr+"&email="+email, true); 
    	xmlHttp.send(null); 
	}
} 

function loginKundState() 
{ 
   if(xmlHttp.readyState == 4) 
   { 
      var response = xmlHttp.responseText; 
      var update = new Array(); 
      if(response.indexOf('||' != -1)) 
      { 
         update = response.split('||'); 
         if(update[0] == "error"){
		  
         	document.getElementById('result_kund').innerHTML = update[1]; 
         } 
         else { 
         
			 document.getElementById('kund_id').innerHTML = update[1]; 
			 ReuqestData();

         } 
      } 
   } 
}

function timeAndNum() { 
	
	var timer_60 = document.getElementById('timer_60').checked;
	var timer_30 = document.getElementById('timer_30').checked;
	var timer_pause = document.getElementById('timer_pause').checked;
	var timer = "";
	
	if (timer_60 == true){
	
		timer = "60";
	}

	if (timer_30 == true){
	
		timer = "30";
	}
	
	if (timer_pause == true){
	
		timer = "pause";
	}
			
	var antal = document.getElementById('antal_p').value;
	
	if (antal != "0" && timer != ""){
	
		document.getElementById('meny_data').innerHTML = "<b><img src='/bokningar/icon/roller.gif' width='16' height='16'> Hämta information...</b>"; 
		createXMLHttpRequest(); 
    	xmlHttp.onreadystatechange = timeAndNumState; 
    	xmlHttp.open("GET", "?getmeny&time="+timer+"&antal="+antal, true); 
    	xmlHttp.send(null);
	}
	else{
	
		document.getElementById('meny_data').innerHTML = "";
	}
} 

function timeAndNumState() 
{ 
   if(xmlHttp.readyState == 4) 
   { 
      var response = xmlHttp.responseText; 
      var update = new Array(); 
      if(response.indexOf('||' != -1)) 
      { 
         update = response.split('||'); 
         if(update[0] == "error"){
		  
			 document.getElementById('meny_data').innerHTML = "<div class='error_box'>"+update[1]+"</div>"; 
         } 
         else { 
         
			 document.getElementById('meny_data').innerHTML = update[1]; 
         } 
      } 
   } 
}

function useShow() { 
		
		var show = document.getElementById('shows').value;
	
		if (show > 0){
			
			createXMLHttpRequest(); 
    		xmlHttp.onreadystatechange = useShowState; 
    		xmlHttp.open("GET", "?useshow="+show, true); 
    		xmlHttp.send(null);
		} 
} 

function useShowState() 
{ 
   if(xmlHttp.readyState == 4) 
   { 
      var response = xmlHttp.responseText; 
      var update = new Array(); 
      if(response.indexOf('||' != -1)) 
      { 
         update = response.split('||'); 
         if(update[0] == "error"){
		  	
			document.getElementById('show_info').className='error_box';
         	document.getElementById('show_info').innerHTML = "<span class='error'><img src='Bilder/down.gif.png'>Ingen lediga bord hittades!</span>";
         } 
         else { 
         
			 ReuqestData();
			 
         } 
      } 
   } 
}

function getShowOld() { 

        document.getElementById('old_show_change').innerHTML = "<b><img src='/bokningar/icon/roller.gif' width='16' height='16'> Hämta boknings information...</b>";
		createXMLHttpRequest(); 
    	xmlHttp.onreadystatechange = getShowOldState; 

    	xmlHttp.open("GET", "?old_change", true); 
    	xmlHttp.send(null); 
} 

function getShowOldState() 
{ 
   if(xmlHttp.readyState == 4) 
   { 
      var response = xmlHttp.responseText; 
      var update = new Array(); 
      if(response.indexOf('||' != -1)) 
      { 
         update = response.split('||'); 
         if(update[0] == "result"){
			
         	document.getElementById('old_show_change').innerHTML = update[1];
         } 
      } 
   } 
}



function oldBord(boknr) { 

        document.getElementById('old_bok').innerHTML = "<b><img src='/bokningar/icon/roller.gif' width='16' height='16'> Hämta boknings information...</b>";
		createXMLHttpRequest(); 
    	xmlHttp.onreadystatechange = oldBordState; 

    	xmlHttp.open("GET", "?get_old="+boknr, true); 
    	xmlHttp.send(null); 
} 

function oldBordState() 
{ 
   if(xmlHttp.readyState == 4) 
   { 
      var response = xmlHttp.responseText; 
      var update = new Array(); 
      if(response.indexOf('||' != -1)) 
      { 
         update = response.split('||'); 
         if(update[0] == "result"){
			
         	document.getElementById('old_bok').innerHTML = update[1];
         } 
      } 
   } 
}

function timerAndDateOldStatus(timer){
		
		var show = document.getElementById('shows').value;
		document.getElementById('yes_no_change_time').innerHTML = "<b><img src='/bokningar/icon/roller.gif' width='16' height='16'> Hämta information...</b>"; 
		createXMLHttpRequest(); 
    	xmlHttp.onreadystatechange = timerAndDateOldStatusState; 
    	xmlHttp.open("GET", "?yes_no_change_time="+show+"&timer_old="+timer, true); 
    	xmlHttp.send(null); 
} 

function timerAndDateOldStatusState() 
{ 
   if(xmlHttp.readyState == 4) 
   { 
      var response = xmlHttp.responseText; 
      var update = new Array(); 
      if(response.indexOf('||' != -1)) 
      { 
         update = response.split('||'); 
         	
			document.getElementById('yes_no_change_time').innerHTML = update[1]; 
      } 
   } 
}

function acceptOldNewShow(){
	
		document.getElementById('old_bok_showname').innerHTML = "<b><img src='/bokningar/icon/roller.gif' width='16' height='16'> Hämta information...</b>"; 
		document.getElementById('old_bok_time').innerHTML = "<b><img src='/bokningar/icon/roller.gif' width='16' height='16'> Hämta information...</b>"; 
		document.getElementById('old_bok_datum').innerHTML = "<b><img src='/bokningar/icon/roller.gif' width='16' height='16'> Hämta information...</b>"; 
		document.getElementById('old_show_change').innerHTML = "<b><img src='/bokningar/icon/roller.gif' width='16' height='16'> Hämta information...</b>"; 
		document.getElementById('old_show_bord').innerHTML = "<b><img src='/bokningar/icon/roller.gif' width='16' height='16'> Hämta information...</b>"; 
	
		createXMLHttpRequest(); 
    	xmlHttp.onreadystatechange = acceptOldNewShowState; 
    	xmlHttp.open("GET", "?accept_old_new_show_state", true); 
    	xmlHttp.send(null); 
	
}

function acceptOldNewShowState() 
{ 
   if(xmlHttp.readyState == 4) 
   { 
      var response = xmlHttp.responseText; 
      var update = new Array(); 
      if(response.indexOf('||' != -1)) 
      { 
         update = response.split('||'); 
        if (update[0] == "error"){
		
			document.getElementById('old_show_change').innerHTML = update[1]; 
		} 
		else{
			
			document.getElementById('old_bok_showname').innerHTML = update[2]; 
			document.getElementById('old_bok_time').innerHTML = update[3]; 
			document.getElementById('old_bok_datum').innerHTML = update[1];
			document.getElementById('old_show_bord').innerHTML = update[4]; 
			document.getElementById('old_show_change').innerHTML = update[5]; 
		}
      } 
   } 
}


function oldShowResultDate(){
	
		var show = document.getElementById('shows').value;
		document.getElementById('old_show_result_data').innerHTML = "<b><img src='/bokningar/icon/roller.gif' width='16' height='16'> Hämta information...</b>"; 
		createXMLHttpRequest(); 
    	xmlHttp.onreadystatechange = oldShowResultDateState; 
    	xmlHttp.open("GET", "?old_show_result_data="+show, true); 
    	xmlHttp.send(null); 
} 

function oldShowResultDateState() 
{ 
   if(xmlHttp.readyState == 4) 
   { 
      var response = xmlHttp.responseText; 
      var update = new Array(); 
      if(response.indexOf('||' != -1)) 
      { 
         update = response.split('||'); 
         	
			document.getElementById('old_show_result_data').innerHTML = update[1]; 
      } 
   } 
}

function loginBokning() { 
	
	var kundnr = document.getElementById('boknr').value;
	var email = document.getElementById('mail_bokning').value;
	
	if (kundnr != "" && email != ""){
		
		document.getElementById('result_bokning').innerHTML = "<b><img src='/bokningar/icon/roller.gif' width='16' height='16'> Hämta boknings information...</b>"; 
		createXMLHttpRequest(); 
    	xmlHttp.onreadystatechange = loginBokningState; 
    	xmlHttp.open("GET", "?login=bokning&boknr="+kundnr+"&mail_bokning="+email, true); 
    	xmlHttp.send(null); 
	}
} 

function loginBokningState() 
{ 
   if(xmlHttp.readyState == 4) 
   { 
      var response = xmlHttp.responseText; 
      var update = new Array(); 
      if(response.indexOf('||' != -1)) 
      { 
         update = response.split('||'); 
         if(update[0] == "error"){
		  
         	document.getElementById('result_bokning').innerHTML = update[1]; 
         } 
         else { 
         
			 document.getElementById('kund_id').innerHTML = update[1]; 
			 ReuqestData();
         } 
      } 
   } 
}



function ReuqestData() { 

		document.getElementById('result_reservera').innerHTML = "<b><img src='/bokningar/icon/roller.gif' width='16' height='16'> Hämta information...</b>"; 
		createXMLHttpRequest(); 
    	xmlHttp.onreadystatechange = ReuqestDataState; 
    	xmlHttp.open("GET", "?bokningsdata", true); 
    	xmlHttp.send(null); 
} 

function ReuqestDataState() 
{ 
   if(xmlHttp.readyState == 4) 
   { 
      var response = xmlHttp.responseText; 
      var update = new Array(); 
      if(response.indexOf('||' != -1)) 
      { 
         update = response.split('||'); 
         if(update[0] == "error"){
		  
         	document.getElementById('result_reservera').innerHTML = update[1]; 
         } 
         else { 
         
			 document.getElementById('result_reservera').innerHTML = update[1]; 
         } 
      } 
   } 
}

</script>
<?php 
$sql = db_connect("SELECT * FROM sommarmenyn WHERE  t_k = 1 ORDER BY nr ASC");

$num = mysql_num_rows($sql);
$id = "";

while ($row = mysql_fetch_array($sql)){
	
	$nr++;
	if ($nr == $num){
	
		$var = $var."id".$row['nr'];
		$id = $id."id{$row['nr']} = document.getElementById('prod{$row['nr']}').value*{$row['pris']};\n";
		$antalid = $antalid."var antal{$row['nr']} = document.getElementById('prod{$row['nr']}').value*1;\n";
		$antal_artiklar = $antal_artiklar."antal{$row['nr']}";
		$summa = $summa."id{$row['nr']}";
	}
	
	else{
	
		$var = $var."id".$row['nr'].",";
		$id = $id."id{$row['nr']} = document.getElementById('prod{$row['nr']}').value*{$row['pris']};\n";
		$antalid = $antalid."var antal{$row['nr']} = document.getElementById('prod{$row['nr']}').value*1;\n";
		$antal_artiklar = $antal_artiklar."antal{$row['nr']}+";
		$summa = $summa."id{$row['nr']}+";
	}
	
}
?>
<script type="text/javascript">


function summa(){
	var summa;
	var antal;
  	var <?php echo $var; ?>;
   <?php echo $id; ?>
   <?php echo $antalid; ?>
  
   summa = <?php echo $summa; ?>;
   antal = <?php echo $antal_artiklar; ?>;
   
	document.getElementById('totalcost').innerHTML = "Summa: "+summa+" Kr";
	document.getElementById('numartiklar').innerHTML = antal;

}

function formCheck(){

	var artiklar = document.getElementById('numartiklar').innerHTML*1;
	var personer = document.getElementById('antal_p').value;
	
	var timer_60 = document.getElementById('timer_60').checked;
	var timer_30 = document.getElementById('timer_30').checked;
	var timer_pause = document.getElementById('timer_pause').checked;
	var timer = "";
	var error = "";
	var betala1 = document.getElementById('betala1').checked;
	var betala2 = document.getElementById('betala2').checked;
	
	if (timer_60 == true){
	
		timer = "60";
	}

	if (timer_30 == true){
	
		timer = "30";
	}
	
	if (timer_pause == true){
	
		timer = "pause";
	}	
	
	if (artiklar < personer){
		
		alert("Ni måster välja minst en artikel per person!");
		error = "ariklar";
		return false;
	}
	
	else if (betala1 == false && betala2 == false){
	
		alert("Ni måster välja betalsätt!");
		error = "ariklar";
		return false;
	}
	else{
	
		return true;
	}

	
}

</script>
<title>Eva Rydberg</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<?php 
if (isset($_SESSION['custid'])) {

	echo "<body onLoad='ReuqestData()' bgcolor=#FFFFFF background='Bilder/bakgrundeva.gif' link='#000000' alink='#000000' vlink='#000000'>";
} 
else { ?>

	<body onLoad='' bgcolor=#FFFFFF background='Bilder/bakgrundeva.gif' link='#000000' alink='#000000' vlink='#000000'>
<?php
} 
?>



<!-- --------- AGREEMENT ----------- -->
    
<?php
	if(!isset($_SESSION['agreement'])){
?>	
	
		<link rel="stylesheet" href="popup/main.css">    		
		<script src="popup/main_2.js"></script>
		
		<?php include("popup/agreement_text.html") ?>
	
<?php	
		$_SESSION['agreement'] = 1;				

	}
?>

<!-- ------------------------     -->




<form action="" method="post" onSubmit="return formCheck(this)">
	<?php if (!isset($_REQUEST['done']) && !isset($_REQUEST['bankgiro']) && !isset($_REQUEST['old'])) { ?>

	<table width="990" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="141" height="638" align="center" valign="top"><font face="Verdana" size="2"><br>
      <br>
    <img border="1" src="Bilder/logga1.gif" width="110" height="123"></font></td>
    <td width="613" align="center" valign="top">
		
		<div id="head" class="head_box">
			<strong><font face="Verdana" color="#000066"><span class="style6">Bokningen är inte öppnad ännu<br>Bokning av bord i Teatercaf&eacute;et och best&auml;llning fr&aring;n menyn</span></font></strong>
			<br>
			<br>
			<p>Boka bord och best&auml;ll fr&aring;n v&aring;r sommarmeny, <br>
		    s&aring; &auml;r allt framdukat och klart n&auml;r du kommer till Teatercaf&eacute;et.</p>
		</div>
		
	<div id="kund_id" class="kund_id_box">
	<?php if (!isset($_SESSION['custid'])) { ?>

<!-- 
	  <table width="490" border="0" cellpadding="0" cellspacing="0" bgcolor="#F7F7F7" class="b_box">
        <tr>
          <td height="20" colspan="3" class="b_box_titel"> Har du bokat biljetter kan du logga in med bokningsnumret h&auml;r:</td>
          </tr>
        <tr>
          <td width="9">&nbsp;</td>
          <td width="242">&nbsp;</td>
          <td width="237">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Ditt Biljettbokningsnummer:
            <br>            
            <input type="text" name="boknr" id="boknr" onKeyUp="startRequest(this.value)" />	
		  <span class="formInfo">
		  <a href="?width=375&help=boknr" class="jTip" id="one" name="Boknings Nummer">?</a>
		  </span>		  </td>
          <td> E-post <br>
            <input type="text" name="mail_bokning" id="mail_bokning" onKeyUp="startRequest(this.value)" />
		  <span class="formInfo">
		  <a href="?width=375&help=boknr" class="jTip" id="one" name="Boknings Nummer">?</a>
		  </span>			</td>
        </tr>
        <tr align="center">
          <td>&nbsp;</td>
          <td height="60"><div id="result_bokning"></div></td>
          <td height="60" align="center"><input type="button" onClick="loginBokning()" name="Button" value="H&auml;mta bokningsinfo"></td>
        </tr>
      </table>
-->
	  <br>
	  <table width="490" border="0" cellpadding="0" cellspacing="0" bgcolor="#F7F7F7" class="b_box">
        <tr>
          <td height="20" colspan="3" class="b_box_titel"><strong>Har du ett kundnummer kan du logga in med kundnummer eller lösenord h&auml;r:</strong></td>
          </tr>
        <tr>
          <td width="10">&nbsp;</td>
          <td width="235">&nbsp;</td>
          <td width="237">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
          <td>Kundnummer eller lösenord:<br>
            <input type="text" name="kundnr" id="kundnr" onKeyUp="startRequest(this.value)" />
					  <span class="formInfo">
		  <a href="?width=375&help=boknr" class="jTip" id="one" name="Boknings Nummer">?</a>
		  </span>			</td>
          <td>E-post <br> 
            <input type="text" name="mail_kund" id="mail_kund" onKeyUp="startRequest(this.value)" />
		 <span class="formInfo">
		  <a href="?width=375&help=boknr" class="jTip" id="one" name="Boknings Nummer">?</a>
		  </span>			</td>
        </tr>
        <tr align="center">
          <td>&nbsp;</td>
          <td height="60"><div id="result_kund"></div><br>
            <a href="/userlogin.php?password">Glömt Er kundnummer? > </a></td>
          <td height="60" align="center"><input type="button" name="kundinfo" onClick="loginKund()" value="H&auml;mta kundinfo"></td>
        </tr>
      </table>
	  <br>
	  <table width="490" border="0" cellpadding="0" cellspacing="0" bgcolor="#F7F7F7" class="b_box">
        <tr class="b_box_titel">
          <td width="186"><strong>Här kan du registera dig som kund</strong></td>
          <td width="225">&nbsp;</td>
        </tr>
        <tr align="center">
          <td colspan="2"><br>
		  <a href="register.php?back=bord_test.php"><strong>Ny kund: </strong> </a><br>
            
			</td>
          </tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
      </table>
	  <?php } else { 

  		$sql = db_connect("SELECT * FROM customer WHERE teleno = '{$_SESSION['custid']}'");
		
  		//Kund hittad
		$company = mysql_result($sql, 0, 'foretag'); 
		$name = mysql_result($sql, 0, 'fornamn')." ".mysql_result($sql, 0, 'name'); 
		$ort = mysql_result($sql, 0, 'city'); 
		$bokningar = mysql_result($sql, 0, 'teleno'); 
		$kundtyp = mysql_result($sql, 0, 'kundtyp'); 
		$custid = mysql_result($sql, 0, 'teleno');
		$email = mysql_result($sql, 0, 'email');
		$kundtyp = "V";
	  
	  ?>
		<div id="kund_box_info">
		  <table width="480" border="0" cellpadding="0" cellspacing="0" class="b_box">
		    <tr class="b_box_titel">
 		     <td colspan="3"><strong>Kundinfo:</strong></td>
		    </tr>
		    <tr>
 		     <td width="92" height="29">Kundnr <?php echo $custid; ?></td>
 		     <td width="250">E-post <?php echo $email; ?> </td>
 		     <td width="136"><a href="?del">Logga ut > </a></td>
 		   </tr>
			<tr>
			 <td height="29">&nbsp;</td>
 		     <td>Namn: <?php echo $name; ?> </td>
 		     <td>&nbsp;</td>
 		   </tr>
		   <?php
			$sql = db_connect("SELECT showid, datum FROM shows WHERE t_k = 1");
			
			while($row = mysql_fetch_array($sql)){
				
				$id = $row['showid'];
				$datum = $row['datum']; 
				$show_info[$id] = $row['datum'];
			}
			
		   $sql = db_connect("SELECT * FROM borden WHERE kundnr = '{$_SESSION['custid']}'");
		   if (mysql_num_rows($sql)){
		   
		   while($row = mysql_fetch_array($sql)){
		   	
				$boknr = $row['bokningsnr'];
				$showid = $row['Shownr'];
				
				if (!isset($gammal_bokning[$boknr]) && $show_info[$showid] >= date("Y-m-d")){
		   			
					$antal_gammla++;
					$gammal_bokning[$boknr] = $boknr;
				}
		   }
		   ?>
			<tr class="b_box_extra">
			  <td height="29" class="b_box_extra">&nbsp;</td>
			  <td align="center" class="b_box_extra"><p class="style1">Du har <?php echo $antal_gammla; ?> tidigare bokningar,  <a href="?old">Visa > </a> </p></td>
			  <td class="b_box_extra">&nbsp;</td>
			  </tr>
			  <?php } ?>
		  </table>
		</div>
	  <?php } ?>
	</div>
	<div id="old"></div>
	<div id="result_reservera"></div>	
	<div id="meny_data" class="kund_id_box"></div>
	</td>
    <td width="236" align="center" valign="top"><span class="style1"><br><br><a href="userlogin.php" class="style7"> Tillbaka > </a> </span></td>
  </tr>
</table>
</form>
<?php } ?>
<?php if (isset($_REQUEST['old'])) { ?>
<table width="980" border="0" cellspacing="0" cellpadding="0">
  <tr align="center" valign="top">
    <td width="142"><br>
      <font face="Verdana" size="2"><br>
    <img border="1" src="Bilder/logga1.gif" width="110" height="123"></font><br></td>
    <td width="612"><div id="head" class="head_box"> <strong><font face="Verdana" color="#000066"><span class="style6">Reservering av bord och best&auml;llning fr&aring;n Sommarmenyn.</span></font></strong> <br>
          <br>
	<p>Boka bord och best&auml;ll fr&aring;n v&aring;r sommarmeny, <br>
		    s&aring; &auml;r allt framdukat och klart n&auml;r du kommer till Teatercaf&eacute;et.</p>   </div>
	<div id="kund_id" class="kund_id_box">
	<?php if (!isset($_SESSION['custid'])) { ?>

	  <?php } else { 

  		$sql = db_connect("SELECT * FROM customer WHERE teleno = '{$_SESSION['custid']}'");
		
  		//Kund hittad
		$company = mysql_result($sql, 0, 'foretag'); 
		$name = mysql_result($sql, 0, 'fornamn')." ".mysql_result($sql, 0, 'name'); 
		$ort = mysql_result($sql, 0, 'city'); 
		$bokningar = mysql_result($sql, 0, 'teleno'); 
		$kundtyp = mysql_result($sql, 0, 'kundtyp'); 
		$custid = mysql_result($sql, 0, 'teleno');
		$email = mysql_result($sql, 0, 'email');
		$kundtyp = "V";
	  
	  ?>
      <div id="kund_box_info">
		  <table width="480" border="0" cellpadding="0" cellspacing="0" class='b_box'>
		    <tr class="b_box_titel">
 		     <td colspan="3"><strong>Kundinfo:</strong></td>
		    </tr>
		    <tr>
 		     <td width="92" height="29">Kundnr <?php echo $custid; ?></td>
 		     <td width="250">E-post <?php echo $email; ?> </td>
 		     <td width="136"><a href="?del">Logga ut > </a></td>
 		   </tr>
			<tr>
			 <td height="29">&nbsp;</td>
 		     <td>Namn: <?php echo $name; ?> </td>
 		     <td>&nbsp;</td>
 		   </tr>
			<tr>
			  <td height="29" class="b_box_extra">&nbsp;</td>
			  <td align="center" class="b_box_extra"><a href="bord_test.php"><strong>Tillbaka till boka bord > </strong> </a></td>
			  <td class="b_box_extra">&nbsp;</td>
		    </tr>
		  </table>
	  </div>
		<br>
		<br>
		<div id="old_bok">
			<table width="550" border="0" cellpadding="0" cellspacing="0" class="b_box">
				<tr>
					<td width="275" align="center" height="30"><img src="/bokningar/icon/money.png" width="16" height="16" border="0" title="Betala Bokning"> Betala bokning med kort</td>
					<td width="275" align="center" height="30"><img src='/bokningar/icon/checkmark.png' width='13' height='12' border='0' title='Bokning Betald'> Bokning är betald*</td>
				</tr>
				<tr>
					<td width="275" align="center" height="30" class="red_box">Rödmarkerade rader är obetalda bokningar</td>
					<td width="275" align="center" height="30"><img src='/images/cross.png' width='16' height='16' border='0'> Makulera obetald bokning</td>
				</tr>
			</table>
			<br>	
			<table width="550" border="0" cellpadding="0" cellspacing="0" class="b_box">
 			 <tr>
 		   		<td colspan="5" class="b_box_titel">Tidigare bord bokningar </td>
		      </tr>		
			<?php 
				$sql = db_connect("SELECT showid, datum FROM shows WHERE t_k = 1");
				
				while($row = mysql_fetch_array($sql)){
					
					$id = $row['showid'];
					$datum = $row['datum']; 
					$show_info[$id] = $row['datum'];
				}
				
				$sql = db_connect("SELECT * FROM borden WHERE kundnr = '{$_SESSION['custid']}'");
				while($row = mysql_fetch_array($sql)){
						$boknr = $row['bokningsnr'];
						$showid = $row['Shownr']; 
						if (!isset($boknr_go[$boknr]) && $show_info[$showid] >= date("Y-m-d")){
					?>
 				 	<tr class="<?php if ($row['betald'] < "4") echo "red_box"; else echo "green_box"; ?>">
 				   		<td width="120" height="30" class="<?php if ($row['betald'] < "4") echo "red_box"; else echo "green_box"; ?>">Bokningsnr:<?php echo $row['bokningsnr']; ?> | </td>
				    	<td width="175" height="30" class="<?php if ($row['betald'] < "4") echo "red_box"; else echo "green_box"; ?>">Datum: <b><?php echo $show_info[$showid]; ?> | </b></td>
 					    <td width="125" height="30" class="<?php if ($row['betald'] < "4") echo "red_box"; else echo "green_box"; ?>">Antal personer: <?php echo $row['antpers']; ?> |</td>
	 					    <td width="95" height="30" align="center" class="<?php if ($row['betald'] < "4") echo "red_box"; else echo "green_box"; ?>"><span class="style1"><a href="#" onClick="oldBord('<?php echo $row['bokningsnr']; ?>')">Visa bokning |</a></span></td>
 				 	    <td align="center" class="<?php if ($row['betald'] < "4") echo "red_box"; else echo "green_box"; ?>">
						<?php 
						if ($row['betald'] > "3"){
						
							echo "<img src='/bokningar/icon/checkmark.png' width='13' height='12' border='0' title='Bokning Betald'>";
						}
						else{
						?>
						<a href="?old_pay=<?php echo $row['bokningsnr']; ?>"><img src="/bokningar/icon/money.png" width="16" height="16" border="0" title="Betala Bokning"></a>
						<?php } ?>
						</td>
					    <td align="center" class="<?php if ($row['betald'] < "4") echo "red_box"; else echo "green_box"; ?>">						<?php 
						if ($row['betald'] > "3"){
						
							echo "";
						}
						else{
						?>
						<a href="?del_old_bord=<?php echo $row['bokningsnr']; ?>"><img src="/images/cross.png" width="16" height="16" border="0" title="Ta bort Bokning"></a>
						<?php } ?></td>
 				 	</tr>
				 	<?php 
					$boknr_go[$boknr] = $boknr;
				 	}
				 } 
				 ?>
			</table>



	  </div>
	  		 <?php } ?>

	</div>
	</td>
    <td width="226"><span class="style1"><br><br><a href="bord_test.php" class="style7"> Tillbaka > </a></span></td>
  </tr>
</table>

	<?php } ?>
	<?php if (isset($_REQUEST['done'])) { ?>
    <table border="0" width="978" cellspacing="0" cellpadding="0">
      <tr>
        <td height="367" colspan="2" rowspan="19" valign="top">
          <p align="center"><font face="Verdana" size="2"> <br>
            <br>
            <br>
            <img border="1" src="Bilder/logga1.gif" width="110" height="123"></font>          </td>
        <td height="16" colspan="2" align="center" valign="top">
        <p align="center"><span class="head_box"><strong><font face="Verdana" color="#000066"><span class="style6">Reservering av bord och best&auml;llning fr&aring;n Sommarmenyn.</span></font></strong></span></td>
        <td width="218" rowspan="19" align="left" valign="top"><p class="style1"><br>
          <br>
          <br>
          <br>
        </p>
        </td>
        <td width="4" rowspan="19" valign="top">&nbsp;</td>
        <td width="6" rowspan="19" align="right" valign="top"> <br>
        </td>
      </tr>
      <tr>
        <td height="23" colspan="2" align="center" valign="top"><br>          <br>          <img src="Bilder/up.gif.png" width="16" height="16"><span class="style1"> Reservation  av bord &auml;r klar</span><br>
Er best&auml;llning &auml;r <strong>BETALD</strong><br>
          <br>          <br>
        <hr></td>
      </tr>
      <tr>
        <td height="48" colspan="2" align="center" valign="middle"><span class="style1">Bokningsnr: <?php echo $done['boknr']; ?></span></td>
      </tr>
      <tr>
        <td height="12" colspan="2" align="center" valign="top"><hr></td>
      </tr>
      <tr>
        <td height="28" align="center" valign="top"><strong><br>
        Datum: <?php echo $done['date']; ?> </strong></td>
        <td height="28" align="center" valign="top"><strong><br>
        F&ouml;rest&auml;llning: <?php echo $done['name']; ?></strong></td>
      </tr>
      <tr>
        <td height="30" colspan="2" align="center" valign="middle"><hr></td>
      </tr>
      <tr>
        <td height="51" colspan="2" align="center" valign="middle"><span class="style1">Tid: <?php echo $done['tid']; ?><br>
            <br>
Antal personer: <?php echo $done['pers']; ?> st </span></td>
      </tr>
      <tr>
        <td height="43" colspan="2" align="center" valign="middle"><hr></td>
      </tr>
      <tr>
        <td width="286" height="77" align="center" valign="top">		
		<div class="divbox">
		<p><strong><br>
		<?php echo $done['antalbord']; ?>st Bord</strong><br>
		<?php 
		foreach($done['bord'] as $bord){
		if ($bord['sida'] == "V") $sida = "Vänster Sida";
		else $sida = "Höger Sida";
		echo "Bord nr ".$bord['nr']." ".$sida."<br>";
		}
		?>
		<br>
		<br>
</p>
	    </div>		</td>
        <td width="322" height="77" align="center" valign="top">		
		<div class="divbox2">		<strong><br>
	    Sommar meny:</strong> <br>
		<?php 
		foreach($done['meny'] as $meny){
		echo $meny."<br>";
		}
		?>
        <br>
</div>		  </td>
      </tr>
      <tr>
        <td height="2" colspan="2" align="center" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="2" colspan="2" align="center" valign="top"><p>Vi skickar ett mail som kvitto p&aring; din betalning. <br>
          V&auml;nligen ta med denna utskrift eller kvittot som kommer med e-post. </p>        </td>
      </tr>
      <tr>
        <td height="5" colspan="2" align="center" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="9" colspan="2" align="center" valign="top"><img src="Bilder/printer.png" width="16" height="16"><a href="JavaScript:window.print();" class="style1">Skriv ut sidan </a></td>
      </tr>
      <tr>
        <td height="18" colspan="2" align="center" valign="top">&nbsp; </td>
      </tr>
      <tr>
        <td height="18" colspan="2" align="center" valign="top"><img src="Bilder/5-arrow_135.gif" width="12" height="13"><a href="https://www.evarydberg.se" class="style1">G&aring; tillbaka till startsidan > </a></td>
      </tr>
      <tr>
        <td height="18" colspan="2" align="center" valign="top">&nbsp;</td>
      </tr>
    </table>
	<?php } ?>
	
		<?php if (isset($_REQUEST['bankgiro'])) { ?>
    <table border="0" width="978" cellspacing="0" cellpadding="0">
      <tr>
        <td height="367" colspan="2" rowspan="19" valign="top">
          <p align="center"><font face="Verdana" size="2"> <br>
            <br>
            <br>
            <img border="1" src="Bilder/logga1.gif" width="110" height="123"></font>          </td>
        <td height="16" colspan="2" align="center" valign="top">
        <p align="center"><span class="head_box"><strong><font face="Verdana" color="#000066"><span class="style6">Reservering av bord och best&auml;llning fr&aring;n Sommarmenyn.</span></font></strong></span></td>
        <td width="218" rowspan="19" align="left" valign="top"><p class="style1"><br>
          <br>
          <br>
          <br>
        </p>
        </td>
        <td width="4" rowspan="19" valign="top">&nbsp;</td>
        <td width="6" rowspan="19" align="right" valign="top"> <br>
        </td>
      </tr>
      <tr>
        <td height="23" colspan="2" align="center" valign="top"><br>          <br>          
        <img src="Bilder/up.gif.png" width="16" height="16"><span class="style1"> Bokningen &auml;r registrerad </span><br><br>
          <br>          <br>
        <hr></td>
      </tr>
      <tr>
        <td height="48" colspan="2" align="center" valign="middle"><span class="style1">Bokningsnr: <?php echo $_GET['bankgiro']; ?></span></td>
      </tr>
      <tr>
        <td height="12" colspan="2" align="center" valign="top"><hr></td>
      </tr>
      <tr>
        <td height="28" colspan="2" align="center" valign="top"><p><strong><br>
        </strong><strong> Tack f&ouml;r din reservation av Bord och best&auml;llning fr&aring;n serveringen. <br>
        <br>
        <br>
        Du f&aring;r strax ett mail med instruktioner hur du betalar din best&auml;llning. <br>
        <br>
        </strong></p>
          <hr>
          <strong>          <br>
          </strong><strong>
  Obs !<br>
  <br>
  Betalning ska vara oss tillhanda inom 5 dagar f&ouml;r att du ska garanteras plats vid bord. <br>
            </strong></td>
      </tr>
      <tr>
        <td height="18" colspan="2" align="center" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td height="18" colspan="2" align="center" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td height="18" colspan="2" align="center" valign="middle"><img src="Bilder/5-arrow_135.gif" width="12" height="13"><a href="https://www.evarydberg.se" class="style1">G&aring; tillbaka till startsidan > </a></td>
      </tr>
      <tr>
        <td width="286" height="18" align="center" valign="top">&nbsp;</td>
        <td width="322" height="18" align="center" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="2" colspan="2" align="center" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="2" colspan="2" align="center" valign="top"><p>&nbsp;</p>        </td>
      </tr>
      <tr>
        <td height="5" colspan="2" align="center" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="9" colspan="2" align="center" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="18" colspan="2" align="center" valign="top">&nbsp; </td>
      </tr>
      <tr>
        <td height="18" colspan="2" align="center" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td height="18" colspan="2" align="center" valign="top">&nbsp;</td>
      </tr>
    </table>
	<?php } ?>
</body>
<?php 
include "core/end_of_line.php";
?>