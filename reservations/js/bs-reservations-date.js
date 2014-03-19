jQuery(document).ready(function($){
 
	jQuery('#dateFrom').datepicker({
        dateFormat : 'dd-mm-yy'
    });	
	jQuery('#dateTo').datepicker({
        dateFormat : 'dd-mm-yy'
    });	
	
	$('#reserve-billboard-form').validate({
	    rules: {
			dateTo: { greaterThan: "#dateFrom" }
		}
	});
  
  $('#reservations_list').tablesorter({
    sortList: [[1,0]],
    headers: {
        4: {
            sorter: false 
        },
        5: { 
            sorter: false 
        } 
    },
  }); 
  
 
 
});
