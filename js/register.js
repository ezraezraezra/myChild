/**
 * @author Ezra Velazquez
 */

/*
 * Project:     myChild
 * Description: Update parents on child's progress via SMS
 * Website:     http://ezraezraezra.com/mt/myChild
 * 
 * Author:      Ezra Velazquez
 * Manager:     Lyel Resner
 * Website:     http://ezraezraezra.com
 * Date:        December 2011
 * 
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
			console.log($('#user_number').val());
			DB.registerParent($('#user_number').val(), $('#user_student_id').val(), options, registrationComplete);
			$('#container_middle').html("Registering...").css({textAlign : 'center', lineHeight : '150px'});
			$('#container_bottom').html("");
		});
	}
	
	function registrationComplete() {
		$('#container_middle').html("Thank you for registering.<br/> To see your son/daughter's progress, TXT 'myChild' to 415-599-2671.");
	}
	
	
	$(document).ready(function() {
		console.log($('#option_participation').css("backgroundColor"));
		mouseListener();
	});
}();
