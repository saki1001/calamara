jQuery(document).ready(function(){
	//if(jQuery('.custom-style-textarea').val() == '')
	//	jQuery.get('./css/' + this.val(), function(data) { $('#myTextbox').val(data); });

	var currentlyEdited = '.' + jQuery('.style-list').val();
	setVisible(currentlyEdited, true);

	jQuery('.style-list').change(function(){
		setVisible(currentlyEdited, false);

		currentlyEdited = '.' + jQuery('.style-list').val();
		setVisible(currentlyEdited, true);
	});

	function setVisible(element, visible){
		if(visible)
			jQuery(element).css({'display': 'inline'});
		else
			jQuery(element).css({'display': 'none'});
	}
});