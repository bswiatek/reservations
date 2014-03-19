<?php

function bs_reservations_zarzadzanie_rozmiarami_tablic() {
	if($rozmiar = sanitize_text_field($_POST['rozmiar'])){	
		$options = get_option('bs_reservations_options');
		array_push($options, $rozmiar);
		update_option('bs_reservations_options', $options);
	}
	if($rozmiar = sanitize_text_field($_POST['size_delete'])){
		$options = get_option('bs_reservations_options');
		if(($key = array_search($rozmiar, $options)) !== false) {
			unset($options[$key]);
		}
		$options = array_values($options);
		update_option('bs_reservations_options', $options);
	}

	?>
	<div class='wrap'>
		<h2>Zarządzanie rozmiarami tablic</h2>
		<div class="available-sizes">
			<h4>Dostępne rozmiary:</h4>
			<ol>
				<?php
				$rozmiary = get_option('bs_reservations_options');
				for($i=0; $i < count($rozmiary); $i++){
					echo("<li>".$rozmiary[$i]."</li>");
				}
				?>
			</ol>
		</div>
		<div class="add-size">
			<h4>Dodaj nowy rozmiar tablicy:</h4>
			<form action="" method="post">
				<input type="text" name="rozmiar" value="" placeholder="np. 12x3" />
				<input type="submit" value="Dodaj" />
			</form>
		</div>
		<div class="add-size">
			<h4>Usuń rozmiar tablicy:</h4>
			<form action="" method="post">
				<select name="size_delete">
					<?php
					for($i=0; $i < count($rozmiary); $i++){
						echo("<option value=".$rozmiary[$i].">".$rozmiary[$i]."</option>");
					}
					?>
				</select>
				<input type="submit" value="Usuń" />
			</form>
		</div>
	<?php
}