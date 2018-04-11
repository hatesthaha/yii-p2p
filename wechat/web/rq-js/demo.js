function diy_select() {
    this.init.apply(this, arguments)
};
diy_select.prototype = {
    init: function (opt) {
        this.setOpts(opt);
        this.o = this.getByClass(this.opt.TTContainer, document, 'div'); //容器
        this.b = this.getByClass(this.opt.TTDiy_select_btn); //按钮
        this.t = this.getByClass(this.opt.TTDiy_select_txt); //显示
        this.l = this.getByClass(this.opt.TTDiv_select_list); //列表容器
        this.ipt = this.getByClass(this.opt.TTDiy_select_input); //列表容器
        this.lengths = this.o.length;
        this.showSelect();
    },
    addClass: function (o, s) //添加class
    {
        o.className = o.className ? o.className + ' ' + s : s;
    },
    removeClass: function (o, st) //删除class
    {
        var reg = new RegExp('\\b' + st + '\\b');
        o.className = o.className ? o.className.replace(reg, '') : '';
    },
    addEvent: function (o, t, fn) //注册事件
    {
        return o.addEventListener ? o.addEventListener(t, fn, false) : o.attachEvent('on' + t, fn);
    },
    showSelect: function () //显示下拉框列表
    {
        var This = this;
        var iNow = 0;
        this.addEvent(document, 'click', function () {
            for (var i = 0; i < This.lengths; i++) {
                This.l[i].style.display = 'none';
            }
        })
        for (var i = 0; i < this.lengths; i++) {
            this.l[i].index = this.b[i].index = this.t[i].index = i;
            this.t[i].onclick = this.b[i].onclick = function (ev) {
                var e = window.event || ev;
                var index = this.index;
                This.item = This.l[index].getElementsByTagName('li');

                This.l[index].style.display = This.l[index].style.display == 'block' ? 'none' : 'block';
                for (var j = 0; j < This.lengths; j++) {
                    if (j != index) {
                        This.l[j].style.display = 'none';
                    }
                }
                This.addClick(This.item);
                e.stopPropagation ? e.stopPropagation() : (e.cancelBubble = true); //阻止冒泡
            }
        }
    },
    addClick: function (o) //点击回调函数
    {

        if (o.length > 0) {
            var This = this;
            for (var i = 0; i < o.length; i++) {
                o[i].onmouseover = function () {
                    This.addClass(this, This.opt.TTFcous);
                }
                o[i].onmouseout = function () {
                    This.removeClass(this, This.opt.TTFcous);
                }
                o[i].onclick = function () {
                    var index = this.parentNode.index; //获得列表
                    This.t[index].innerHTML = This.ipt[index].value = this.innerHTML.replace(/^\s+/, '').replace(/\s+&/, '');
                    This.l[index].style.display = 'none';
                }
            }
        }
    },
    getByClass: function (s, p, t) //使用class获取元素
    {
        var reg = new RegExp('\\b' + s + '\\b');
        var aResult = [];
        var aElement = (p || document).getElementsByTagName(t || '*');

        for (var i = 0; i < aElement.length; i++) {
            if (reg.test(aElement[i].className)) {
                aResult.push(aElement[i])
            }
        }
        return aResult;
    },

    setOpts: function (opt) //以下参数可以不设置  //设置参数
    {
        this.opt = {
            TTContainer: 'diy_select', //控件的class
            TTDiy_select_input: 'diy_select_input', //用于提交表单的class
            TTDiy_select_txt: 'diy_select_txt', //diy_select用于显示当前选中内容的容器class
            TTDiy_select_btn: 'diy_select_btn', //diy_select的打开按钮
            TTDiv_select_list: 'diy_select_list', //要显示的下拉框内容列表class
            TTFcous: 'focus' //得到焦点时的class
        }
        for (var a in opt) //赋值 ,请保持正确,没有准确判断的
        {
            this.opt[a] = opt[a] ? opt[a] : this.opt[a];
        }
    }
}


var TTDiy_select = new diy_select({ //参数可选
    TTContainer: 'diy_select', //控件的class
    TTDiy_select_input: 'diy_select_input', //用于提交表单的class
    TTDiy_select_txt: 'diy_select_txt', //diy_select用于显示当前选中内容的容器class
    TTDiy_select_btn: 'diy_select_btn', //diy_select的打开按钮
    TTDiv_select_list: 'diy_select_list', //要显示的下拉框内容列表class
    TTFcous: 'focus' //得到焦点时的class
}); //如同时使用多个时请保持各class一致.