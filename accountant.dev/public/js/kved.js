/**
 * kved
 *
 */
"use strict"
var kved = {
    initialize: function () {
	console.info(' initialize - Kved');
    },
    set_main: function ($this) {
	var kved_id = $($this).closest('tr').data('id');
	$('.col-md-12 table tbody tr td input[type="checkbox"][checked="checked"]').attr('checked', false);
	$('.col-md-12 table tbody tr td input[type="checkbox"][checked=""]').attr('checked', false);
	$($this).attr('checked', true);
	$($this).attr('checked', 'checked');

	if (kved_id) {
	    $.ajax({
		type: 'POST',
		url: $('#site_url').val() + '/kved/set-main-ajax',
		data: {
		    _token: $('#_token').val(),
		    kved_id: kved_id
		},
		dataType: "json",
		success: function (response) {
		    console.log(response);
		}
	    });
	}


    },
    edit_description: function ($this) {
	var kved_id = $($this).closest('tr').data('id');
	var old_description = $($this).closest('tr').find('td:nth-child(3)').text().trim();
	$($this).closest('tr').find('td:nth-child(3)').html('<input id="tmp_id" type="text" value="' + old_description + '"/>');
	$('#tmp_id').css('width', '100%');
	$('#tmp_id').focus();

	$('#tmp_id').focusout(function () {
	    var new_description = $('#tmp_id').val().trim();
	    if (old_description == new_description) {/*case if desc is not changed*/
		$($this).closest('tr').find('td:nth-child(3)').html();
		$($this).closest('tr').find('td:nth-child(3)').text(new_description);
		return;
	    }
	    if (kved_id) {
		send_ajax_desc($this, kved_id, new_description);
	    }
	});

	$('#tmp_id').keypress(function (e) {
	    if (e.which == 13) {
		var new_description = $('#tmp_id').val().trim();
		if (old_description == new_description) {/*case if desc is not changed*/
		    $($this).closest('tr').find('td:nth-child(3)').html();
		    $($this).closest('tr').find('td:nth-child(3)').text(new_description);
		    return;
		}
		if (kved_id) {
		    send_ajax_desc($this, kved_id, new_description);
		}
	    }
	});
    },
    delete: function ($this) {
	var kved_id = $($this).closest('tr').data('id');
	if (kved_id) {
	    $.ajax({
		type: 'GET',
		url: $('#site_url').val() + '/kved/delete-kved/' + kved_id,
		dataType: "json",
		success: function (response) {
		    if (response.code == 200) {
			$($this).closest('tr').css('background-color','#FFFBD3');
			$($this).closest('tr').slideUp('slow');
		    }
		}
	    });
	}
    },
    delete_user_kved: function ($this) {
	var kved_id = $($this).closest('tr').data('id');
	if (kved_id) {
	    $.ajax({
		type: 'POST',
		url: $('#site_url').val() + '/kved/delete-kved-ajax/',
		data: {
		    kved_id: kved_id,
		    _token: $('#_token').val(),
		},
		dataType: "json",
		success: function (response) {
		    if (response.code == 200) {
			$($this).closest('tr').css('background-color','#FFFBD3');
			$($this).closest('tr').slideUp('slow');
		    }
		}
	    });
	}
    }
}

function send_ajax_desc($this, kved_id, new_description) {
    $.ajax({
	type: 'POST',
	url: $('#site_url').val() + '/kved/kved-field-ajax',
	data: {
	    _token: $('#_token').val(),
	    kved_id: kved_id,
	    field: 'description',
	    value: new_description
	},
	dataType: "json",
	success: function (response) {
	    if (response.code == 200) {
		$($this).closest('tr').find('td:nth-child(3)').html();
		$($this).closest('tr').find('td:nth-child(3)').text(new_description);
	    }

	}
    });
}

/*Process flsh messages slideUp/hide*/
$('.alert').click(function(){
    $(this).hide();
})
$('.alert').delay(3500).slideUp();
