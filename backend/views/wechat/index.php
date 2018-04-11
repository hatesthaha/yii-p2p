<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = '自定义菜单管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<?=Html::jsFile('@web/js/angular.min.js')?>
<?=Html::jsFile('@web/adminlte/js/jquery.min.js')?>
<style>
    .hover li{
        list-style: none;
    }
</style>
<div>
    <div class="row" ng-controller="menuDesigner">
        <div class="col-md-6">
            <tbody class="designer">

            <ul class="hover" ng-repeat="menu in menus">
                <li style="border-top:none;">
                    <div class="parentmenu">
                        <input type="text" class="form-control" style="display:inline-block;width:300px;" ng-model="menu.name">
                        <a href="javascript:;" title="拖动调整此菜单位置" style="border-left:0;"><i class="fa fa-arrows"></i></a>
                        <a href="javascript:;" ng-click="setAction(menu);" title="设置此菜单动作"><i class="fa fa-pencil"></i> 设置此菜单动作</a>
                        <a href="javascript:;" ng-click="deleteMenu(menu)" title="删除此菜单"><i class="fa fa-remove"></i> 删除此菜单</a>
                        <a href="javascript:;" ng-click="addSubMenu(menu, this);" title="添加子菜单"><i class="fa fa-plus"></i> 添加子菜单</a>
                    </div>
                    <div class="designer sonmenu">
                        <div ng-repeat="sub in menu.subMenus" style="margin-top:20px;padding-left:80px;">
                            <input type="text" class="form-control" style="display:inline-block;width:220px;" ng-model="sub.name">
                            <a href="javascript:;" title="拖动调整此菜单位置" style="border-left:0;"><i class="fa fa-arrows"></i></a>
                            <a href="javascript:;" ng-click="setAction(sub);" title="设置此菜单动作"><i class="fa fa-pencil"></i> 设置此菜单动作</a>
                            <a href="javascript:;" ng-click="deleteMenu(menu, sub, this);" title="删除此菜单"><i class="fa fa-remove"></i> 删除此菜单</a>
                        </div>
                    </div>
                </li>

            </ul>

            </tbody>

            <a href="javascript:;" ng-click="addMenu();">添加菜单</a>

            <input type="button" value="保存菜单结构" class="btn btn-primary" ng-click="saveMenu();"/>
        </div>
        <div id="dialog" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h3>选择菜单 【{{activeMenu.name || '未命名菜单'}}】 要执行的操作</h3>
                    </div>
                    <div class="modal-body">
                        <label class="radio-inline">
                            <input type="radio" name="ipt" ng-model="activeMenu.type" value="view"> 链接
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="ipt" ng-model="activeMenu.type" value="click"> 触发关键字
                        </label>
                        <div ng-show="activeMenu.type == 'view';">
                            <hr />
                            <div class="input-group">
                                <input class="form-control" id="ipt-url" type="text" ng-model="activeMenu.url" />
                                <div class="input-group-btn">
                                    <button class="btn btn-primary" id="search" ng-click="select_link()"><i class="fa fa-external-link"></i> </button>
                                </div>
                            </div>
                            <span class="help-block">指定点击此菜单时要跳转的链接（注：链接需加http://）</span>
                            <span class="help-block"><strong>注意: 由于接口限制. 如果你没有网页oAuth接口权限, 这里输入链接直接进入微站个人中心时将会有缺陷(有可能获得不到当前访问用户的身份信息. 如果没有oAuth接口权限, 建议你使用图文回复的形式来访问个人中心)</strong></span>
                        </div>
                        <div ng-show="activeMenu.type != 'view';" style="position:relative">
                            <hr />
                            <div class="input-group">
                                <input class="form-control" id="ipt-forward" type="text" ng-model="activeMenu.forward"/>
                                <div class="input-group-btn">
                                    <button class="btn btn-primary" id="search" ng-click="search()"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                            <div id="key-result" style="width:100%;position:absolute;top:55px;left:0px;display:none;z-index:10000">
                                <ul class="dropdown-menu" style="display:block;width:88%;"></ul>
                            </div>
                            <span class="help-block">指定点击此菜单时要执行的操作, 你可以在这里输入关键字, 那么点击这个菜单时就就相当于发送这个内容至系统系统</span>
                            <span class="help-block"><strong>这个过程是程序模拟的, 比如这里添加关键字: 优惠券, 那么点击这个菜单是, 系统系统相当于接受了粉丝用户的消息, 内容为"优惠券"</strong></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:;" ng-click="saveMenuAction();" class="pull-right btn btn-primary span2" data-dismiss="modal">保存</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        without = function(list, rejectedItem) {
            var item, _i, _len, _results;
            _results = [];
            for (_i = 0, _len = list.length; _i < _len; _i++) {
                item = list[_i];
                if (item !== rejectedItem) {
                    _results.push(item);
                }
            }
            return _results;
        };
        var util = {}
        util.message = function(msg, redirect, type){
            if(!redirect && !type){
                type = 'info';
            }
            if($.inArray(type, ['success', 'error', 'info', 'warning']) == -1) {
                type = '';
            }
            if(type == '') {
                type = redirect == '' ? 'error' : 'success';
            }

            var icons = {
                success : 'check-circle',
                error :'times-circle',
                info : 'info-circle',
                warning : 'exclamation-triangle'
            };
            var p = '';
            if(redirect && redirect.length > 0){
                if(redirect == 'back'){
                    p = '<p>[<a href="javascript:;" onclick="history.go(-1)">返回上一页</a>] &nbsp; [<a href="./?refresh">回首页</a>]</p>';
                }else{
                    p = '<p><a href="' + redirect + '" target="main" data-dismiss="modal" aria-hidden="true">如果你的浏览器在 <span id="timeout"></span> 秒后没有自动跳转，请点击此链接</a></p>';
                }
            }
            var content =
                '			<i class="pull-left fa fa-4x fa-'+icons[type]+'"></i>'+
                '			<div class="pull-left"><p>'+ msg +'</p>' +
                p +
                '			</div>'+
                '			<div class="clearfix"></div>';
            var footer =
                '			<button type="button" class="btn btn-default" data-dismiss="modal">确认</button>';
            var modalobj = util.dialog('系统提示', content, footer, {'containerName' : 'modal-message'});
            modalobj.find('.modal-content').addClass('alert alert-'+type);
            if(redirect) {
                var timer = '';
                timeout = 3;
                modalobj.find("#timeout").html(timeout);
                modalobj.on('show.bs.modal', function(){doredirect();});
                modalobj.on('hide.bs.modal', function(){timeout = 0;doredirect(); });
                modalobj.on('hidden.bs.modal', function(){modalobj.remove();});
                function doredirect() {
                    timer = setTimeout(function(){
                        if (timeout <= 0) {
                            modalobj.modal('hide');
                            clearTimeout(timer);
                            window.location.href = redirect;
                            return;
                        } else {
                            timeout--;
                            modalobj.find("#timeout").html(timeout);
                            doredirect();
                        }
                    }, 1000);
                }
            }
            modalobj.modal('show');
            return modalobj;
        };
        util.dialog = function(name, content, footer, options) {
            if(!options) {
                options = {};
            }
            if(!options.containerName) {
                options.containerName = 'modal-message';
            }
            var modalobj = $('#' + options.containerName);
            if(modalobj.length == 0) {
                $(document.body).append('<div id="' + options.containerName + '" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"></div>');
                modalobj = $('#' + options.containerName);
            }
            html =
                '<div class="modal-dialog">'+
                '	<div class="modal-content">';
            if(name) {
                html +=
                    '<div class="modal-header">'+
                    '	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'+
                    '	<h3>' + name + '</h3>'+
                    '</div>';
            }
            if(content) {
                if(!$.isArray(content)) {
                    html += '<div class="modal-body">'+ content + '</div>';
                } else {
                    html += '<div class="modal-body">正在加载中</div>';
                }
            }
            if(footer) {
                html +=
                    '<div class="modal-footer">'+ footer + '</div>';
            }
            html += '	</div></div>';
            modalobj.html(html);
            if(content && $.isArray(content)) {
                var embed = function(c) {
                    modalobj.find('.modal-body').html(c);
                };
                if(content.length == 2) {
                    $.post(content[0], content[1]).success(embed);
                } else {
                    $.get(content[0]).success(embed);
                }
            }
            return modalobj;
        };
        angular.module('app', []).controller('menuDesigner', function ($scope, $http) {
            $scope.menus = <?php echo json_encode($menus); ?>;
            $scope.activeMenu = <?php echo json_encode($menus); ?>;

            $scope.addMenu = function () {
                if($scope.menus.length >= 5) {
                    return;
                }
                $scope.menus.push({
                    name: '',
                    type: 'url',
                    url: '',
                    forward: '',
                    subMenus: []
                });
            };

            $scope.addSubMenu = function(menu, obj) {
                if(menu.subMenus.length >= 5) {
                    return;
                }
                menu.subMenus.push({
                    name: '',
                    type: 'url',
                    url: '',
                    forward: ''
                });

                $('.parentmenu').eq(obj.$index).find('a').eq(1).hide();
            };

            $scope.deleteMenu = function(menu, sub, obj) {
                if(sub) {
                    if (typeof obj == 'object') {
                        var text = $('.sonmenu').eq(obj.$parent.$index).find('input[type="text"]').eq(obj.$index);
                        if (text.val() != '') {
                            if (confirm('将删除该菜单, 是否继续? ')) {
                                if (menu.subMenus.length == 1) {
                                    $('.parentmenu').eq(obj.$parent.$index).find('a').eq(2).show();
                                }
                                menu.subMenus = without(menu.subMenus, sub);
                            }
                        } else {
                            if (menu.subMenus.length == 1) {
                                $('.parentmenu').eq(obj.$parent.$index).find('a').eq(2).show();
                            }
                            menu.subMenus = without(menu.subMenus, sub);
                        }
                    }
                } else {
                    if(menu.subMenus.length > 0 && !confirm('将同时删除所有子菜单, 是否继续? ')) {
                        return;
                    }
                    $scope.menus = without($scope.menus, menu);
                }
            };
            $scope.setAction = function(menu) {
                $scope.activeMenu = menu;
                console.log($scope.activeMenu.url);
                if(!$scope.activeMenu.url) {
                    $scope.activeMenu.url = 'http://';
                }
                var header = "选择菜单 【{{activeMenu.name || '未命名菜单'}}】 要执行的操作";
                var content = $("#url").html();
                var menu = util.dialog(header, content, 'queee');

                $('#dialog').modal('show');
            };
            $scope.saveMenuAction = function(){
                $('#dialog').modal('hide');
            };
            $scope.saveMenu = function(version){
                var menus = $scope.menus;
                if (menus.length < 1) {
                    util.message('请您至少输入一个自定义菜单.', '', 'error');
                    return ;
                }
                if(menus.length > 5) {
                    util.message('不能输入超过 5 个主菜单才能保存.', '', 'error');
                    return;
                }
                var error = {empty: false, message: ''};
                angular.forEach(menus, function(val){
                    if(val.subMenus.length > 0) {
                        angular.forEach(val.subMenus, function(v){
                            if($.trim(v.name) == '') {
                                this.empty = true;
                            }
                            if((v.type == 'url' && $.trim(v.url) == '') || (v.type == 'forward' && $.trim(v.forward) == '')) {
                                this.message += '菜单【' + val.name + '】的子菜单【' + v.name + '】未设置操作选项. <br />';
                            }
                        }, error);
                    } else {
                        if((val.type == 'url' && $.trim(val.url) == '') || (val.type == 'forward' && $.trim(val.forward) == '')) {
                            this.message += '菜单【' + val.name + '】不存在子菜单并且未设置操作选项. <br />';
                        }
                    }

                    if($.trim(val.name) == '') {
                        this.empty = true;
                    }
                }, error)
                if(error.empty) {
                    util.message('存在未输入名称的菜单.', '', 'error');
                    return;
                }
                if(error.message) {
                    util.message(error.message, '', 'error');
                    return;
                }


                var params = {};

                params.menus = angular.copy($scope.menus);
                params.method = 'save';




                $http.post('addmenu', params).success(function(dat, status){
                    console.log(dat);
                    if(dat != 'success') {
                        if (typeof dat == 'string') {
                            $('#errorinfo').empty().append(dat);
                        } else {
                            util.message(dat.message, '', 'error');
                        }
                    } else {
                        util.message('菜单保存成功. ', location.href);
                    }
                });
                return;

            };
        });
        angular.bootstrap(document, ['app']);


    </script>
</div>







