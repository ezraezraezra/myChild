/**
 * @author Ezra Velazquez
 */

var DB = function() {

	function _polulateTeacherView(school, teacher, period, callback) {		
		$.get('php/server.php', {
			school : school,
			teacher : teacher,
			period : period,
			'function' : 'populateTeacherView'
		}, function(data) {
			callback(data);
		});
	}
	
	function _saveTeacherView(values, callback) {
		for (var x = 0; x < values.length; x++) {
			saveView(values[x], x, values.length, callback);
			//console.log(values[x]["id"]);
		}
		
		function saveView(_values, current_index, length, _callback) {
			$.get('php/server.php', {
				'function'     : 'saveTeacherView',
				performance_id : _values['id'],
				absent         : _values['absent'],
				disrespectful  : _values['disrespectful'],
				disruptive     : _values['disruptive'],
				insight        : _values['insight'],
				late           : _values['late'],
				missing        : _values['missing'],
				participation  : _values['participation'],
				persistence    : _values['persistence'],
				teamwork       : _values['teamwork']
			}, function(data) {
				//console.log(data);
				if(current_index + 1 == length) {
					console.log("Update");
					_callback('Update Saved');
				}
			});
		}
	}
	
	function _registerParent(number, id, values) {
		$.get('php/server.php', {
			'function'     : 'registerParent',
			student_id     : id,
			absent         : values['absent'],
			disrespectful  : values['disrespectful'],
			disruptive     : values['disruptive'],
			insight        : values['insight'],
			late           : values['late'],
			missing        : values['missing'],
			participation  : values['participation'],
			persistence    : values['persistence'],
			teamwork       : values['teamwork'],
			creativity     : values['creativity']
		}, function(data) {
			console.log(data);
		});
	}

	return {
		populateTeacherView : function(school, teacher, period, callback) {
			_polulateTeacherView(school, teacher, period, callback);
		},
		saveTeacherView : function(values, callback) {
			//console.log(values);
			//console.log(callback);
			//callback('HI');
			_saveTeacherView(values, callback);
		},
		registerParent : function(number, id, values) {
			console.log("register parent called");
			_registerParent(number, id, values);
		}
};
	
}();
