//
//    File: mgmt.js
//
//Revision:2014091002
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

function update_select_menu(menu_id, hint, new_list) {
	var new_options = "<option value='-1'>" + hint + "</option>";
	var opt_cnt = 0;

	for(var key in new_list) {
		opt_cnt++;
		new_options = new_options
			+ "<option value='" + new_list[key].ID + "'>"
			+ new_list[key].Title + "(" + new_list[key].Description + ")"
			+ "</option>";	
	}
	$(menu_id).html(new_options);
	$(menu_id).attr("size", opt_cnt+1);
} // update_select_menu

// Roles -----------------------------------------------------------------
function add_perm_to_role() {
	var r_id = $('#r_id_for_edit').html();
	var p_title_and_descr = $('#role_unassoc_perm').find(":selected").html().match(/^(.+)\((.+)\)$/);
	var p_title = p_title_and_descr[1], p_descr = p_title_and_descr[2];

	console.log("add_perm_to_role for r_id: " + r_id + " title: " + p_title + " descr: " + p_descr);

	$.ajax({
	        url:"./api/assign_perm_to_role.php?json=1&role="+r_id+"&perm="+p_title,
	        dataType: "json",
	        beforeSend:before_send("#modify_role_is_sending"),
	        complete:on_complete("#modify_role_is_sending"),
	        success: function (response) {
			var obj = response;

			console.log("Result: " + obj.Result);
			$('#role_msg').html("Result: " + obj.Result);
			if( obj.Result == "OK." ) {
				update_select_menu('#role_assoc_perm', '-- Associated Perm --', obj.List);
				update_select_menu('#role_unassoc_perm', '-- Unassociated Perm --', obj.unList);
			}
		},
	        error: function (xhr) {
			console.log("AJAX request error (add_perm_to_role)");
			$('#role_msg').html("AJAX request error (add_perm_to_role)");
		}
	    }); 
} // add_perm_to_role

function remove_perm_from_role() {
	var r_id = $('#r_id_for_edit').html();
	var p_title_and_descr = $('#role_assoc_perm').find(":selected").html().match(/^(.+)\((.+)\)$/);
	var p_title = p_title_and_descr[1], p_descr = p_title_and_descr[2];

	console.log("remove_perm_from_role for r_id: " + r_id + " title: " + p_title + " descr: " + p_descr);

	$.ajax({
	        url:"./api/unassign_perm_and_role.php?json=1&role="+r_id+"&perm="+p_title,
	        dataType: "json",
	        beforeSend:before_send("#modify_role_is_sending"),
	        complete:on_complete("#modify_role_is_sending"),
	        success: function (response) {
			var obj = response;

			console.log("Result: " + obj.Result);
			$('#role_msg').html("Result: " + obj.Result);
			if( obj.Result == "OK." ) {
				update_select_menu('#role_assoc_perm', '-- Associated Perm --', obj.List);
				update_select_menu('#role_unassoc_perm', '-- Unassociated Perm --', obj.unList);
			}
		},
	        error: function (xhr) {
			console.log("AJAX request error (remove_perm_from_role)");
			$('#role_msg').html("AJAX request error (remove_perm_from_role)");
		}
	    }); 
} // remove_perm_from_role

function edit_role(r_id, r_title, r_descr) {
	console.log("prepare to edit role id " + r_id + "\nnew title: " + r_title + "\nnew descr: " + r_descr);
    	var new_role_title = encodeURIComponent( r_title );
	var new_role_descr = encodeURIComponent( r_descr );

	$.ajax({
	        url:"./api/edit_role.php?json=1&r_id="+r_id+"&title="+new_role_title+"&descr="+new_role_descr,
	        dataType: "json",
	        beforeSend:before_send("#modify_role_is_sending"),
	        complete:on_complete("#modify_role_is_sending"),
	        success: function (response) {
			var obj = response;

			console.log("Result: " + obj.Result);
			$('#role_msg').html("Result: " + obj.Result);
			if( obj.Result == "OK." ) {
				update_select_menu('#rname', '-- Role Name --', obj.List);
			}
		},
	        error: function (xhr) {
			console.log("AJAX request error (edit_role)");
			$('#role_msg').html("AJAX request error (edit_role)");
		}
	    }); 
} // edit_role

function modify_role() {
	var r_id = $('#r_id_for_edit').html();
	var new_r_title = $('#r_title_for_edit').val();
	var new_r_descr = $('#r_descr_for_edit').val();

	edit_role(r_id, new_r_title, new_r_descr);
} // modify_role

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
			update_select_menu('#rname', '--Role Name --', obj.List);
			$('#new_role_path').val("");
			$('#new_role_path_descr').val("");
		}
	},
        error: function (xhr) {
		console.log("AJAX request error (add_new_role_path)");
		$('#role_msg').html("AJAX request error (add_new_role_path)");
	}
    }); 
} // add_new_role_path

function update_selection_about_role_perm_assoc(r_id) {
	// get role & permissioin associated/unassociated list
	$.ajax({
	        url:"./api/get_perms_of_role.php?json=1&r_id="+r_id,
	        dataType: "json",
	        beforeSend:before_send("#delete_role_is_sending"),
	        complete:on_complete("#delete_role_is_sending"),
	        success: function (response) {
			var obj = response;

			console.log("Result: " + obj.Result);
			$('#role_msg').html("Result: " + obj.Result);
			if( obj.Result == "OK." ) {
				update_select_menu('#role_assoc_perm', '-- Associated Perm --', obj.List);
				update_select_menu('#role_unassoc_perm', '-- Unassociated Perm --', obj.unList);
			}
		},
	        error: function (xhr) {
			console.log("AJAX request error (update_selection_about_role_perm_assoc)");
			$('#role_msg').html("AJAX request error (update_selecttion_about_role_perm_assoc)");
		}
	});
} // update_selection_about_role_perm_assoc

function role_selected() {
	var r_id = $('#rname').find(":selected").val();

	if( typeof r_id === 'undefined' || r_id < 0 ) return;		// invalid value, ignore them and return

	var r_title_and_descr = $('#rname').find(":selected").html().match(/^(.+)\((.+)\)$/);
	var r_title = r_title_and_descr[1], r_descr = r_title_and_descr[2];

	console.log("role id: " + r_id + ", title: " + r_title + ", descr: " + r_descr + " has been selected.");

	// update r_*_for_edit
	$('#r_id_for_edit').html(r_id);
	$('#r_title_for_edit').val(r_title);
	$('#r_descr_for_edit').val(r_descr);

	// update role & perm association
	update_selection_about_role_perm_assoc(r_id);
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
				update_select_menu('#rname', '-- Role Name --', obj.List);
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

	if( confirm("Are you sure to delete role id " + role_id + ": " + role_title_and_descr) ) {
		remove_role(role_id);
	}
	else {
		console.log("Canceled.");
		$('#role_msg').html("Canceled.");
	}
} // delete_role

// Permissions -------------------------------------------------------------------------------------------
function edit_perm(p_id, p_title, p_descr) {
	console.log("prepare to edit perm id " + p_id + "\nnew title: " + p_title + "\nnew descr: " + p_descr);
    	var new_perm_title = encodeURIComponent( p_title );
	var new_perm_descr = encodeURIComponent( p_descr );

	$.ajax({
	        url:"./api/edit_perm.php?json=1&p_id="+p_id+"&title="+new_perm_title+"&descr="+new_perm_descr,
	        dataType: "json",
	        beforeSend:before_send("#modify_perm_is_sending"),
	        complete:on_complete("#modify_perm_is_sending"),
	        success: function (response) {
			var obj = response;

			console.log("Result: " + obj.Result);
			$('#perm_msg').html("Result: " + obj.Result);
			if( obj.Result == "OK." ) {
				// update select menu of perms
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
			console.log("AJAX request error (edit_perm)");
			$('#perm_msg').html("AJAX request error (edit_perm)");
		}
	    }); 
} // edit_perm

function modify_perm() {
	var p_id = $('#p_id_for_edit').html();
	var new_p_title = $('#p_title_for_edit').val();
	var new_p_descr = $('#p_descr_for_edit').val();

	edit_perm(p_id, new_p_title, new_p_descr);
} // modify_perm

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
	var p_id = $('#pname').find(":selected").val();

	if( typeof p_id === 'undefined' || p_id < 0 ) return;		// invalid value, ignore them and return

	var p_title_and_descr = $('#pname').find(":selected").html().match(/^(.+)\((.+)\)$/);
	var p_title = p_title_and_descr[1], p_descr = p_title_and_descr[2];

	console.log("perm id: " + p_id + ", title: " + p_title + ", descr: " + p_descr + " has been selected.");

	// update p_*_for_edit
	$('#p_id_for_edit').html(p_id);
	$('#p_title_for_edit').val(p_title);
	$('#p_descr_for_edit').val(p_descr);
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

