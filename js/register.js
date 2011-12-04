/**
 * @author Ezra Velazquez
 */

var REGISTER_VIEW = function() {
	var options = new Array();
	options['participation'] = 0;
	options['creativity'] = 0;
	options['disruptive'] = 0;
	options['late'] = 0;
	options['missing'] = 0;
	options['absent'] = 0;
	options['disrespectful'] = 0;
	options['teamwork'] = 0;
	options['persistence'] = 0;
	options['insight'] = 0;
	
	function mouseListener() {
		$('.container_options').delegate('.user_options', 'click', function() {
			if($(this).css("backgroundColor").indexOf("rgb(128, 128, 128)") != -1) {
				$(this).css("backgroundColor", "rgb(0, 192, 0)");
				options[$(this).attr('id').split("_")[1]] = 1;
			}
			else {
				$(this).css("backgroundColor", "rgb(128, 128, 128)");
				options[$(this).attr('id').split("_")[1]] = 0;
			}
		});
		
		$('#button_submit').click(function() {
			DB.registerParent($('#user_number').val(), $('#user_student_id').val(), options);
		});
	}
	
	
	$(document).ready(function() {
		console.log($('#option_participation').css("backgroundColor"));
		mouseListener();
	});
}();
