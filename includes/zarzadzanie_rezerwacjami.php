<?php

function bs_reservations_zarzadzanie_rezerwacjami() {

	if( $id = intval( $_GET['reserve'] ) ){
		global $wpdb;
		$tablica = $wpdb->prefix.'tablica';
		$billboard = $wpdb->get_row("SELECT * FROM $tablica WHERE tablica_id = $id");
	?>
	
	<h3>Rezerwacja tablicy</h3>
	<form action="<?php menu_page_url("reservations/reservations.php_zarzadzanie_rezerwacjami") ?>" method="post" id="reserve-billboard-form">
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

	?>
	<div class='wrap'>
		<h2>Zarządzanie rezerwacjami</h2>
		<input type="submit" name="Save" value="Save Options" class="button-primary" />
		<input type="submit" name="Secondary" value="Secondary Button" class="button-secondary" />
	</div>
	<?php
}

?>