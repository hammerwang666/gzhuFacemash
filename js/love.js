/**
 * Created with JetBrains PhpStorm.
 * User: Fanz
 * Date: 13-3-19
 * Time: 下午8:43
 * To change this template use File | Settings | File Templates.
 */

$(function(){
    $("p a").click(function(){
        var love = $(this);
        var id = love.attr("rel"); //对应id
        love.fadeOut(300); //渐隐效果
        $.ajax({
            type:"POST",
            url:"love.php",
            data:"id="+id,
            cache:false, //不缓存此页面
            success:function(data){
                love.html(data);
                love.fadeIn(300); //渐显效果
            }
        });
        return false;
    });
});