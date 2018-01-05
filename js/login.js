$(function(){
	$('body').mousemove(function(e){
		$('#background div').css('background-position-y', -(e.pageY/1.3));
	});

	VK.init({
		apiId: 4986840
	});
	VK.UI.button('vk-login-button');
});

function onFBLogin()
{
    FB.api('/me', function(response) {
    	$.ajax({url: '/site/FBLogin', data:{name: response.name, email: 'fb'+response.id}})
    	.done(function(res){
    		if (res == 'ok') window.location.href = '/home';
    		else alert('Error');
    	});
    });
}

function onVKLogin(response)
{
	if (response.session) {
		$.ajax({url: '/site/FBLogin', data:{name: response.session.user.first_name, email: 'vk'+response.session.user.id}})
    	.done(function(res){
    		if (res == 'ok') window.location.href = '/home';
    		else alert('Error');
    	});
	}
	else alert('Не удалось авторизоваться через VK');
}