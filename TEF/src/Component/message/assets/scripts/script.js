
function init()
{
	var message = document.getElementById('message');
	if (message != null)
	{
		var theMessage = "";
		new Ajax.Request(baseUri+'src/Component/message/app/Template/recupMess.php',
		{
			method: 'get',
			parameters:$H({}).toQueryString(),
			onFailure: function(transport) {alert('../'+baseUri+'src/Component/message/app/Template/recupMess.php');},
			onSuccess: function(transport) {theMessage = transport.responseText; 	
			message.innerHTML = '<div>'+theMessage+'</div>';
			}
		});
	}	
}

window.addEventListener('load',init,false);
