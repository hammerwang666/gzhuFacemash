/**
 * Created with JetBrains PhpStorm.
 * User: Fan
 * Date: 13-3-20
 * Time: 上午10:52
 * To change this template use File | Settings | File Templates.
 */


angular.module('photoService', ['ngResource']).
    factory('photoFac', function($resource){
        var _result = $resource('src/:op',
            { }, {
                getPhoto:{
                    method:'GET',
                    params:{
                        op:'getPhoto.php'
                    }
                    //isArray : true
                },
                getXyAll:{
                    method:'GET',
                    params:{
                        op:'getXyAll.php'
                    }
                    //isArray : true
                } ,
                love:{
                    method:'SAVE',
                    params:{
                        op:'love.php'
                    }
                    //isArray : true
                } ,
                getTopGirls:{
                    method:'GET',
                    params:{
                        op:'getTopGirls.php'
                    }
                    //isArray : true
                }
            }
        );
        return _result;
    });