jQuery(document).ready(function() {
	jQuery('.delete :button').click(function(){
		  if (confirm('Are you sure you want to delete this?')) {
			  var $id = jQuery(this).attr('id');
			  data = {
					action: 'delete_record',
					id: $id
			  };
			  
			  jQuery.post(ajaxurl, data, function(response){
				  	location.reload();
			  });
		  }
	});
	
});