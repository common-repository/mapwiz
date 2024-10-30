jQuery(document).ready(function(e) {
	copyMapID();
	jQuery('.map').on('click', function(event) {
		jQuery(this).hide();
		jQuery(this).next().show();
	});
	
});
function copyMapID() {
	var copyBtn = jQuery('.code');
	copyBtn.on('click', function(event) {
		var content = jQuery(this);
		var range = document.createRange();
		range.selectNode(content[0]);
		window.getSelection().addRange(range);
		try {
			var successful = document.execCommand('copy');
			jQuery(this).addClass('p-success');
			setTimeout(function() {
				jQuery('.code').removeClass('p-success');
			}, 1000);
		}catch (err){
		// console.log('Oops, unable to copy');  
		}
		window.getSelection().removeAllRanges();
	});
}
