var coversOffset = 0;
var coversOffsetHistory = [];
var prevOffset = 0;
var imageSearch;

$(function(){
	AdjustPreviewSize();
	$('#cover-image').load(AdjustPreviewSize);
	$('#add-book').submit(function(){
		var errors = false;
		$('#add-book').find('.required').each(function(){
			if (!$(this).val())
			{
				console.log(this);
				$(this).addClass('error');
				errors = true;
			}
		});
		$('.required.error').click(function(){ $(this).removeClass('error'); });
		var pos = parseInt($('#preview-chose').css('top')) - ($("#cover-holder").height() - $('#cover-image').height())/2;
		$('#cover-pos').val( pos );
		$('#cover-pos-percent').val( $('#cover-image').height() ? (pos / parseInt($('#cover-image').height())).toPrecision(2) : 0 );
		if (errors) return false;
	});

	$( "#author" ).autocomplete({
    	source: authors
    });

    $('#cover-select').change(function(){
    	file = this.files[0];
    	SubmitCover(file);
    });

    $('#cover-holder').click(function(e){
    	if (e.target != $('#preview-chose').get(0))
    		$('#cover-select').click();
    });
    $('#preview-chose').draggable({ containment: "parent" });

    //$("body").bind('dragenter', onDragOver);
    //$("body").bind('dragleave', function(){ console.log('leave'); $("#cover-holder").removeClass('file-hover') });

    //google images
    $('#autoload').click(function(){
    	var err = false;
    	if (!$('#author').val())
    	{
    		err = true;
    		$('#author').addClass('error');
    	}
    	else $('#author').removeClass('error');
    	if (!$('#name').val())
    	{
    		err = true;
    		$('#name').addClass('error');
    	}
    	else $('#name').removeClass('error');
    	$('.required.error').click(function(){ $(this).removeClass('error'); });
    	if (err) return;
    	coversOffset = 0;
    	prevOffset = 0;
    	//coversOffsetHistory = [0];
    	$('#back').hide();
    	ShowCovers();
    });
    $('#next').click(function(){
    	coversOffsetHistory.push(prevOffset);
    	prevOffset = coversOffset;
    	$('#back').show();
    	ShowCovers();
    });
    $('#back').click(function(){
    	prevOffset = coversOffset = coversOffsetHistory.pop();
    	if (!coversOffsetHistory.length)
    		$('#back').hide();
    	ShowCovers();
    });
    $('#chose-cover-dialog>i.fa').click(HideDialog);
    $('#dialog-holder').not('.dialog').click(function(e){
    	if(e.target == e.currentTarget)
    		HideDialog();
    });
});

function SubmitCover(file)
{
	formdata = new FormData();
	//reader = new FileReader();
	if (!file) return;
	//reader.readAsDataURL(file);
	formdata.append("images[]", file);
	$.ajax({
		url: "/ajax/uploadcover",
		type: "POST",
		data: formdata,
		processData: false,
		contentType: false,
		success: function (res) {
			OnImageUploaded(res);
		}
	});
}

function AdjustPreviewSize()
{
	if ($('#cover-image').attr('src'))
	{
		$('#preview-chose').show();
		var margin = ($('#icons-holder').width() - $('#cover-image').width()) / 2;
		$('#preview-chose').height(($('#cover-image').width() * 0.266) + 'px');
		$('#preview-chose').css('margin-left', margin + 'px');
		$('#preview-chose').css('margin-right', margin + 'px');
		$('#preview-chose').css('top', (parseInt($('#cover-pos').val()) + ($("#cover-holder").height() - $('#cover-image').height())/2) + 'px');
		var t = ($("#cover-holder").height() - $('#cover-image').height())/2;
		if (parseInt($('#preview-chose').css('top')) < t)
		{
			console.log(t);
			$('#preview-chose').css('top', t+'px');
		}
	}
}

function OnImageUploaded(res)
{
	res = JSON.parse(res);
	if (res.result == 'ok')
	{
		$("#icons-holder").hide();
		$("#cover-image").attr('src', res.full_path);
		$('#cover-input').val(res.name);
		setTimeout(AdjustPreviewSize,100);
	}
	else alert(res.description);
}

function onDragOver(e) {
    e.preventDefault();
    $("#cover-holder").addClass('file-hover')
    console.log(e.currentTarget);
}

function ShowCovers()
{
	var name = $('#author').val() + " " + $('#name').val();
	var covers = [];
	$('#chose-cover-dialog>div.covers-content').html('');
	LoadCovers(covers, name);
	ShowDialog('chose-cover-dialog');
}

function LoadCovers(covers, name)
{
	$.getJSON('/ajax/LoadImages', {offset:coversOffset, name: name}, function(res){
		res.d.results.forEach(function(img){
			l(img.Height, img.Width);
			if (img.Height >= 300 && img.Width >= 300 && covers.length < 4)
				covers.push(img.MediaUrl);
		});
		coversOffset += 4;
		l(coversOffset);
		if (covers.length < 4)
			LoadCovers(covers, name);
		else
			onCoversLoaded(covers);
	});
}

function onCoversLoaded(covers)
{
	covers.forEach(function(url){
		$('#chose-cover-dialog>div.covers-content').append('<div class="chose-cover-holder"><img src="'+url+'"/></div>');
	});
	$('.chose-cover-holder').click(function(){
		var url = $(this).find('img').attr('src');
		$
		//$('#cover-select').val(url);
		$.ajax({
			url: "/ajax/uploadcoverbyurl",
			data: {url: url},
			success: function (res) {
				OnImageUploaded(res);
			}
		});
		HideDialog();
	});
}