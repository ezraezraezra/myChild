/**
 * @author Ezra Velazquez
 */

var TEACHER_VIEW = function() {
	
	var $table_entry;
	var $table_row;
	
	function setup() {
		$table_entry = $(".table_entry");
		$table_row = $(".table_row");
		
		$table_row.filter(":even").css("backgroundColor", "#bbbbff");
		$table_row.filter(":odd").css("backgroundColor", "#ffffff");
		$table_entry.filter(":nth-child(2)").css("borderRight", "1px solid #000000");
	}
	
	function mouseListener() {
		$("#container_middle").delegate(".table_entry", "click", function() {
		//$table_entry.click(function() {
			console.log("hi");
			console.log($table_entry.hasClass('entry_positive'));
			if($(this).hasClass('entry_positive') == true) {
				console.log($table_entry.css("backgroundColor"));
				if($(this).css("backgroundColor").indexOf('rgba(0, 0, 0, 0)') != -1) {
					console.log("TRUE");
					$(this).css("backgroundColor", "rgba(0, 192, 0, 100)");
				}
				else {
					$(this).css("backgroundColor", "rgba(0, 0, 0, 0)");
				}
			}
			else if($(this).hasClass('entry_negative') == true) {
				if($(this).css("backgroundColor").indexOf('rgba(0, 0, 0, 0)') != -1) {
					console.log("TRUE");
					$(this).css("backgroundColor", "rgba(255, 0, 0, 100)");
				}
				else {
					$(this).css("backgroundColor", "rgba(0, 0, 0, 0)");
				}
			}	
		});
	}
	
	function loadData(school, teacher, period) {
		DB.populateTeacherView(school, teacher, period, echoData);
	}
	
	function echoData(result) {
		console.log(result);
	}
	
	$(document).ready(function() {
		setup();
		mouseListener();
		loadData('mets', 'randolph', '2');
		
	});
}();
