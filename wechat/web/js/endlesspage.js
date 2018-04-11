var gPageSize = 1;
var i = 1; //设置当前页数，全局变量
$(function () {
    //根据页数读取数据
    function getData(pagenumber) {		
        i++; //页码自动增加，保证下次调用时为新的一页。
        $.ajax({
            type: "post",
            url: "page.php",
            data: { pagesize: gPageSize, pagenumber: pagenumber },
            dataType: "json",
            success: function (data) {
                $(".loaddiv").hide();
                if (data.length > 0) {
                    var jsonObj = data.data;
                    insertDiv(jsonObj);
                }else{
					$(".div_null").show();
					$("#btn_Page").hide();
				}
            },
            beforeSend: function () {
                $(".loaddiv").show();
            },
            error: function () {
                $(".loaddiv").hide();
            }
        });
    }
    //初始化加载第一页数据
    getData(1);

    //生成数据html,append到div中
    function insertDiv(json) {
        var $mainDiv = $(".mainDiv");
        var html = '';
        for (var i = 0; i < json.length; i++) {
            html += '<div class="item ov">';
            html += ' <div class="title">' + json[i].D_Name + '</div>';
            html += '</div>';
        }
        $mainDiv.append(html);
    }

    //==============核心代码=============
    var scrollHandler = function () {
		var scrollTop = $(document).scrollTop(); //滚动条滚动高度
		var documentH = $(document).height();  //滚动条高度 
		var windowH = $(window).height(); //窗口高度

        if (scrollTop  >= documentH - windowH) {
            if (i % 100 === 0) {//每100页做一次停顿！
                getData(i);
                $(window).unbind('scroll');
                $("#btn_Page").show();
            }else{
                getData(i);
                $("#btn_Page").hide();
            }
        }
    }
    //定义鼠标滚动事件
    $(window).scroll(scrollHandler);
    //继续加载按钮事件
    $("#btn_Page").click(function () {
        getData(i);
        $(window).scroll(scrollHandler);
    });
});