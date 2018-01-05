$(function(){
	$('#sent-button').click(SendMessage);
	$('#chat-window').get(0).scrollTop = $('#chat-window').get(0).scrollHeight;
	$('textarea').keydown(function(e){
		console.log(e);
	});
});

function SendMessage()
{
	var text = $('textarea').val();
	if (!text) return;
	$('textarea').val('');
	AddMessage(text);
}

function AddMessage(text)
{
	$.getJSON('/ajax/SendMessage', {text: text}, function(data){
		$('#chat-window').append('<div class="message-holder from"><div class="message">'+text+'<i>'+data.date+'</i></div></div>');
	})
}