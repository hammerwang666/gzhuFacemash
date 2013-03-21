/**
 * Created with JetBrains PhpStorm.
 * User: Fan
 * Date: 13-3-20
 * Time: 上午10:30
 * To change this template use File | Settings | File Templates.
 */
angular.module('photoManage', ['photoService']);
function photoCtrl($scope, photoFac){



    photoFac.getXyAll({}, function (arg_result) {
        if (arg_result['isSuccess'] == true) {

            $scope.xyAll = arg_result['xyAll'];
            var len = $scope.xyAll.length;
         //   console.log($scope.xyAll[0]);
            for(var x=0; x<len;x++){
                $scope.xyAll[x]['class'] = '';
            }
            $scope.xyAll[0]['class'] = 'active';

            $scope.xyPhoto = 123;
            //  console.log( $scope.pageArr);
        } else {
            if (document.getElementById('loadGoodsExchangeId')) {
                document.getElementById('loadGoodsExchangeId').style.display = "none";
            }
        }
    });


    $scope.changePage = function(arg_page){
        console.log(arg_page);
       if(arg_page=="All"){
           document.getElementById('allId').className = "current_page_item";
           document.getElementById('topId').className = "";
           $scope.getPhotoByXy(14);
           if(document.getElementById('chooseXyId')){
               document.getElementById('chooseXyId').style.display = "";
           }
       }else{
           document.getElementById('allId').className = "";
           document.getElementById('topId').className = "current_page_item";
           photoFac.getTopGirls({}, function (arg_result) {
               if (arg_result['isSuccess'] == true) {

                   $scope.photos = arg_result['photo'];

//            var len = $scope.photos.length;

                   //  console.log( $scope.pageArr);
               } else {
                   if (document.getElementById('loadGoodsExchangeId')) {
                       document.getElementById('loadGoodsExchangeId').style.display = "none";
                   }
               }
           });
           if(document.getElementById('chooseXyId')){
               document.getElementById('chooseXyId').style.display = "none";
           }
       }
    } ;


    $scope.getPhotoByXy = function(arg_xydm){
        if($scope.xyAll){
            var len = $scope.xyAll.length;
            for(var x=0; x<len;x++){
                if(arg_xydm != $scope.xyAll[x]['XYDM']){
                    $scope.xyAll[x]['class'] =  '';
                } else{
                    $scope.xyAll[x]['class'] = 'active';
                }

            }
        }




        photoFac.getPhoto({XYDM:arg_xydm}, function (arg_result) {
            if (arg_result['isSuccess'] == true) {

                $scope.photos = arg_result['photo'];

//            var len = $scope.photos.length;

                //  console.log( $scope.pageArr);
            } else {
                if (document.getElementById('loadGoodsExchangeId')) {
                    document.getElementById('loadGoodsExchangeId').style.display = "none";
                }
            }
        });

    };

    $scope.love = function(arg_photo){
        var userName = arg_photo['USERNAME'];

        var love = Number(arg_photo['LOVE']);
        love++;
        arg_photo['LOVE'] = love;
        photoFac.love({userName:userName, love: love}, function (arg_result) {
            if (arg_result['isSuccess'] == true) {

              //  $scope.photos = arg_result['photo'];

//            var len = $scope.photos.length;

                //  console.log( $scope.pageArr);
            } else {
                if (document.getElementById('loadGoodsExchangeId')) {
                    document.getElementById('loadGoodsExchangeId').style.display = "none";
                }
            }
        });

    }


}