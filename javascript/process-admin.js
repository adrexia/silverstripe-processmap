/*jslint browser: true*/
/*global $, jQuery*/

(function($) {
	$('#DecisionPoint').entwine({
		onmatch: function() {
			var self = this;
			this._super();
			this.showHide();
			this.on('change', function(){
				self.showHide();
			});
			
		},
		onunmatch: function() {
			this._super();
		},
		showHide: function(){
			if(this.find('input').is(':checked')){
				$('#Root_Main').find('.toggle-decide').slideDown();
				$('#Root_Main').find('.toggle-grid').slideUp();
			}else{
				$('#Root_Main').find('.toggle-decide').slideUp();
				$('#Root_Main').find('.toggle-grid').slideDown();
			}
		}
		
	});
}(jQuery));