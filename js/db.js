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

	return {
		populateTeacherView : function(school, teacher, period, callback) {
			_polulateTeacherView(school, teacher, period, callback);
		}
};
	
}();
