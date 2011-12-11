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
	
	function _registerParent(number, id, values, callback) {
		$.get('php/server.php', {
			'function'     : 'registerParent',
			'number'         : number,
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
			callback();
		});
	}
	
	function _loginTeacher(teacher, password, _callback) {
		console.log("it got this far");
		console.log(teacher);
		console.log(password);
		//_callback('mets', 'randolph', '2');
		$.get('php/server.php', {
			'function'    : 'loginTeacher',
			'teacher'     : teacher,
			'password'    : password
		}, function(data) {
			if(data.status.indexOf('200') != -1) {
				var classes = new Array();
				var school;
				for(var x = 0; x < data.results.length; x++) {
					if(data.results[x].class_period != "") {
						classes.push(data.results[x].class_period);
						school = data.results[x].name;
					}
				}
				_callback(school, teacher, classes);
				//_callback(school, teacher, classes[0]);
			}
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
		registerParent : function(number, id, values, callback) {
			console.log("register parent called");
			_registerParent(number, id, values, callback);
		},
		loginTeacher : function(teacher, password, callback) {
			console.log("login teacher");
			_loginTeacher(teacher, password, callback);
		}
};
	
}();
