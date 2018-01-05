$(function(){
	LoadBooks();
});

function LoadBooks()
{
	var loaded_count = $('#ajax-content').find('.book-small').size();
	$.get('/ajax/LoadRecentBooks?read=0&offset='+loaded_count,function(data){
		$('.load_more').remove();
		$('#ajax-content').append(data);
		$('.book-small').fadeIn(200);
		$('.book-small').css('display','inline-block');
		$('.load_more').click(function(){LoadBooks();});
	});
}