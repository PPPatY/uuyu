/*! h5game-sdk 2017-07-27 19:33:20 */
// 自执行函数开始
!
function(a, b, c) {
    var openurl = "//uuyu.com";
    "use strict";
    var d = Date.parse(new Date())/1000;
    var e = d,
    f = new Date,
    g = {},
    h = {
        addEventListener: function(a, b, c) {
            a.addEventListener ? a.addEventListener(b, c, !1) : a.attachEvent && a.attachEvent("on" + b, c)
        },
        jsonp: function(d) {
            if (d = d || {},
            !d.url || !d.callback) throw new Error("参数不合法");
            var e, f, g, h;
            e = d.callbackName || ("jsonp_" + c.random()).replace(".", ""),
            f = b.getElementsByTagName("head")[0],
            d.data[d.callback] = e,
            g = this.formatParams(d.data),
            h = b.createElement("script"),
            f.appendChild(h),
            a[e] = function(b) {
                f.removeChild(h),
                a.clearTimeout(h.timer),
                a[e] = null,
                "function" == typeof d.success && d.success(b)
            },
            h.src = d.url + "?" + g,
            d.time && (h.timer = a.setTimeout(function() {
                a[e] = null,
                h.parentElement && f.removeChild(h),
                "function" == typeof d.fail && d.fail({
                    code: 10002,
                    message: d.callbackName + ":请求超时"
                })
            },
            d.time))
        },
        ajax: function(b) {
            var c = "post" === b.type.toLowerCase() ? b.type.toLowerCase() : "get",
            d = b.url,
            e = b.data;
            "object" == typeof e && (e = this.formatParams(e));
            var f, g = ["text", "xml", "json"].indexOf(b.dataType.toLowerCase()) > -1 ? b.dataType.toLowerCase() : "json",
            h = b.success,
            i = b.error;
            try {
                if (f = new ActiveXObject("microsoft.xmlhttp"), "function" != typeof f.open) throw "not support"
            } catch(j) {
                try {
                    f = new XMLHttpRequest
                } catch(k) {
                    return a.alert("您的浏览器不支持ajax，请更换！"),
                    !1
                }
            }
            f.open(c, d, !0),
            "get" === c ? f.send(null) : "post" === c && (f.setRequestHeader("content-type", "application/x-www-form-urlencoded"), f.send(e)),
            f.onreadystatechange = function() {
                4 === f.readyState && (200 === f.status ? "text" === g ? "function" == typeof h && h(f.responseText) : "xml" === g ? "function" == typeof h && h(f.responseXML) : "json" === g && "function" == typeof h && h(JSON.parse(f.responseText)) : "function" == typeof i && i(f))
            }
        },
        formatParams: function(a) {
            var b, c = [];
            for (b in a) a.hasOwnProperty(b) && c.push(encodeURIComponent(b) + "=" + encodeURIComponent(a[b]));
            return c.join("&")
        },
        getQueryString: function(a, c) {
            try {
                var d, e, f = b.referrer;
                d = new RegExp("(^|&)" + a + "=([^&]*)(&|$)");
                var g = "";
                switch (c) {
                case "referrer":
                    var h = f ? f.split("?") : ["", ""];
                    g = h[1];
                    break;
                default:
                    g = this.getWindow().location.search.substr(1)
                }
                return e = "undefined" != typeof g ? g.match(d) : null,
                null !== e ? decodeURIComponent(e[2]) : null
            } catch(i) {
                return null
            }
        },
        getHdpu: function(a) {
            var b;
            b = a && a.hdpu ? decodeURIComponent(a.hdpu) : this.getQueryString("hdpu") || this.getQueryString("hdpu", "referrer");
            var c = {};
            if (null !== b) for (var d = b.split("|"), e = 0; e < d.length; e++) {
                var f = d[e].indexOf(":");
                f > 0 && (c[d[e].substring(0, f)] = d[e].substring(f + 1))
            }
            return c
        },
        browser: function() {
            var c = navigator.userAgent;
            navigator.appVersion;
            return {
                isTrident: function() {
                    return c.indexOf("Trident") > -1
                },
                isIEBelow10: function() {
                    return c.indexOf("Trident") > -1 && c.toLowerCase().indexOf("msie") > -1
                },
                isIECompatible: function() {
                    return c.toLowerCase().indexOf("compatible") > -1 && c.toLowerCase().indexOf("msie") > -1
                },
                isPresto: function() {
                    return c.indexOf("Presto") > -1
                },
                isWebKit: function() {
                    return c.indexOf("AppleWebKit") > -1
                },
                isGecko: function() {
                    return c.indexOf("Gecko") > -1 && -1 === c.indexOf("KHTML")
                },
                isMobile: function() {
                    return !! c.match(/AppleWebKit.*Mobile.*/)
                },
                isIos: function() {
                    return !! c.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/)
                },
                isAndroid: function() {
                    return c.indexOf("Android") > -1 || c.indexOf("Linux") > -1
                },
                isIPhone: function() {
                    return c.indexOf("iPhone") > -1 || c.indexOf("Mac") > -1
                },
                isIPad: function() {
                    return c.indexOf("iPad") > -1
                },
                isWebApp: function() {
                    return - 1 === c.indexOf("Safari") && !this.isUcBrowser() && !this.isSogouBrowser()
                },
                isUcBrowser: function() {
                    return c.indexOf("UCBrowser") > -1
                },
                isQQBrowser: function() {
                    return c.indexOf("MQQBrowser") > -1
                },
                isTBSBrowser: function() {
                    return "undefined" == typeof qb_bridge
                },
                isSogouBrowser: function() {
                    return c.indexOf("SogouMobileBrowser") > -1
                },
                is360Browser: function() {
                    return c.indexOf("QHBrowser") > -1
                },
                isChromeIOS: function() {
                    return c.indexOf("CriOS") > -1
                },
                isMaxtonIOS: function() {
                    return c.indexOf("MXiOS") > -1
                },
                isMaxtonAndroid: function() {
                    return c.indexOf("MxBrowser") > -1
                },
                isMaxtonPc: function() {
                    return c.indexOf("Maxthon") > -1
                },
                isOperaIOS: function() {
                    return c.indexOf("OPiOS") > -1
                },
                isBaiduBrowser: function() {
                    return /baidubrowser/i.test(c)
                },
                isSinaWeibo: function() {
                    return c.indexOf("Weibo") > -1
                },
                isPC: function() {
                    return /Windows/i.test(c)
                },
                isMac: function() {
                    return /Macintosh/i.test(c)
                },
                isHgameApp: function() {
                    return /Hgame|MicroClient/i.test(c)
                },
                isWeixin: function() {
                    var a = c.toLowerCase();
                    return a.indexOf("micromessenger") > -1
                },
                isQQZone: function() {
                    return c.indexOf("QZONEJSSDK") > -1
                },
                checkAgent: function(a, b) {
                    var d = b ? c: c.toLowerCase();
                    return d.indexOf(a) > -1
                },
                language: function() {
                    return (navigator.browserLanguage || navigator.language).toLowerCase()
                },
                isSafari: function() {
                    var a = c.match(/^Mozilla\/5.0 \(.*?\) AppleWebKit\/.*? \(.*?\) Version\/.*? Mobile\/.*? Safari\/\d+\.\d+\.\d+?$/);
                    return null !== a
                },
                isGC: function() {
                    return /gc\.hgame\.com\/home\//i.test(b.referrer)
                },
                isReview: function() {
                    return /[\/&]review[\/=]\d+/.test(b.referrer)
                },
                isLocalStorageSupport: function() {
                    try {
                        var b = "hdTestLS",
                        c = "hd";
                        a.localStorage.setItem(b, c);
                        var d = a.localStorage.getItem(b);
                        return d == c ? (a.localStorage.removeItem(b), !0) : !1
                    } catch(e) {
                        return ! 1
                    }
                }
            }
        },
        getWindow: function() {
            return a
        },
        urls: {
            online: {
                siteUrl:location.protocol + openurl,
                pc1Site:location.protocol + openurl+"/media.php/",
                pc2Site:location.protocol + openurl+"/mediawide.php/",
                gcSite: location.protocol + openurl+"/mobile.php/",
            },
            dev: {
                
            }
        }
    };
    a.xgGameUtil = h
} (window, document, Math);
// 自执行函数结束
// 
// 
// 
// 
// 
// GameXG 主类
GameXG = function(options) {
    //属性
    this.parentWin = window.parent;
    // 动态事件
    this.events = {};
    // 实用工具类
    this.utils = new GameXGUtils(this);
    // 初始化事件
    this.initEvent();
};
// 初始化事件
GameXG.prototype.initEvent = function() {
    var _this = this;
    window.addEventListener("message", function(e) {
        if (!e.data) return;
        switch (e.data.event) {
            // 创角回调
            case "game:createRole:callback":
                console.log('cp角色回调');
                _this.onCreateRoleCallback && _this.onCreateRoleCallback.call(null, e.data.data);
                break;
            // 登录sdk回调
            case "game:loginSdk:callback":
                _this.onCreateRoleCallback && _this.onCreateRoleCallback.call(null, e.data.data);
                break;
            // 支付sdk回调
            case "game:h5paySdk:callback":
                _this.onCreateRoleCallback && _this.onCreateRoleCallback.call(null, e.data.data);
                break;
            // 分享sdk回调
            case "game:shareSdk:callback":
                delete e.data.event;
                _this.onshareSdkCallback && _this.onshareSdkCallback.call(null, e.data);
                break;
            // 是否关注回调
            case "game:isSubscribeWx:callback":
                delete e.data.event;
                _this.onisSubscribeWxCallback && _this.onisSubscribeWxCallback.call(null, e.data);
                break;
        }
    });
};

// 向上发送消息
GameXG.prototype.postMessage = function(data) {
    this.parentWin.postMessage(data, "*");
};
// 创建角色
GameXG.prototype.jointCreateRole = function(role) {
    $this = this;
    if(typeof role == 'object'){
        role = JSON.stringify(role);
    }else{
        role = role;
    }
    var d = {
            url: xgGameUtil.url.gcSite + "Game/create_role",
            callbackName: "create_roleCallback",
            callback: "callback",
            data: {
                role: role,
            },
            time: 6e3,
            success: function(a) {
                console.log(a.msg);
            },
            fail: function(a) {
                console.log(a.message)
            }
        };
    xgGameUtil.jsonp(d);
};

GameXG.prototype.ready = function() {
    if(document.readyState=='complete'){
        console.log('sdk初始化完成');
        return true;
    }else{
        console.log('sdk未加载完成');
        return false;
    }
};

// 登录
GameXG.prototype.loginSdk = function(data, callback) {
    console.log(data);
    this.onCreateRoleCallback = callback;
    $this = this;
    if(!data.game_appid){
        $this.postMessage({ event: "game:loginSdk",code:0, data: "缺少game_appid" });return;
    }
    if(!data.sdkloginmodel){
        $this.postMessage({ event: "game:loginSdk",code:0, data: "缺少sdkloginmodel" });return;
    }
    if(!data.channelExt){
        $this.postMessage({ event: "game:loginSdk",code:0, data: "缺少channelExt" });return;
    }
    switch (data.sdkloginmodel) {
        case 'mobile.php':
            siteurl = xgGameUtil.url.gcSite;
            break;
        case 'media.php':
            siteurl = xgGameUtil.url.pc1Site;
            break;
        case 'mediawide.php':
            siteurl = xgGameUtil.url.pc2Site;
            break;
        default:
            $this.postMessage({ event: "game:loginSdk",code:0, data: data.sdkloginmodel+"--sdkloginmodel参数错误" });return;
            break;
    }
    var d = {
            url: siteurl + "GameUtile/loginSdk",
            callbackName: "loginCallback",
            callback: "callback",
            data: {
                game_appid: data.game_appid,
                channelExt: data.channelExt,
            },
            time: 6e3,
            success: function(a) {
                if(a.code==200){
                    resdata = a.content;
                }else if(a.code==201){
                    resdata = a.data;
                }else{
                    resdata = a.msg;
                }
                $this.postMessage({ event: "game:loginSdk",code:a.code, data: resdata },"*");
            },
            fail: function(a) {
                console.log(a.message)
            }
        };
    xgGameUtil.jsonp(d);
};

// sdk支付
GameXG.prototype.h5paySdk = function(data, callback) {
    this.onCreateRoleCallback = callback;
    $this = this;
    if(!data.game_appid){
        $this.postMessage({ event: "game:h5paySdk",code:0, data: "缺少game_appid" });return;
    }
    if(!data.sdkloginmodel){
        $this.postMessage({ event: "game:h5paySdk",code:0, data: "缺少sdkloginmodel" });return;
    }
    if(!data.channelExt){
        $this.postMessage({ event: "game:h5paySdk",code:0, data: "缺少channelExt" });return;
    }
    var islm = parent !=window?1:0;
    data.islm = islm;
    switch (data.sdkloginmodel) {
        case 'mobile.php':
            siteurl = xgGameUtil.url.gcSite;
            break;
        case 'media.php':
            siteurl = xgGameUtil.url.pc1Site;
            break;
        case 'mediawide.php':
            siteurl = xgGameUtil.url.pc2Site;
            break;
        default:
            $this.postMessage({ event: "game:h5paySdk",code:0, data: data.sdkloginmodel+"--sdkloginmodel参数错误" });return;
            break;
    }
    console.log(siteurl)
    var d = {
            url: siteurl + "GameUtile/h5paySdk",
            callbackName: "h5paySdkCallback",
            callback: "callback",
            data: data,
            time: 6e3,
            success: function(a) {
                if(a.code==200){
                    resdata = a.content;
                }else if(a.code==-200){
                    delete data.islm;
                    var dd = {
                            url: '//'+a.lm_url +data.sdkloginmodel+ "/GameUtile/h5paySdk",
                            callbackName: "h5paySdkCallback",
                            callback: "callback",
                            data: data,
                            time: 6e3,
                            success: function(a) {
                                if(a.code==200){
                                    resdata = a.content;
                                }else{
                                    resdata = a.msg;
                                }
                                $this.postMessage({ event: "game:h5paySdk",code:a.code, data: resdata },"*");
                            },
                            fail: function(a) {
                                console.log(a.message)
                            }
                        };
                    xgGameUtil.jsonp(dd);
                    return false;
                }else{
                    resdata = a.msg;
                }
                $this.postMessage({ event: "game:h5paySdk",code:a.code, data: resdata },"*");
            },
            fail: function(a) {
                console.log(a.message)
            }
        };
    xgGameUtil.jsonp(d);
};

// sdk分享
GameXG.prototype.shareSdk = function(data, callback) {
    this.onshareSdkCallback = callback;
    $this = this;
    siteurl = xgGameUtil.url.gcSite;
    var d = {
            url: siteurl + "GameUtile/shareSdk",
            callbackName: "shareSdkCallback",
            callback: "callback",
            data: data,
            time: 6e3,
            success: function(a) {
                if(a.code==200){
                    a.shareparams.imgUrl = xgGameUtil.urls.online.siteUrl+a.shareparams.imgUrl;
                    resdata = a.shareparams;
                }else{
                    resdata = a.msg;
                }
                $this.postMessage({ event: "game:shareSdk",code:a.code, data: resdata },"*");
            },
            fail: function(a) {
                console.log(a.message)
            }
        };
    xgGameUtil.jsonp(d);
};

//是否关注微信公众号
GameXG.prototype.isSubscribeWx = function(data, callback) {
    this.onisSubscribeWxCallback = callback;
    $this = this;
    siteurl = xgGameUtil.url.gcSite;
    var d = {
            url: siteurl + "GameUtile/isSubscribeWx",
            callbackName: "isSubscribeWxCallback",
            callback: "callback",
            data: data,
            time: 6e3,
            success: function(a) {
                if(a.code==200){
                    status = 1;
                }else{
                    status = 0;
                }
                self.postMessage({ event: "game:isSubscribeWx:callback",status:status},"*");
            },
            fail: function(a) {
                console.log(a.message)
            }
        };
    xgGameUtil.jsonp(d);
};







/***************************** 实用工具类开始 *********************************/

// 实用工具类
GameXGUtils = function(Gamexg) {
    this.Gamexg = Gamexg;
};

// 浅复制对象
GameXGUtils.prototype.extend = function(target, options) {
    if (target == undefined || target == null) {
        return options;
    } else {
        if (options) {
            for (var name in options) {
                target[name] = options[name];
            }
        }
        return target;
    }
};

// 判断 iOS 机型
GameXGUtils.prototype.isIOS = function() {
    return /iPhone|iPod|iPad|Mac/ig.test(navigator.userAgent);
};

// 判断 Android 机型
GameXGUtils.prototype.isAndroid = function() {
    return /android|linux/i.test(navigator.userAgent);
};

// 获取 URL（不包含 ? 及后面的参数）
GameXGUtils.prototype.getUrl = function() {
    if (location.origin && location.pathname) {
        return location.origin + location.pathname;
    } else {
        return location.href.match(/[^?#]+/i)[0];
    }
};

// 获取完整 URL（包含 ? 及后面的参数，不包含 # 和 ; 及后面的参数）
GameXGUtils.prototype.getFullUrl = function() {
    return location.href.match(/[^#;]+/i)[0];
};

// 获取 Path（不包含 ? 及后面的参数）
GameXGUtils.prototype.getPath = function() {
    if (location.pathname) {
        return location.pathname;
    } else {
        return location.href.match(/(?:http|https):\/\/[^\/]+([^?#;]+)/i)[1];
    }
};

// 获取 QueryString（?gameid=xpg&spid=uc）
GameXGUtils.prototype.getQueryString = function() {
    return location.search;
};

// 获取 URL 参数
GameXGUtils.prototype.getParameter = function(name) {
    var reg = new RegExp("[&?](" + name + ")=([^&?#]*)", "i");
    var r = window.location.search.match(reg);
    return r ? r[2] : null;
};

// 设置/替换 URL 参数
GameXGUtils.prototype.setParameter = function(url, name, value) {
    url = url.replace(/(#.*)/ig, "");
    var reg = new RegExp("([\?&])" + name + "=([^&]*)(?=&|$)", "i");
    if (reg.test(url)) {
        return url.replace(reg, "$1" + name + "=" + value);
    } else {
        return url + (url.indexOf("?") == -1 ? "?" : "&") + name + "=" + value;
    }
};

// 移除 URL 参数
GameXGUtils.prototype.removeParameter = function(url, name) {
    url = url.replace(/(#.*)/ig, "");
    var reg = new RegExp("([\?&])" + name + "=([^&]*)(?=&|$)", "i");
    if (reg.test(url)) {
        url = url.replace(reg, "");
        if (url.indexOf("?") == -1) url = url.replace("&", "?");
    }
    return url;
};

// 返回 min - max 之间的随机整数
GameXGUtils.prototype.getRandomInt = function(min, max) {
    return parseInt((Math.random() * (max - min + 1)) + min);
};

// 返回一个指定长度的随机字符串
GameXGUtils.prototype.getRandomString = function(len) {
    var base = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    var str = "";
    for (var i=0; i<len; i++) {
        var n = this.getRandomInt(1, base.length) - 1;
        str += base.substr(n, 1);
    }
    return str;
};

// 返回本地会话 ID
GameXGUtils.prototype.getSessionId = function() {
    if (!sessionStorage.sessionid) {
        sessionStorage.sessionid = this.getRandomString(40);
    }
    return sessionStorage.sessionid;
};

// 异步加载 js，参数形式：url | [ url1, url2, .. ] , callback
GameXGUtils.prototype.require = function(list, callback) {
    if (!list) return;
    if (typeof list == "string") list = [ list ];
    if (list.length == 0) {
        callback && callback.call(null);
        return;
    }
    var url = list.shift();
    var head = document.getElementsByTagName("head")[0] || document.documentElement;
    var node = document.createElement("script");
    var _this = this;
    node.addEventListener("load", function(e) {
        _this.require(list, callback);
    });
    node.addEventListener("error", function(e) {
        var message = "require fail on " + url;
        console.log(message);
    });
    node.async = true;
    node.src = url;
    head.appendChild(node);
};

// Ajax 请求，参数形式：ajax(url) | ajax(url, success) | ajax(options)
GameXGUtils.prototype.ajax = function() {
    // 默认值
    var options = {
        method: "GET",
        url: "",
        data: null,
        type: "json",
        success: null
    };
    switch (arguments.length) {
        case 1:
            // ajax(url) 或 ajax(options)
            if (typeof arguments[0] == "string") options.url = arguments[0];
            if (typeof arguments[0] == "object") options = this.extend(options, arguments[0]);
            break;
        case 2:
            // ajax(url, success)
            options.url = arguments[0];
            options.success = arguments[1];
            break;
    }
    // 随机参数
    options.url = this.setParameter(options.url, "_", Math.random());
    new GameXGUtilsAjax(this.Gamexg, options.method, options.url, options.data, options.type, options.success);
};

// JSONP 请求
GameXGUtils.prototype.jsonp = function(url, data, jsonparam, success) {
    url = this.setParameter(url, "_", Math.random());
    new GameXGUtilsJsonp(url, data, jsonparam, success).request();
};

// Ajax 类
GameXGUtilsAjax = function(Gamexg, method, url, data, type, success) {
    this.Gamexg = Gamexg;
    this.xmlhttp = null;
    if (window.XMLHttpRequest) {
        this.xmlhttp = new XMLHttpRequest();
    }
    else {
        this.xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    this.type = type;
    this.success = success;
    this.xmlhttp.open(method, url, true);
    var _this = this;
    this.xmlhttp.onreadystatechange = function() {
        _this.callback.apply(_this);
    };
    if (typeof data == "object" && data != null) {
        var a = [];
        for (var p in data) {
            a.push(p + "=" + escape(data[p]));
        }
        data = a.join("&");
    }
    this.xmlhttp.send(data);
};

// Ajax 请求回调
GameXGUtilsAjax.prototype.callback = function() {
    if (this.xmlhttp.readyState == 4 && this.xmlhttp.status == 200) {
        var data = null;
        switch (this.type) {
            case "text":
                data = this.xmlhttp.responseText;
                break;
            case "json":
                try {
                    data = JSON.parse(this.xmlhttp.responseText);
                }
                catch (e) {
                    data = this.xmlhttp.responseText;
                }
                break;
        }
        this.success && this.success.call(this.xmlhttp, data);
    }
};

// JSONP 类
GameXGUtilsJsonp = function(url, data, jsonparam, success, timeout) {
    var finish = false;
    var theHead = document.getElementsByTagName("head")[0] || document.documentElement;
    var scriptControll = document.createElement("script");
    var jsonpcallback = "jsonpcallback" + (Math.random() + "").substring(2);
    var collect = function() {
        if (theHead != null) {
            theHead.removeChild(scriptControll);
            try {
                delete window[jsonpcallback];
            } catch (ex) { }
            theHead = null;
        }
    };
    var init = function() {
        scriptControll.charset = "utf-8";
        theHead.insertBefore(scriptControll, theHead.firstChild);
        window[jsonpcallback] = function(responseData) {
            finish = true;
            success(responseData);
        };
        jsonparam = jsonparam || "callback";
        if (url.indexOf("?") > 0) {
            url = url + "&" + jsonparam + "=" + jsonpcallback;
        } else {
            url = url + "?" + jsonparam + "=" + jsonpcallback;
        }
        if (typeof data == "object" && data != null) {
            for (var p in data) {
                url = url + "&" + p + "=" + escape(data[p]);
            }
        }
    };
    var timer = function() {
        if (typeof window[jsonpcallback] == "function") {
            collect();
        }
        if (typeof timeout == "function" && finish == false) {
            timeout();
        }
    };
    this.request = function() {
        init();
        scriptControll.src = url;
        window.setTimeout(timer, 10000);
    };
};
/***************************** 实用工具类结束 *********************************/

var js = document.scripts;
xgGameUtil.url = xgGameUtil.urls['online'];
xgGame = new GameXG;
