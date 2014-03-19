<?php

function bs_reservations_zarzadzanie_tablicami() {
	if( $_POST['add_billboard'] ){
		if( $ulica = sanitize_text_field($_POST['ulica']) ) {} else echo ('Błąd: pole ulica wymagane<br>');
		if( $miejscowosc = sanitize_text_field($_POST['miejscowosc']) ) {} else echo('Błąd: pole miejscowość wymagane<br>');
		if( $wojewodztwo = sanitize_text_field($_POST['wojewodztwo']) );
		if( $rozmiar = sanitize_text_field($_POST['rozmiar']) );
		if( $street_view = esc_url($_POST['street_view']) ) {} else echo('Błąd: pole link do street view wymagane<br>');
		if( $zdjecie = esc_url($_POST['ad_image']) );
		if( !$zdjecie ){
			$zdjecie = 'http://reservations.swiatek.biz/wp-content/plugins/reservations/images/brak.jpg';
		}
		
		$values = array(
			'tablica_rozmiar' => $rozmiar,
			'tablica_miasto' => $miejscowosc,
			'tablica_ulica' => $ulica,
			'tablica_wojewodztwo' => $wojewodztwo,
			'tablica_zdjecie' => $zdjecie,
			'tablica_link' => $street_view
		);
		$pattern = array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
		);
		global $wpdb;
		if ($wpdb->insert( $wpdb->prefix."tablica", $values, $pattern ) ) echo ('<p class="success">Nowa tablica została dodana do bazy danych</p>');
	}
	if( $_POST['update_billboard'] ){
		if( $ulica = sanitize_text_field($_POST['ulica']) ) {} else echo ('Błąd: pole ulica wymagane<br>');
		if( $miejscowosc = sanitize_text_field($_POST['miejscowosc']) ) {} else echo('Błąd: pole miejscowość wymagane<br>');
		if( $wojewodztwo = sanitize_text_field($_POST['wojewodztwo']) );
		if( $rozmiar = sanitize_text_field($_POST['rozmiar']) );
		if( $street_view = esc_url($_POST['street_view']) ) {} else echo('Błąd: pole link do street view wymagane<br>');
		if( $zdjecie = esc_url($_POST['ad_image']) );
		if( !$zdjecie ){
			$zdjecie = 'http://reservations.swiatek.biz/wp-content/plugins/reservations/images/brak.jpg';
		}
		$id = sanitize_text_field($_POST['update_id']);
		
		$values = array(
			'tablica_rozmiar' => $rozmiar,
			'tablica_miasto' => $miejscowosc,
			'tablica_ulica' => $ulica,
			'tablica_wojewodztwo' => $wojewodztwo,
			'tablica_zdjecie' => $zdjecie,
			'tablica_link' => $street_view
		);
		$where = array(
			'tablica_id' => $id
		);
		$pattern_values = array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s'
		);
		$pattern_where = array ( '%d' );
		
		global $wpdb;
		if ($wpdb->update( $wpdb->prefix."tablica", $values, $where, $pattern_values, $pattern_where ) ) echo ('<p class="success">Tablica została zaktualizowana.</p>');
	}
	if( $id = intval( $_GET['usun'] ) ){
		$where = array(
			'tablica_id' => $id
		);
		global $wpdb;
		$pattern = array(
			'%d'
		);
		if ($wpdb->delete( $wpdb->prefix."tablica", $where, $pattern) ) echo ('<p class="success">Tablica została usunięta.</p>');
		
	}
	?>
	
	<div class='wrap'>
		<h2>Zarządzanie tablicami</h2>
		<p>lista tablic: dodaj rezerwacje|pokaż rezerwacje|edytuj|usuń</p>
		<div class="add-billboard">
			<h4 id="add-billboard-link" class="switcher">Dodaj nową tablicę</h4>
			<div class="add-billboard-content">
				<form action="" method="post" id="add-billboard-form">
					<fieldset>
						<label for="upload_image">zdjęcie</label>
						<input class="upload_image" type="text" name="ad_image" value="" placeholder="http://" />
						<input type="button" value="Wybierz zdjęcie" class="button-secondary upload_image_button" />
					</fieldset>
					<fieldset>
						<label for="ulica">ulica*</label>
						<input type="text" name="ulica" value="" placeholder="np. Mickiewicza" required />
					</fieldset>
					<fieldset>
						<label for="miejscowosc">miejscowość*</label>
						<input type="text" name="miejscowosc" value="" placeholder="np. Kraków" required />
					</fieldset>
					<fieldset>
						<label for="wojewodztwo">województwo*</label>
						<?php listaWojewodztw(); ?>
					</fieldset>
					<fieldset>
						<label for="size">rozmiar*</label>
						<select name="rozmiar">
							<?php
							$rozmiary = get_option('bs_reservations_options');
							for($i=0; $i < count($rozmiary); $i++){
								echo("<option value=".$rozmiary[$i].">".$rozmiary[$i]."</option>");
							}
							?>
						</select>
					</fieldset>
					<fieldset>
						<label for="street_view">Link do Street View*</label>
						<textarea name="street_view" rows="5" cols="50" placeholder="np. https://www.google.com/maps/place/Bazylika+Mariacka/@50.061941,19.938489,3a,75y,162.44h,100.27t/data=!3m5!1e1!3m3!1sXNIvqDwp0C656HyaYrQ9Jg!2e0!3e5!4m2!3m1!1s0x47165b11f53a5077:0xdd371e3071dcbf32" required ></textarea>
					</fieldset>
					<fieldset>
						<p>* pola wymagane</p>
					</fieldset>
					<fieldset>
						<input type="hidden" name="add_billboard" value="true" />
						<input type="submit" value="Dodaj" class="button-primary" />
					</fieldset>
				</form>
			</div>
			<h4 id="list-billboards" class="switcher">Lista tablic</h4>
			<div id="list-billboards-content">
						<?php
						global $wpdb;
						$i=1;
						$tablica = $wpdb->prefix.'tablica';
						$result = $wpdb->get_results("SELECT * FROM $tablica");
						$edytuj_link = menu_page_url("reservations/reservations.php", 0);
						if( $id_edytuj = sanitize_text_field($_GET['edytuj']) ){
							listBillboard($id_edytuj);
						} else{
							listAllBillboards();
						}
						?>
			</div>
		</div>
	</div>
	<?
}

function listAllBillboards(){
	?>
	<table>
		<thead>
			<tr>
				<td>Lp.</td><td>Ulica</td><td>Miasto</td><td>Województwo<td>Rozmiar</td><td>Zdjęcie</td><td>Mapa</td><td>Rezerwacje</td><td>Edytuj</td><td>Usuń</td>
			</tr>
		</thead>
		<tbody>
			<?php
			global $wpdb;
			$i=1;
			$tablica = $wpdb->prefix.'tablica';
			$result = $wpdb->get_results("SELECT * FROM $tablica");
			$edytuj_link = menu_page_url("reservations/reservations.php", 0);
			$reserve_link = menu_page_url("reservations/reservations.php_zarzadzanie_rezerwacjami", 0);
			foreach ( $result as $billboard ) {
				echo("<tr><td>".$i++."</td><td>".$billboard->tablica_ulica."</td><td>".$billboard->tablica_miasto."</td><td>".$billboard->tablica_wojewodztwo."</td><td>".$billboard->tablica_rozmiar."</td><td><img src=".$billboard->tablica_zdjecie." width=150px /></td><td><a href=".$billboard->tablica_link." target='_blank'>link</a></td><td><a class='billboard_reserve' href=$reserve_link&reserve=".$billboard->tablica_id.">rezerwuj</a></td><td><a href=$edytuj_link&edytuj=".$billboard->tablica_id.">edytuj</a></td><td><a class='billboard_delete' href=$edytuj_link&usun=".$billboard->tablica_id.">usuń</a></td></tr>");
			}
		?>
		</tbody>
	</table>
<?php
}
function listBillboard($id){
	global $wpdb;
	$tablica = $wpdb->prefix.'tablica';
	$billboard = $wpdb->get_row("SELECT * FROM $tablica WHERE tablica_id = $id");
	?>
	<h3>Edycja tablicy</h3>
	<form action="<?php menu_page_url("reservations/reservations.php") ?>" method="post" id="update-billboard-form">
		<fieldset>
			<label for="image">zdjęcie</label>
			<input class="upload_image" type="text" name="image" value="<?php echo $billboard->tablica_zdjecie ?>" />
			<input type="button" value="Wybierz zdjęcie" class="button-secondary upload_image_button" />
		</fieldset>
		<fieldset>
			<label for="ulica">ulica*</label>
			<input type="text" name="ulica" value="<?php echo $billboard->tablica_ulica ?>" required />
		</fieldset>
		<fieldset>
			<label for="miejscowosc">miejscowość*</label>
			<input type="text" name="miejscowosc" value="<?php echo $billboard->tablica_miasto ?>" required />
		</fieldset>
		<fieldset class="change_province">
			<label for="wojewodztwo">województwo*</label>
			<input type="hidden" name="update_province" class="i_hidden" value="<?php echo $billboard->tablica_wojewodztwo ?>" />
			<?php listaWojewodztw() ?>
		</fieldset>
		<fieldset  class="change_size">
			<label for="size">rozmiar*</label>
			<input type="hidden" name="update_size" class="i_hidden" value="<?php echo $billboard->tablica_rozmiar ?>" />
			<select name="rozmiar">
				<?php
				$rozmiary = get_option('bs_reservations_options');
				for($i=0; $i < count($rozmiary); $i++){
					echo("<option value=".$rozmiary[$i].">".$rozmiary[$i]."</option>");
				}
				?>
			</select>
		</fieldset>
		<fieldset>
			<label for="street_view">Link do Street View*</label>
			<textarea name="street_view" rows="5" cols="50" required ><?php echo $billboard->tablica_link ?></textarea>
		</fieldset>
		<fieldset>
			<p>* pola wymagane</p>
		</fieldset>
		<fieldset>
			<input type="hidden" name="update_billboard" value="true" />
			<input type="hidden" name="update_id" value="<?php echo $id ?>" />
			<input type="submit" value="Aktualizuj" class="button-primary" />
		</fieldset>
	</form>
	
	<?php
}

function listaWojewodztw(){
	?>
	<select name="wojewodztwo">
		<option value="małopolskie">małopolskie</option>
		<option value="dolnośląskie">dolnośląskie</option>
		<option value="kujawsko-pomorskie">kujawsko-pomorskie</option>
		<option value="lubelskie">lubelskie</option>
		<option value="lubuskie">lubuskie</option>
		<option value="łódzkie">łódzkie</option>
		<option value="mazowieckie">mazowieckie</option>
		<option value="opolskie">opolskie</option>
		<option value="podkarpackie">podkarpackie</option>
		<option value="podlaskie">podlaskie</option>
		<option value="pomorskie">pomorskie</option>
		<option value="śląskie">śląskie</option>
		<option value="świętokrzyskie">świętokrzyskie</option>
		<option value="warmińsko-mazurskie">warmińsko-mazurskie</option>
		<option value="wielkopolskie">wielkopolskie</option>
		<option value="zachodniopomorskie">zachodniopomorskie</option>
	</select>
	<?php
}
?>