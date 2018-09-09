var view;

$(document).ready(function() {
	view = $("#content");
	
	//TEST
	showLoginPage();
	//ENDTEST
});

var showLoginPage = function() {
	view.empty();
	view.append("<form id='login_form'><input type='text' name='username' placeholder='Username' required><br><input type='password' name='password' placeholder='Password' required><br><input type='submit' value='Login'></form>");
	
	$("#login_form").on("submit", function(e) {
		e.stopPropagation();
		e.preventDefault();
		
		$.ajax('login.php', {
				type:'GET',
				data:$('#login_form').serialize(),
				cache:false,
				success:function() {
					alert('Login Successful');
					showDashboard();
				},
				error:function() {
					alert('Login Failed');
				}
			});
	});
}

var showDashboard = function() {
	view.empty();
	
	$.ajax('drugs.php', {
			type:'GET',
			cache:false,
			dataType:'json',
			success:function(data, status, jqXHR) {
				console.log(data);
			},
			error:function(jqXHR, status, error) {
				view.append("<p>Could not retrieve dashboard.</p>");
			}
		});
}
