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
			//console.log("hi");
			//console.log($table_entry.hasClass('entry_positive'));
			if($(this).hasClass('entry_positive') == true) {
				//console.log($table_entry.css("backgroundColor"));
				if($(this).css("backgroundColor").indexOf('rgba(0, 0, 0, 0)') != -1) {
					//console.log("TRUE");
					$(this).css("backgroundColor", "rgba(0, 192, 0, 100)");
				}
				else {
					$(this).css("backgroundColor", "rgba(0, 0, 0, 0)");
				}
			}
			else if($(this).hasClass('entry_negative') == true) {
				if($(this).css("backgroundColor").indexOf('rgba(0, 0, 0, 0)') != -1) {
					//console.log("TRUE");
					$(this).css("backgroundColor", "rgba(255, 0, 0, 100)");
				}
				else {
					$(this).css("backgroundColor", "rgba(0, 0, 0, 0)");
				}
			}	
		});
	}
	
	function loadData(school, teacher, period) {
		DB.populateTeacherView(school, teacher, period, displayData);
	}
	
	function echoData(result) {
		console.log(result);
	}
	
	function displayData(data) {
		var display = '';
		var id = '';
		
		for(var x = 0; x < data.total; x++) {
			id = data.results[x].performance_id;
			display +=	'<div class="table_row">' +
							'<div class="table_entry" id="entry_first_'+ id +'">'+ data.results[x].first_name +'</div>'+
							'<div class="table_entry" id="entry_last_'+ id +'">'+ data.results[x].last_name +'</div>'+
							'<div class="table_entry entry_positive" id="entry_participation_'+ id +'">participation</div>'+
							'<div class="table_entry entry_positive" id="entry_creativity_'+ id +'">creativity</div>'+
							'<div class="table_entry entry_positive" id="entry_teamwork_'+ id +'">teamwork</div>'+
							'<div class="table_entry entry_positive" id="entry_persistence_'+ id +'">persistence</div>'+
							'<div class="table_entry entry_positive" id="entry_insight_'+ id +'">insight</div>'+
							'<div class="table_entry entry_negative" id="entry_disruptive_'+ id +'">disruptive</div>'+
							'<div class="table_entry entry_negative" id="entry_missing_'+ id +'">no work</div>'+
							'<div class="table_entry entry_negative" id="entry_late_'+ id +'">late</div>'+
							'<div class="table_entry entry_negative" id="entry_absent_'+ id +'">absent</div>'+
							'<div class="table_entry entry_negative" id="entry_disrespectful_'+ id +'">disrespectful</div>'+
						'</div>';
		}
		
		$('#container_middle').html(display);
		loadPreSelect(data);
		setup();
	}
	
	function loadPreSelect(data) {
		var id;
		var shortcut;
		for(var x = 0; x < data.total; x++) {
			shortcut = data.results[x];
			id = shortcut.performance_id;
			
			setColor(shortcut.absent, $('#entry_absent_'+id), 'positive');
			setColor(shortcut.creativity, $('#entry_creativity_'+id), 'positive');
			setColor(shortcut.disrespectful, $('#entry_disrespectful_'+id), 'negative');
			setColor(shortcut.disruptive, $('#entry_disruptive_'+id), 'negative');
			setColor(shortcut.insight, $('#entry_insight_'+id), 'positive');
			setColor(shortcut.late, $('#entry_late_'+id), 'negative');
			setColor(shortcut.missing_work, $('#entry_missing_'+id), 'negative');
			setColor(shortcut.participation, $('#entry_participation_'+id), 'positive');
			setColor(shortcut.persistance, $('#entry_persistence_'+id), 'positive');
			setColor(shortcut.teamwork, $('#entry_teamwork_'+id), 'positive');
			
			//if(parseInt(data.results[x].absent) === 0 ) {
			//	$('#entry_participation_'+ id).css("backgroundColor", "rgba(0, 192, 0, 100)");
			//}
		}
		
		function setColor(value, $id, color_scheme) {
			var true_color = '';
			var false_color = "rgba(0, 0, 0, 0)";
			
			if(color_scheme.indexOf('positive') != -1) {
				true_color = "rgba(0, 192, 0, 100)";
			}
			else {
				true_color = "rgba(255, 0, 0, 100)";
			}
			
			if(parseInt(value) === 0) {
				$id.css("backgroundColor", false_color);
			}
			else {
				$id.css("backgroundColor", true_color);
			}
		}
	}
	
	$(document).ready(function() {
		setup();
		mouseListener();
		loadData('mets', 'randolph', '2');
		
	});
}();
