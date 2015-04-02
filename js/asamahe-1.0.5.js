function treatGallery() {
    // $('.badan').height($(window).height() - $('.kepala').height());
    $('.badan-row').css({"padding-top":($('.kepala').height()+18)+'px'});
    $('.kepala').css({"width":($(window).width()-20)+'px'});
    // $('.fb-login-btn').css( {"margin-top":($(window).height() - $('.kepala').height())/2 + 'px'} );
}

$( window ).load(function() {
	treatGallery();
});
$(window).resize(function() {
	treatGallery();
});

$(window).trigger('resize');



var apiUrlPrefix = 'http://asamahe.com:2903/api/';

function deleteJokeDialog(key, deleterId, jokeId, redirectUrl) {
	$("#dialog-confirm").html("Do you want to delete this joke?<br />(CAN'T BE UNDONE)");

    // Define the Dialog and its properties.
    $("#dialog-confirm").dialog({
        resizable: false,
        modal: true,
        title: "Delete Joke",
        height: 200,
        width: 300,
        buttons: {
            "Yes": function () {
            	var person = {
		Key: key,
		UserId: deleterId,
		JokeId: jokeId
	}

	$.ajax({
		url: apiUrlPrefix+'joke/deletejoke/',
		type: 'POST',
		dataType: 'json',
		success: function(data) {
			// setTimeout(function(){window.location.href='<?php echo DOMAIN_URL; ?>';}, 200);
			window.location.href=redirectUrl;
		},
		data: JSON.stringify(person)
	});
                $(this).dialog('close');
            },
            "Cancel": function () {
                $(this).dialog('close');
            }
        }
    });
}

function deleteJokeDialogFromList(key, deleterId, jokeId, redirectUrl) {
	$("#dialog-confirm").html("Do you want to delete this joke?<br />(CAN'T BE UNDONE)");

    // Define the Dialog and its properties.
    $("#dialog-confirm").dialog({
        resizable: false,
        modal: true,
        title: "Delete Joke",
        height: 200,
        width: 300,
        buttons: {
            "Yes": function () {
            	var person = {
		Key: key,
		UserId: deleterId,
		JokeId: jokeId
	}

	$.ajax({
		url: apiUrlPrefix+'joke/deletejoke/',
		type: 'POST',
		dataType: 'json',
		success: function(data) {
			// setTimeout(function(){window.location.href='<?php echo DOMAIN_URL; ?>';}, 200);
			// window.location.href=redirectUrl;
			$('#'+jokeId).hide(500);
		},
		data: JSON.stringify(person)
	});
                $(this).dialog('close');
            },
            "Cancel": function () {
                $(this).dialog('close');
            }
        }
    });
}

function toggleLike(key, jokeId, liker) {
    var likeUrl='';

    // If already Liked, gonna unlike
    if ($('.like-btn_'+jokeId).css("opacity")==1.0) {
        $('.like-btn_'+jokeId).css({"opacity":0.4});
        $('#like-count_'+jokeId).text(parseInt($('#like-count_'+jokeId).text())-1);
        likeUrl=apiUrlPrefix+'like/dislike/';
    }
    // If not liked, gonna like
    else {
        $('.like-btn_'+jokeId).css({"opacity":1.0});
        $('#like-count_'+jokeId).text(parseInt($('#like-count_'+jokeId).text())+1);
        likeUrl=apiUrlPrefix+'like/like/';
    }

    var person = {
        Key: key,
        Liker: liker,
        JokeId: jokeId
    }
    $.ajax({
        url: likeUrl,
        type: 'POST',
        dataType: 'json',
        success: function(data) {
            // setTimeout(function(){window.location.href='<?php echo DOMAIN_URL; ?>';}, 200);
            // window.location.href=redirectUrl;
        },
        data: JSON.stringify(person)
    });
}

function report(key, jokeId, reporter) {
    var likeUrl = '';
    // If not liked, gonna like
    if ($('.report-btn_' + jokeId).css("opacity") == 0.4) {
        $("#dialog-confirm").html("Do you want to report this joke?<br />(CAN'T BE UNDONE)");

        // Define the Dialog and its properties.
        $("#dialog-confirm").dialog({
            resizable: false,
            modal: true,
            title: "Report Joke",
            height: 200,
            width: 300,
            buttons: {
                "Yes": function() {
                    $(this).dialog('close');
                    $('.report-btn_' + jokeId).css({
                        "opacity": 1.0
                    });
                    likeUrl = apiUrlPrefix + 'report/report/';

                    var person = {
                        Key: key,
                        Reporter: reporter,
                        JokeId: jokeId
                    }
                    $.ajax({
                        url: likeUrl,
                        type: 'POST',
                        dataType: 'json',
                        success: function(data) {
                            // setTimeout(function(){window.location.href='<?php echo DOMAIN_URL; ?>';}, 200);
                            // window.location.href=redirectUrl;
                        },
                        data: JSON.stringify(person)
                    });
                },
                "Cancel": function() {
                    $(this).dialog('close');
                }
            }
        });
    }
}