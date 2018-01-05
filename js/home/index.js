$(function(){
	LoadBooks();
	$('#search-form').submit(function(){
		UpdateSearch();
		return false;
	});

	window.addEventListener("popstate", function() 
	{
		var q = GetUrlParameter('q');
		$('#search').val(q);
		UpdateSearch(false);
	});
	$('#search').keydown(SearchHintsNavigate);
	$('#search').keyup(ShowSearchHints);
});

function SearchHintsNavigate(e)
{
	console.log(e.keyCode);
	if (e.keyCode == 40 || e.keyCode == 38)
	{
		var hints_count;
		if (hints_count = $('#search-autocomplete li').size())
		{
			var sel_num = -1;
			$('#search-autocomplete li').each(function(i) {
				if ($(this).hasClass('selected'))
					sel_num = i;
			});
			if (e.keyCode == 40 && ++sel_num >= hints_count) return;
			$('#search-autocomplete li.selected').removeClass('selected');
			if (e.keyCode == 40 || (e.keyCode == 38 && --sel_num >= 0))
			{
				if (e.keyCode == 40 && sel_num == 0)
					$('#search').attr('old-data',$('#search').val());
				$('#search').val($('#search-autocomplete li').eq(sel_num).attr('data'));
				$('#search-autocomplete li').eq(sel_num).addClass('selected');
			}
			else if (e.keyCode == 38 && sel_num == -1)
				$('#search').val($('#search').attr('old-data'));
		}
		e.preventDefault();
	}
}

function ShowSearchHints(e)
{
	console.log(e.keyCode);
	if ((e.keyCode >= 37 && e.keyCode <= 40 ) || e.keyCode == 13) return;
	if ($('#search').val().length < 2) 
	{
		$('#search-autocomplete').hide();
		return;
	}
	$.getJSON('/ajax/SearchHints?q='+$('#search').val(), function(data){
		if(Object.keys(data).length)
		{
			$('#search-autocomplete').find('li').remove();
			for (i in data)
			{
				var hint = data[i];
				$('#search-autocomplete ul').append('<li data="'+hint.title_clear+'"><div class="hint-title">'+hint.title+'</div><div class="hint-type">'+hint.type+'</div></li>');
			}
			$('#search-autocomplete li').click(function(){ OnSearchHintSelect($(this).attr('data')); });
			$('#search-autocomplete').show();
		}
		else
			$('#search-autocomplete').hide();
	});
}

function OnSearchHintSelect(data)
{
	$('#search-autocomplete').hide();
	$('#search').val(data);
	UpdateSearch();
}

function GetUrlParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) 
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) 
        {
            return decodeURIComponent(sParameterName[1]);
        }
    }
}   

function UpdateSearch(push_state)
{
	$('#search-autocomplete').hide();
	push_state = push_state==undefined ? true : push_state;
	if (push_state)
	{
		var q = $('#search').val();
		var url = window.location.pathname + "?" + $.param({q:q});
		history.pushState({}, '', url);
	}
	LoadBooks(true);
}

function LoadBooks(clear)
{
	clear = clear || false;
	var q = $('#search').val();
	var loaded_count = $('#ajax-content').find('.book-small').size();
	if (clear)
		loaded_count = 0;
	$.get('/ajax/LoadRecentBooks?q='+q+'&offset='+loaded_count,function(data){
		$('.load_more').remove();
		if (clear)
			$('#ajax-content').html('');
		$('#ajax-content').append(data);
		InitRating();
		$('.book-small').fadeIn(200);
		$('.book-small').css('display','inline-block');
		$('.load_more').click(function(){LoadBooks();});
	});
}

function TagClick(tag)
{
	if (tag == $('#search').val()) return;
	$('#search').val(tag);
	UpdateSearch();
}