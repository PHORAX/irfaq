/**
 * toggle FAQ Items
 **/
jQuery.noConflict();
jQuery(document).ready(function ($) {

	var plus = 'tx-irfaq-toggle-plus';
	var minus = 'tx-irfaq-toggle-minus';
	var title = '.tx-irfaq-toggle .tx-irfaq-dynheader';
	var content = '.tx-irfaq-toggle .tx-irfaq-dynans';
	var effectDuration = 250;

	$(title).click(function () {
		if ($(this).next().css('display') == 'block') {
			$(this).addClass(plus).removeClass(minus);
		} else {
			$(this).addClass(minus).removeClass(plus);
		}
		$(this).next().slideToggle(effectDuration);

		return false;
	}).next().hide();

	$('.tx-irfaq-toggle-all-show').click(function () {
		$(title).addClass(minus).removeClass(plus);
		$(content).show(effectDuration);
		return false;
	});
	$('.tx-irfaq-toggle-all-hide').click(function () {
		$(title).addClass(plus).removeClass(minus);
		$(content).hide(effectDuration);
		return false;
	});
});