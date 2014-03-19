jQuery(document).ready(function($){
 
	$('#add-billboard-link').click(function(){
		$('#add-billboard-form').toggle();
	});
	$('#list-billboards').click(function(){
		$('#list-billboards-content').toggle();
	});
	
	
	//ustawienie wojewodztwa
	var wyb_wojewodztwo = $('.change_province .i_hidden').attr('value');
	$(".change_province select option:contains("+wyb_wojewodztwo+")").prop('selected', true);
	//ustawienie rozmiaru
	var wyb_rozmiar = $('.change_size .i_hidden').attr('value');
	$(".change_size select option:contains("+wyb_rozmiar+")").prop('selected', true);
	
	//potwierdzenie usunięcia
	$('.billboard_delete').click( function(e){
		e.preventDefault();
		var del_href = $(this).attr('href');
		$(this).after('<div class="alert">Jesteś pewien? <a href="' + del_href + '" class="confirm">tak</a> <a href="" class="reject">nie</a></div>');
		$('.alert .reject').on("click", function(f){
			f.preventDefault();
			$(this).parent('.alert').remove();
		});
		
		return false;
	});
	
    var custom_uploader;
 
 
    $('.upload_image_button').click(function(e) {
 
        e.preventDefault();
		var klikniety = $(this);
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            //('.upload_image').val(attachment.url);
			$(klikniety).prev('.upload_image').val(attachment.url);
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });
 
 
});
