function OrderTab()
{
	this.coucou = coucou;
	this.deleteEntity = deleteEntity;
	
	function coucou()
	{
		alert('coucou');
	};	
	
	function deleteEntity(id,columnName,table)
	{
		//alert(id+' '+table);
		new Ajax.Request(baseUri+'src/Component/ordertab/app/Template/deleteEntity.php',
		{
			method: 'get',
			parameters:$H({
				'id':id,
				'columnname':columnName,
				'table':table,
			}).toQueryString(),
			onFailure: function(transport) {alert('../'+baseUri+'src/Component/message/app/Template/recupMess.php');},
			onSuccess: function(transport) {
				location.reload();
			}
		});
	}
}
