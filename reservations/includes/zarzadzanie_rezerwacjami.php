<?php

function bs_reservations_zarzadzanie_rezerwacjami() {
	if( $id = intval( $_POST['reserve_id'] ) ){
		if( $typ = sanitize_text_field($_POST['typ']) ) {} else echo ('Błąd: pole typ wymagane<br>');
		if( $dateFrom = $_POST['dateFrom'] ) {} else echo ('Błąd: pole od wymagane<br>');
		if( $dateTo = sanitize_text_field($_POST['dateTo']) ) {} else echo ('Błąd: pole do wymagane<br>');
		
		$dateFrom = date("Y-m-d", strtotime($dateFrom));
		$dateTo = date("Y-m-d", strtotime($dateTo));
		
		global $wpdb;
		$flag_free = 1;
		$tab_rezerwacja = $wpdb->prefix.'rezerwacje';
		$result = $wpdb->get_results("SELECT * FROM $tab_rezerwacja WHERE rezerwacja_id_tablicy=$id");
		foreach ( $result as $rezerwacja ) {
			if ($dateFrom >= $rezerwacja->rezerwacja_od && $dateFrom <= $rezerwacja->rezerwacja_do) $flag_free=0;
			if ($dateTo >= $rezerwacja->rezerwacja_od && $dateTo <= $rezerwacja->rezerwacja_do) $flag_free=0;
			if ($dateFrom <= $rezerwacja->rezerwacja_od && $dateTo >= $rezerwacja->rezerwacja_do) $flag_free=0;
			
			//usunięcie starych rezerwacji
			$currentDate = date("Y-m-d");
			if( $rezerwacja->rezerwacja_do < $currentDate ){
				$values = array(
					'rezerwacja_id' => $rezerwacja->rezerwacja_id				
				);
				$pattern = array(
					'%d'
				);
				$wpdb->delete( $tab_rezerwacja, $values, $pattern );
			}
		}
		
		if (!$flag_free){
			$powrot_link = menu_page_url("reservations/reservations.php", 0);
			echo('<p class="alert">Tablica w wybranym terminie jest niedostępna.</p><br><a href='.$powrot_link.'>Powrót do listy tablic</a>');
		} else{
			$values = array(
				'rezerwacja_id_tablicy' => $id,
				'rezerwacja_od' => $dateFrom,
				'rezerwacja_do' => $dateTo,
				'rezerwacja_typ' => $typ
			);
			$pattern = array(
				'%d',
				'%s',
				'%s',
				'%s'
			);
			if ($wpdb->insert( $wpdb->prefix."rezerwacje", $values, $pattern ) ) echo ('<p class="success">Nowa rezerwacja została zapisana</p>');
		}
	}
	
	if( $id = intval( $_GET['reserve'] ) ){
	?>
	<h3>Rezerwacja tablicy</h3>
	<table>
		<thead>
			<tr>
				<td>Ulica</td><td>Miasto</td><td>Województwo<td>Rozmiar</td><td>Zdjęcie</td><td>Mapa</td>
			</tr>
		</thead>
		<tbody>
			<?php
			global $wpdb;
			$tablica = $wpdb->prefix.'tablica';
			$billboard = $wpdb->get_row("SELECT * FROM $tablica WHERE tablica_id = $id");
			echo("<tr><td>".$billboard->tablica_ulica."</td><td>".$billboard->tablica_miasto."</td><td>".$billboard->tablica_wojewodztwo."</td><td>".$billboard->tablica_rozmiar."</td><td><img src=".$billboard->tablica_zdjecie." width=150px /></td><td><a href=".$billboard->tablica_link." target='_blank'>link</a></td></tr>");
		?>
		</tbody>
	</table>
	
	<form action="<?php menu_page_url("reservations/reservations.php_zarzadzanie_rezerwacjami") ?>" method="post" id="reserve-billboard-form">
		<fieldset>
			<label for="typ">typ</label>
			<select name="typ" required>
				<option value="zajeta">zajęte</option>
				<option value="rezerwacja">zarezerwowane</option>
			</select>
		</fieldset>
		<fieldset>
			<label for="dateFrom">od</label>
			<input type="text" name="dateFrom" id="dateFrom" value="" required />
		</fieldset>
		<fieldset>
			<label for="dateTo">do</label>
			<input type="text" name="dateTo" id="dateTo" value="" required />
		</fieldset>
		<fieldset>
			<p>* pola wymagane</p>
		</fieldset>
		<fieldset>
			<input type="hidden" name="reserve_id" value="<?php echo $id ?>" />
			<input type="submit" value="Dodaj" class="button-primary" />
		</fieldset>
	</form>
	
	<?php
	}
	
	if( $id = intval( $_GET['edytuj'] ) ){
	
	}
	if( $id = intval( $_GET['usun'] ) ){
	
	}
	?>
	<div class='wrap'>
		<h2>Lista rezerwacji</h2>
  	<table id="reservations_list" class="tablesorter">
  		<thead>
  			<tr>
  				<th>Ulica</th><th>Miasto</th><th>Województwo<th>Rozmiar</th><th>Zdjęcie</th><th>Mapa</th><th>Typ</th><th>Rezerwacja od</th><th class="dateFormat-dd-mm-yyyy">Rezerwacja do</th><th>edytuj</th><th>usuń</th>
  			</tr>
  		</thead>
  		<tbody>
    		<?php
    		global $wpdb;
    		$tab_rezerwacja = $wpdb->prefix.'rezerwacje';
    		$tab_tablica = $wpdb->prefix.'tablica';
    		$edytuj_link = menu_page_url("reservations/reservations.php_zarzadzanie_rezerwacjami", 0);
    		$result = $wpdb->get_results("SELECT * FROM $tab_rezerwacja, $tab_tablica WHERE tablica_id=rezerwacja_id_tablicy");
    		foreach ( $result as $rezerwacja ) {
    		  $dateFrom = $rezerwacja->rezerwacja_od;
    		  $dateTo = $rezerwacja->rezerwacja_do;
    		  echo("<tr><td>".$rezerwacja->tablica_ulica."</td><td>".$rezerwacja->tablica_miasto."</td><td>".$rezerwacja->tablica_wojewodztwo."</td><td>".$rezerwacja->tablica_rozmiar."</td><td><img src=".$rezerwacja->tablica_zdjecie." width=150px /></td><td><a href=".$rezerwacja->tablica_link." target='_blank'>link</a></td><td>".$rezerwacja->rezerwacja_typ."</td><td>".$dateFrom."</td><td>".$dateTo."</td><td><a href=$edytuj_link&edytuj=".$rezerwacja->rezerwacja_id.">edytuj</a></td><td><a class='reservations_delete' href=$edytuj_link&usun=".$rezerwacja->rezerwacja_id.">usuń</a></td></tr>");	
    			
    		}
        ?>
		</tbody>
	</table>
	<div id="pager">
	</div>
	</div>
	<?php
}
