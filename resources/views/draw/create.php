<!DOCTYPE html>
<html>

<meta charset="utf-8">
<script type="text/javascript" src="./static/js/jquery.js"></script>
<head>
	<title></title>
</head>
<body>
	<center>
		<h2>抽奖</h2>
		<button id="fom">开始抽奖</button>
	</center>
</body>
</html>
<script type="text/javascript">
	$(document).on('click','#fom',function(){

		$.ajax({
			url: '/draw/enit',
			type: "get",
            dataType: 'json',
            success: function(d){
            	 if(d.errno==400003)
                    {
                        window.location.href = '/user/login'
                    }
                    alert(d.data.level);
			}
		});	
	});
</script>
