$(function(){
	$('body').click(function(e){
		if(e.target.id != 'user-menu' && e.target.id != 'user-name')
			$('#user-menu').hide();
		if(e.target.id != 'mobile-menu' && e.target.id != 'navicon' && e.target.id != 'fa-navicon')
			$('#mobile-menu').hide();
	});
	$('#user-name').click(function(){
		$('#user-menu').toggle();
	});
	$('#navicon').click(function(){
		console.log('click');
		$('#mobile-menu').toggle();
	});
	$('.date-input').mask('99.99.9999');
	InitRating();
	InitMenu();
	UpdateNewMessagesCount();
	setInterval(UpdateNewMessagesCount, 10000);
});

function InitMenu()
{
	var cur_href = window.location.href.split('?')[0];
	$('#top-menu a').each(function(){
		var href = this.href.split('?')[0];
		if (cur_href == href && href && href != '#')
			$(this).addClass('active');
	});
}

function UpdateNewMessagesCount()
{
	$.get('/ajax/NewMessagesCount', function(c){
		if (c != '0')
		{
			$('#new-messages-count').fadeIn(500);
			$('#new-messages-count').text('+'+c);
		}
		else
			$('#new-messages-count').hide();
	});
}

function InitRating()
{
	$('.rating').not('.processed').each(function(){
		var stars = $(this).find('input').val();
		for (i=0; i<5; i++)
		{
			var content = '<i class="fa fa-2x fa-star" pos="'+i+'"></i>';
			$(this).append(content);
			if ($(this).attr('editable') == 'true')
			{
				var star = $(this).find('i.fa').eq(i);
				star.mousemove(function(){ OnStarHover(this, event); });
				star.click(function(){ OnStarHover(this, event, true); });
			}
		}
		$(this).mouseleave(function() { UpdateStars(this, $(this).find('input').val()); });
		$(this).addClass('processed');
		UpdateStars(this,stars);
	});
}
function OnStarHover(star, e, save)
{
	save = save || false;
	var pos = $(star).attr('pos');
	score = pos * 2 + 1;
	if (e.layerX > $(star).width()/2)
		score += 1;
	UpdateStars($(star).parent(), score);
	if (save)
		$(star).parent().find('input').val(score);
}
function UpdateStars(rating_obj, score)
{
	var stars = $(rating_obj).find('i.fa');
	for (i=0; i<5; i++)
	{
		var star = stars.eq(i);
		star.removeClass('fa-star-o').removeClass('fa-star-half-o').removeClass('fa-star');
		var c = 'fa-star-o';
		if (score == 1)
			c = 'fa-star-half-o';
		if (score > 1)
			c = 'fa-star';
		star.addClass(c);
		score -= 2;
		if (score < 0) score = 0;
	}
}

function ShowDialog(id)
{
	$('#shadow').show();
	$('#dialog-holder').show();
	$('#'+id).addClass('active');
	$('#'+id).css('display', 'inline-block');
}
function HideDialog()
{
	$('#shadow').hide();
	$('#dialog-holder').hide();
	$('.dialog.active').hide();
}