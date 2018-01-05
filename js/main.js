var l = console.log.bind(console);
$(function(){
	// ---------------- Tabs --------------

	function ShowActiveTab(tabs)
	{
		$(tabs).find('li').each(function(k, li) {
			var id = $(li).attr('target');
			if (!$(li).hasClass('active'))
				$('#'+id).hide();
			else
				$('#'+id).show();
		});
	}

	$('ul.tabs').each(function(k, tabs) {
		ShowActiveTab(tabs);
	});

	$('ul.tabs li').click(function(){
		if ($(this).hasClass('active')) return;
		$(this).parent().find('li.active').removeClass('active');
		$(this).addClass('active');
		ShowActiveTab($(this).parent());
	});
	// ---------------- End tabs --------------

	InitTags();
});

function UpdateInputVal(holder)
{
	var str = '';
	$(holder).find('.tag').each(function(){
		var clone = $(this).clone();
		clone.find('span').remove();
		str += (str ? ',' : '') + clone.text();
	});
	$(holder).next().val(str);
}

function DeleteTag(btn)
{
	var holder = $(btn).closest('.tags_div');
	$(btn).closest('.tag').remove();
	UpdateInputVal(holder);
}

function InitTags()
{
	function css(a) {
	    var sheets = document.styleSheets, o = {};
	    for (var i in sheets) {
	        var rules = sheets[i].rules || sheets[i].cssRules;
	        for (var r in rules) {
	            if (a.is(rules[r].selectorText)) {
	                o = $.extend(o, css2json(rules[r].style), css2json(a.attr('style')));
	            }
	        }
	    }
	    return o;
	}

	function css2json(css) {
	    var s = {};
	    if (!css) return s;
	    if (css instanceof CSSStyleDeclaration) {
	        for (var i in css) {
	            if ((css[i]).toLowerCase) {
	                s[(css[i]).toLowerCase()] = (css[css[i]]);
	            }
	        }
	    } else if (typeof css == "string") {
	        css = css.split("; ");
	        for (var i in css) {
	            var l = css[i].split(": ");
	            s[l[0].toLowerCase()] = (l[1]);
	        }
	    }
	    return s;
	}

	function AddTag(holder,text)
	{
		$(holder).append('<span class="tag" contenteditable="false">' + text + '<span class="delete-tag" onclick="DeleteTag(this)">×</span></span>');
		
	}

	function OnKeyUp(e)
	{
		if ((e.keyCode >= 37 && e.keyCode <= 40 ) || e.keyCode == 13) return;
		if (e.keyCode == 8)
		{
			if (!$(e.currentTarget).find('br').size())
			{
				var setSel = true;
				cns = e.currentTarget.childNodes;
				if (cns[cns.length-1] && cns[cns.length-1].nodeName == '#text')
					setSel = false;
				$(e.currentTarget).append('<br />');
				if (setSel)
					SetSelection(e.currentTarget);
			}
		}
		if(url=$(e.currentTarget).attr('hintsUrl'))
		{
			var tags = $(e.currentTarget).clone();
			tags.find('.tag').remove();
			var text = $(tags).text();
			if (text)
			{
				$.getJSON(url + '&q='+text, function(data){
					//l(data);
					$('#tag-hint ul li').remove();
					$.each(data, function(i, v) {
					    $('#tag-hint ul').append('<li data="'+v.title_clear+'">' + v.title + '</li>');
					}); 
					var x = $(e.currentTarget).position().left;
					var y = $(e.currentTarget).position().top + $(e.currentTarget).outerHeight() + 2;
					if ($(e.currentTarget).find('.tag').size())
						x = $(e.currentTarget).find('.tag').last().position().left + $(e.currentTarget).find('.tag').last().outerWidth() + 2;
					$('#tag-hint').css('left', x);
					var r = $(e.currentTarget).position().left + $(e.currentTarget).width();
					if ($('#tag-hint').position().left + $('#tag-hint').width() > r )
						$('#tag-hint').css('left', r - $('#tag-hint').width());
					$('#tag-hint').css('top', y);
					if ($('#tag-hint ul li').size())
						$('#tag-hint').show();
					else
						$('#tag-hint').hide();
					$('#tag-hint li').click(function(){ 
						$(e.currentTarget).attr('needOnBlur', 0);
						TransformTextToTag(e.currentTarget, $(this).attr('data'));
						SetSelection(e.currentTarget);
						$('#tag-hint').hide();
					});
				});
			}
			else
				$('#tag-hint').hide();
		}
	}

	function SetSelection(holder)
	{
		var c = $(holder).find('.tag').size();
		var range = document.createRange();
		var sel = window.getSelection();
		range.setStart(holder.childNodes[c], 0);
		range.collapse(true);
		sel.removeAllRanges();
		sel.addRange(range);
	}

	function TransformTextToTag(holder, text_to_add)
	{
		var tags = $(holder).find('.tag').remove();
		var text = $(holder).text();
		if (text_to_add !== undefined) text = text_to_add;
		$(holder).text('');
		holder.removeChild(holder.childNodes[0]);
		tags.each(function(){
			$(this).find('span').remove();
			AddTag($(holder), $(this).text());
		});
		if (text)
			AddTag(holder, text);
		$(holder).append('<br />');
		UpdateInputVal(holder);
	}

	function OnKeyDown(e)
	{
		if (e.keyCode == 13)
		{
			if ($('#tag-hint').is(':visible') && $('#tag-hint li.selected').size())
			{
				TransformTextToTag(e.currentTarget, $('#tag-hint li.selected').attr('data'));
				SetSelection(e.currentTarget);
				$('#tag-hint').hide();
			}
			return false;
		}
		var unicode=e.charCode? e.charCode : e.keyCode;
    	var actualkey=String.fromCharCode(unicode);
		if (actualkey == ',')
		{
			TransformTextToTag(e.currentTarget);
			SetSelection(e.currentTarget);
			return false;
		}
	}

	function TagHintsNavigate(e, tag_input)
	{
		//console.log(e.keyCode);
		if (e.keyCode == 40 || e.keyCode == 38)
		{
			var hints_count;
			if (hints_count = $('#tag-hint li').size())
			{
				var sel_num = -1;
				$('#tag-hint li').each(function(i) {
					if ($(this).hasClass('selected'))
						sel_num = i;
				});
				if (e.keyCode == 40 && ++sel_num >= hints_count) return;
				$('#tag-hint li.selected').removeClass('selected');
				if (e.keyCode == 40 || (e.keyCode == 38 && --sel_num >= 0))
				{
					if (e.keyCode == 40 && sel_num == 0)
						tag_input.attr('old-data',tag_input.val());
					//tag_input.val($('#tag-hint li').eq(sel_num).attr('data'));
					$('#tag-hint li').eq(sel_num).addClass('selected');
				}
				//else if (e.keyCode == 38 && sel_num == -1)
				//	tag_input.val(tag_input.attr('old-data'));
			}
			e.preventDefault();
		}
	}

	function OnFocusLeave(e)
	{
		$(e.currentTarget).attr('needOnBlur', 1);
		setTimeout(function(){
			if ($(e.currentTarget).attr('needOnBlur') == 1)
				TransformTextToTag(e.currentTarget);
			$('#tag-hint').hide();
		}, 200);
	}

	function InitTagsInput(input)
	{
		$(input).before('<div class="tags_div" contenteditable="true"></div>');
		var div = $(input).prev();
		var style = css($(input));
		div.css(style);
		div.attr('placeholder', $(input).attr('placeholder'));
		$(input).hide();
		var tags_num = 0;
		$(input).val().split(',').filter(function(s) {return s.length > 0;})
			.forEach(function(s) {AddTag(div,s); tags_num++});
		if (tags_num)
			$(div).append('<br />');

		div.attr('hintsUrl', $(input).attr('hintsUrl'));
		div.get(0).onkeypress=OnKeyDown;
		div.keyup(OnKeyUp);
		div.keydown(function(e) { TagHintsNavigate(e, div); });
		div.blur(OnFocusLeave);
	}

	$('.tags').each(function(){
		InitTagsInput(this);
	});
}