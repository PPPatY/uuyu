$(document).ready(function () {
			$(".btn-select").each(function (e) {
			var value = $(this).find("ul li.selected").html();
			if (value != undefined) {
			$(this).find(".btn-select-input").val(value);
			$(this).find(".btn-select-value").html(value);
			}
			});
			});

			$(document).on('click', '.btn-select', function (e) {
			e.preventDefault();
			var ul = $(this).find("ul");
			if ($(this).hasClass("active")) {
			if (ul.find("li").is(e.target)) {
			var target = $(e.target);
			target.addClass("selected").siblings().removeClass("selected");
			var value = target.html();
			$(this).find(".btn-select-input").val(value);
			$(this).find(".btn-select-value").html(value);
			}
			ul.hide();
			$(this).removeClass("active");
			}
			else {
			$('.btn-select').not(this).each(function () {
			$(this).removeClass("active").find("ul").hide();
			});
			ul.slideDown(300);
			$(this).addClass("active");
			}
			});

			$(document).on('click', function (e) {
			var target = $(e.target).closest(".btn-select");
			if (!target.length) {
			$(".btn-select").removeClass("active").find("ul").hide();
			}
			});

				function tick() {
					var years, months, days;
					var intYears, intMonths, intDays;
					today = new Date(); //系统当前时间
					intYears = today.getFullYear(); //得到年份,getFullYear()比getYear()更普适
					intMonths = today.getMonth() + 1; //得到月份，要加1
					intDays = today.getDate(); //得到日期
					years = intYears + "-";

					if(intMonths < 10) {
						months = "0" + intMonths + "-";
					} else {
						months = intMonths + "-";
					}
					if(intDays < 10) {
						days = "0" + intDays + " ";
					} else {
						days = intDays + " ";
					}

					timeString = years + months + days;
					Clock.innerHTML = timeString;
					window.setTimeout("tick();", 1000);
				}
				window.onload = tick;