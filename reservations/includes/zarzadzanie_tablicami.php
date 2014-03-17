<?php

function bs_reservations_zarzadzanie_tablicami() {
	if($ulica = sanitize_text_field($_POST['ulica'])){
		if( $miejscowosc = sanitize_text_field($_POST['miejscowosc']) ) {} else echo('Błąd: pole miejscowość wymagane<br>');
		if( $wojewodztwo = sanitize_text_field($_POST['wojewodztwo']) );
		if( $rozmiar = sanitize_text_field($_POST['rozmiar']) );
		if( $street_view = esc_url($_POST['street_view']) ) {} else echo('Błąd: pole link do street view wymagane<br>');
		if( $zdjecie = esc_url($_POST['ad_image']) );
		
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
	?>
	
	<div class='wrap'>
		<h2>Zarządzanie tablicami</h2>
		<p>lista tablic: dodaj rezerwacje|pokaż rezerwacje|edytuj|usuń</p>
		<div class="add-billboard">
			<h4 id="add-billboard-link">Dodaj nową tablicę</h4>
			<div class="add-billboard-content">
				<form action="" method="post" id="add-billboard-form">
					<fieldset>
						<label for="upload_image">zdjęcie</label>
						<input id="upload_image" type="text" name="ad_image" value="" placeholder="http://" />
						<input id="upload_image_button" type="button" value="Wybierz zdjęcie" class="button-secondary" />
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
						<input type="submit" value="Dodaj" class="button-primary" />
					</fieldset>
				</form>
			</div>
			<h4 id="list-billboards">Lista tablic</h4>
			<div class="list-billboards-content">
				<table>
					<thead>
						<tr>
							<td>Lp.</td><td>Ulica</td><td>Miasto</td><td>Województwo<td>Rozmiar</td><td>Zdjęcie</td><td>Mapa</td><td>Rezerwacje</td><td>Edytuj</td><td>Usuń</td>
						</tr>
						<?php
						global $wpdb;
						$i=1;
						$tablica = $wpdb->prefix.'tablica';
						$result = $wpdb->get_results("SELECT * FROM $tablica");
						foreach ( $result as $billboard ) {
							echo("<tr><td>".$i++."</td><td>".$billboard->tablica_ulica."</td><td>".$billboard->tablica_miasto."</td><td>".$billboard->tablica_wojewodztwo."</td><td>".$billboard->tablica_rozmiar."</td><td><img src=".$billboard->tablica_zdjecie." width=150px /></td><td><a href=".$billboard->tablica_link." target='_blank'>link</a></td><td>rezerwacje</td><td>edytuj</td><td>usuń</td></tr>");
						}
						?>
					</thead>
					<tbody>
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?
}

?>