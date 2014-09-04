//
//    File: mgmt.js
//
//Revision:2014090301
//
//

//
// NOTE: jQuery is required
//

// Common ----------------------------------------------------------------
function before_send(id) {
	// show ajax sending indicator
	$(id).css({"display": "block"});
} // before_send

function on_complete(id) {
	// hide ajax sending indicator
	$(id).css({"display": "none"});
} // on_complete

// Roles -----------------------------------------------------------------
function add_new_role() {
    var new_role = encodeURIComponent( $('#new_role').val() );
    var new_role_descr = encodeURIComponent( $('#new_role_descr').val() );

    $.ajax({
        url:"./api/add_role.php?json=1&title="+new_role+"&descr="+new_role_descr,
        dataType: "json",
        beforeSend:before_send("#add_new_role_is_sending"),
        complete:on_complete("#add_new_role_is_sending"),
        success: function (response) {
		var obj = response;

		console.log("new added\nid: " + obj.ID + "\ntitle: " + obj.Title + "\ndescr: " + obj.Description);
		$('#role_msg').html("new added<br>id: " + obj.ID + "<br>title: " + obj.Title + "<br>descr: " + obj.Description);

		// update selection menu of rols
		var old_options = $('#rname').html();
		var new_options = old_options
			+ "<option value='" 
			+ obj.ID
			+ "'>"
			+ obj.Title
			+ "(" 
			+ obj.Description
			+ ")"
			+ "</option>";	

		$('#rname').html(new_options);
		$('#new_role').val("");
		$('#new_role_descr').val("");
		$('#rname').attr("size", $('#rname').children('option').length+1);
	},
        error: function (xhr) {
		console.log("AJAX request error (add_new_role)");
		$('#role_msg').html("AJAX request error (add_new_role)");
	}
    }); 
} // add_new_role

function add_new_role_path() {
    var new_role_path = encodeURIComponent( $('#new_role_path').val() );
    var new_role_path_descr = encodeURIComponent( $('#new_role_path_descr').val() );

    $.ajax({
        url:"./api/add_role_path.php?json=1&path="+new_role_path+"&descr="+new_role_path_descr,
        dataType: "json",
        beforeSend:before_send("#add_new_role_path_is_sending"),
        complete:on_complete("#add_new_role_path_is_sending"),
        success: function (response) {
		var obj = response;

		console.log("Result: " + obj.Result + "\n" + "Msg: " + obj.Msg);
		$('#role_msg').html("Result: " + obj.Result + "<br>" + "Msg: " + obj.Msg);
		if( obj.Result == "OK." ) {
			// update select menu of roles
			var new_options = "<option value='-1'>-- Role Name --</option>";
			var opt_cnt = 0;

			for(var r_key in obj.List) {
				opt_cnt++;
				new_options = new_options
					+ "<option value='" 
					+ obj.List[r_key].ID
					+ "'>"
					+ obj.List[r_key].Title
					+ "(" 
					+ obj.List[r_key].Description
					+ ")"
					+ "</option>";	
			}
			$('#rname').html(new_options);
			$('#new_role_path').val("");
			$('#new_role_path_descr').val("");
			$('#rname').attr("size", opt_cnt+1);
		}
	},
        error: function (xhr) {
		console.log("AJAX request error (add_new_role_path)");
		$('#role_msg').html("AJAX request error (add_new_role_path)");
	}
    }); 
} // add_new_role_path

function role_selected() {
	console.log($('#rname').find(":selected").val() + " has been selected.");
} // role_selected

function remove_role(r_id) {
	$.ajax({
	        url:"./api/remove_role.php?json=1&r_id="+r_id,
	        dataType: "json",
	        beforeSend:before_send("#delete_role_is_sending"),
	        complete:on_complete("#delete_role_is_sending"),
	        success: function (response) {
			var obj = response;

			console.log("Result: " + obj.Result);
			$('#role_msg').html("Result: " + obj.Result);
			if( obj.Result == "OK." ) {
				// update select menu of roles
				var new_options = "<option value='-1'>-- Role Name --</option>";
				var opt_cnt = 0;

				for(var r_key in obj.List) {
					opt_cnt++;
					new_options = new_options
						+ "<option value='" 
						+ obj.List[r_key].ID
						+ "'>"
						+ obj.List[r_key].Title
						+ "(" 
						+ obj.List[r_key].Description
						+ ")"
						+ "</option>";	
				}
				$('#rname').html(new_options);
				$('#rname').attr("size", opt_cnt+1);
			}
		},
	        error: function (xhr) {
			console.log("AJAX request error (remove_role)");
			$('#role_msg').html("AJAX request error (remove_role)");
		}
	    }); 
} // remove_role

function delete_role() {
	var role_id = $('#rname').find(":selected").val();
	var role_title_and_descr = $('#rname').find(":selected").text();

	if( confirm("Are you sure to delete role: " + role_title_and_descr) ) {
		remove_role(role_id);
	}
	else {
		console.log("Canceled.");
		$('#role_msg').html("Canceled.");
	}
} // delete_role

// Permissions -------------------------------------------------------------------------------------------
function add_new_perm() {
    var new_perm = encodeURIComponent( $('#new_perm').val() );
    var new_perm_descr = encodeURIComponent( $('#new_perm_descr').val() );

    $.ajax({
        url:"./api/add_perm.php?json=1&title="+new_perm+"&descr="+new_perm_descr,
        dataType: "json",
        beforeSend:before_send("#add_new_perm_is_sending"),
        complete:on_complete("#add_new_perm_is_sending"),
        success: function (response) {
		var obj = response;

		console.log("new added\nid: " + obj.ID + "\ntitle: " + obj.Title + "\ndescr: " + obj.Description);
		$('#perm_msg').html("new added<br>id: " + obj.ID + "<br>title: " + obj.Title + "<br>descr: " + obj.Description);

		// update selection menu of rols
		var old_options = $('#pname').html();
		var new_options = old_options
			+ "<option value='" 
			+ obj.ID
			+ "'>"
			+ obj.Title
			+ "(" 
			+ obj.Description
			+ ")"
			+ "</option>";	

		$('#pname').html(new_options);
		$('#new_perm').val("");
		$('#new_perm_descr').val("");
		$('#pname').attr("size", $('#pname').children('option').length+1);
	},
        error: function (xhr) {
		console.log("AJAX request error (add_new_perm)");
		$('#perm_msg').html("AJAX request error (add_new_perm)");
	}
    }); 
} // add_new_perm

function add_new_perm_path() {
    var new_perm_path = encodeURIComponent( $('#new_perm_path').val() );
    var new_perm_path_descr = encodeURIComponent( $('#new_perm_path_descr').val() );

    $.ajax({
        url:"./api/add_perm_path.php?json=1&path="+new_perm_path+"&descr="+new_perm_path_descr,
        dataType: "json",
        beforeSend:before_send("#add_new_perm_path_is_sending"),
        complete:on_complete("#add_new_perm_path_is_sending"),
        success: function (response) {
		var obj = response;

		console.log("Result: " + obj.Result + "\n" + "Msg: " + obj.Msg);
		$('#perm_msg').html("Result: " + obj.Result + "<br>" + "Msg: " + obj.Msg);
		if( obj.Result == "OK." ) {
			// update select menu of roles
			var new_options = "<option value='-1'>-- Perm Name --</option>";
			var opt_cnt = 0;

			for(var p_key in obj.List) {
				opt_cnt++;
				new_options = new_options
					+ "<option value='" 
					+ obj.List[p_key].ID
					+ "'>"
					+ obj.List[p_key].Title
					+ "(" 
					+ obj.List[p_key].Description
					+ ")"
					+ "</option>";	
			}
			$('#pname').html(new_options);
			$('#new_perm_path').val("");
			$('#new_perm_path_descr').val("");
			$('#pname').attr("size", opt_cnt+1);
		}
	},
        error: function (xhr) {
		console.log("AJAX request error (add_new_perm_path)");
		$('#perm_msg').html("AJAX request error (add_new_perm_path)");
	}
    }); 
} // add_new_perm_path

function perm_selected() {
	console.log($('#pname').find(":selected").val() + " has been selected.");
} // perm_selected

function remove_perm(p_id) {
	$.ajax({
	        url:"./api/remove_perm.php?json=1&p_id="+p_id,
	        dataType: "json",
	        beforeSend:before_send("#delete_perm_is_sending"),
	        complete:on_complete("#delete_perm_is_sending"),
	        success: function (response) {
			var obj = response;

			console.log("Result: " + obj.Result);
			$('#perm_msg').html("Result: " + obj.Result);
			if( obj.Result == "OK." ) {
				// update select menu of roles
				var new_options = "<option value='-1'>-- Perm Name --</option>";
				var opt_cnt = 0;

				for(var p_key in obj.List) {
					opt_cnt++;
					new_options = new_options
						+ "<option value='" 
						+ obj.List[p_key].ID
						+ "'>"
						+ obj.List[p_key].Title
						+ "(" 
						+ obj.List[p_key].Description
						+ ")"
						+ "</option>";	
				}
				$('#pname').html(new_options);
				$('#pname').attr("size", opt_cnt+1);
			}
		},
	        error: function (xhr) {
			console.log("AJAX request error (remove_perm)");
			$('#perm_msg').html("AJAX request error (remove_perm)");
		}
	    }); 
} // remove_perm

function delete_perm() {
	var perm_id = $('#pname').find(":selected").val();
	var perm_title_and_descr = $('#pname').find(":selected").text();

	if( confirm("Are you sure to delete permission: " + perm_title_and_descr) ) {
		remove_perm(perm_id);
	}
	else {
		console.log("Canceled.");
		$('#perm_msg').html("Canceled.");
	}
} // delete_perm
