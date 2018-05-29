/**
 * dialog
 * @authors baojunbo <baojunbo@gmail.com>
 * @date    2014-04-03 10:52:49
 */

(function($){
	$.string = {
		numberFormat: function(str) {
			var x = str.split('.');
			x1 = x[0];
			x2 = x.length > 1 ? '.' + x[1] : ''; var rgx = /(\d+)(\d{3})/;
			while (rgx.test(x1)) {
				x1 = x1.replace(rgx, '$1' + ',' + '$2');
			}
			return x1 + x2;
		}
	};
})(jQuery);