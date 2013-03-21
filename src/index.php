<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="keywords" content="jquery">
<meta name="description" content="">
<title>jQuery+Ajax+PHP实现“喜欢”评级</title>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
$(function(){
	$("p a").click(function(){
		var love = $(this);
		var id = love.attr("rel");
		love.fadeOut(300);
		$.ajax({
			type:"POST",
			url:"love.php",
			data:"id="+id,
			cache:false,
			success:function(data){
				love.html(data);
				love.fadeIn(300);
			}
		});
		return false;
	});
});
</script>
<style type="text/css">
.clear{clear:both}
.list{width:760px; margin:20px auto}
.list li{float:left; width:360px; height:280px; margin:10px; position:relative}
.list li p{position:absolute; top:0; left:0; width:360px; height:24px; line-height:24px; background:#000; opacity:.8;filter:alpha(opacity=80);}
.list li p a{padding-left:30px; height:24px; background:url(images/heart.png) no-repeat 4px -1px;color:#fff; font-weight:bold; font-size:14px}
.list li p a:hover{background-position:4px -25px;text-decoration:none}
</style>
</head>

<body>
<div id="header">
   <div id="logo"><h1><a href="http://www.helloweba.com" title="返回helloweba首页">helloweba</a></h1></div>
</div>
<div id="main">
	 <h2 class="top_title"><a href="http://www.helloweba.com/view-blog-197.html">jQuery+Ajax+PHP实现“喜欢”评级</a></h2>
     <ul class="list">
     <?php
	 include_once("connect.php");
	 $sql = mysql_query("select * from pic");
	 while($row=mysql_fetch_array($sql)){
		 $pic_id = $row['id'];
		 $pic_name = $row['pic_name'];
		 $pic_url = $row['pic_url'];
		 $love = $row['love'];
	 ?>
     	<li><img src="images/<?php echo $pic_url;?>" alt="<?php echo $pic_name;?>"><p><a href="#" title="我喜欢" class="img_on" rel="<?php echo $pic_id;?>"><?php echo $love;?></a></p></li>
        <?php }?>
     </ul>
     <div class="clear"></div>
     <div class="ad_demo"><script src="/js/ad_js/ad_demo.js" type="text/javascript"></script></div><br/>
</div>
<div id="footer">
    <p>Powered by helloweba.com  允许转载、修改和使用本站的DEMO，但请注明出处：<a href="http://www.helloweba.com">www.helloweba.com</a></p>
</div>
<p id="stat"><script type="text/javascript" src="http://js.tongji.linezing.com/1870888/tongji.js"></script></p>
</body>
</html>