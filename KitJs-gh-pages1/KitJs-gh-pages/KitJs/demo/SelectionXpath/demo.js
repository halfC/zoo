$kit.$(function() {
	if($kit.url.getParam('config')) {

		$kit.selection.highlight($kit.url.getParam('config'));
	}
	$kit.ev({
		el : document.body,
		ev : 'mouseup',
		fn : function(e) {
			if($kit.selection.getText() && $kit.selection.getText().length) {
				// var r = $kit.selection.getRange();
				// console.log($kit.selection.getStartContainer(r));
				// console.log($kit.selection.getStartOffset(r));
				// console.log($kit.selection.getEndContainer(r));
				// console.log($kit.selection.getEndOffset(r));
				// return;
				var mark = $kit.selection.getXpath($kit.selection.getRange()).full;
				var config = $kit.selection.getXpath($kit.selection.getRange());
				if(window.console) {
					console.log(mark);
					console.log('换个浏览器试试在console输入:$kit.selection.highlight("' + $kit.selection.getXpath($kit.selection.getRange()).full + '")');
					console.log('如果出现部分误差，和浏览器的渲染模式有关，因为有的浏览器渲染出来有空白，有的没有，纯小说文本，完全无问题');
				}
				//console && console.log($kit.selection.getXpath($kit.selection.getRange()).full);
				alert($kit.selection.highlight(config));
				window.open('?config=' + mark);
			}
		}
	})
	$kit.ev({
		el : '#a',
		ev : 'click',
		fn : function(e) {
			$kit.selection.removeHighlight();
		}
	})
});
