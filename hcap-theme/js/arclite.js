
// ie 6 png fix
(function($) {
	$.fn.pngfix = function(options) {

		// Review the Microsoft IE developer library for AlphaImageLoader reference
		// http://msdn2.microsoft.com/en-us/library/ms532969(VS.85).aspx

		// ECMA scope fix
		var elements 	= this;
		var settings 	= $.extend({
			imageFixSrc: 	false,
			sizingMethod: 	false
		}, options);

		if(!$.browser.msie || ($.browser.msie &&  $.browser.version >= 7)) {
			return(elements);
		}

		function setFilter(el, path, mode) {
			var fs = el.attr("filters");
			var alpha = "DXImageTransform.Microsoft.AlphaImageLoader";
			if (fs[alpha]) {
				fs[alpha].enabled = true;
				fs[alpha].src = path;
				fs[alpha].sizingMethod = mode;
			} else {
				el.css("filter", 'progid:' + alpha + '(enabled="true", sizingMethod="' + mode + '", src="' + path + '")');
			}
		}

		function setDOMElementWidth(el) {
			if(el.css("width") == "auto" & el.css("height") == "auto") {
				el.css("width", el.attr("offsetWidth") + "px");
			}
		}

		return(
			elements.each(function() {

				// Scope
				var el = $(this);

				if(el.attr("tagName").toUpperCase() == "IMG" && (/\.png/i).test(el.attr("src"))) {
					if(!settings.imageFixSrc) {

						// Wrap the <img> in a <span> then apply style/filters,
						// removing the <img> tag from the final render
						el.wrap("<span></span>");
						var par = el.parent();
						par.css({
							height: 	el.height(),
							width: 		el.width(),
							display: 	"inline-block"
						});
						setFilter(par, el.attr("src"), "scale");
						el.remove();
					} else if((/\.gif/i).test(settings.imageFixSrc)) {

						// Replace the current image with a transparent GIF
						// and apply the filter to the background of the
						// <img> tag (not the preferred route)
						setDOMElementWidth(el);
						setFilter(el, el.attr("src"), "image");
						el.attr("src", settings.imageFixSrc);
					}

				} else {
					var bg = new String(el.css("backgroundImage"));
					var matches = bg.match(/^url\("(.*)"\)$/);
					if(matches && matches.length) {

						// Elements with a PNG as a backgroundImage have the
						// filter applied with a sizing method relevant to the
						// background repeat type
						setDOMElementWidth(el);
						el.css("backgroundImage", "none");

						// Restrict scaling methods to valid MSDN defintions (or one custom)
						var sc = "crop";
						if(settings.sizingMethod) {
							sc = settings.sizingMethod;
						}
						setFilter(el, matches[1], sc);

						// Fix IE peek-a-boo bug for internal links
						// within that DOM element
						el.find("a").each(function() {
							$(this).css("position", "relative");
						});
					}
				}

			})
		);
	}

})(jQuery)


// fixes for IE-7 cleartype bug on fade in/out
jQuery.fn.fadeIn = function(speed, callback) {
 return this.animate({opacity: 'show'}, speed, function() {
  if (jQuery.browser.msie) this.style.removeAttribute('filter');
  if (jQuery.isFunction(callback)) callback();
 });
};

jQuery.fn.fadeOut = function(speed, callback) {
 return this.animate({opacity: 'hide'}, speed, function() {
  if (jQuery.browser.msie) this.style.removeAttribute('filter');
  if (jQuery.isFunction(callback)) callback();
 });
};

jQuery.fn.fadeTo = function(speed,to,callback) {
 return this.animate({opacity: to}, speed, function() {
  if (to == 1 && jQuery.browser.msie) this.style.removeAttribute('filter');
  if (jQuery.isFunction(callback)) callback();
 });
};

// simple nav. fade
function navigationeffects(){
jQuery(" ul#nav ul ").css({display: "none"}); 
jQuery("ul#nav li").hover(function(){
  jQuery(this).find('ul:first').css({visibility: "visible",display: "none"}).fadeIn(333);
 },function(){
    jQuery(this).find('ul:first').css({visibility: "hidden"});
   });
}

// comment.js by mg12 - http://www.neoease.com/
(function() {
    function $$$(id) { return document.getElementById(id); }
    function setStyleDisplay(id, status) {	$$$(id).style.display = status; }

    window['MGJS'] = {};
    window['MGJS']['$$$'] = $$$;
    window['MGJS']['setStyleDisplay'] = setStyleDisplay;

})();


(function() {
  function quote(authorId, commentId, commentBodyId, commentBox) {
	var author = MGJS.$$$(authorId).innerHTML;
	var comment = MGJS.$$$(commentBodyId).innerHTML;

	var insertStr = '<blockquote cite="#' + commentBodyId + '">';
	insertStr += '\n<strong><a href="#' + commentId + '">' + author.replace(/\t|\n|\r\n/g, "") + '</a> :</strong>';
	insertStr += comment.replace(/\t/g, "");
	insertStr += '</blockquote>\n';

	insertQuote(insertStr, commentBox);
  }

  function insertQuote(insertStr, commentBox) {
   if(MGJS.$$$(commentBox) && MGJS.$$$(commentBox).type == 'textarea') {
		field = MGJS.$$$(commentBox);

	} else {
		alert("The comment box does not exist!");
		return false;
	}

	if(document.selection) {
		field.focus();
		sel = document.selection.createRange();
		sel.text = insertStr;
		field.focus();

	} else if (field.selectionStart || field.selectionStart == '0') {
		var startPos = field.selectionStart;
		var endPos = field.selectionEnd;
		var cursorPos = startPos;
		field.value = field.value.substring(0, startPos)
					  + insertStr
					  + field.value.substring(endPos, field.value.length);
		cursorPos += insertStr.length;
		field.focus();
		field.selectionStart = cursorPos;
		field.selectionEnd = cursorPos;

	} else {
		field.value += insertStr;
		field.focus();
	}
  }

  window['MGJS_CMT'] = {};
  window['MGJS_CMT']['quote'] = quote;

})();


// init.

jQuery(document).ready(function(){
  jQuery(".comment .avatar").pngfix();


  // rss popup link
  jQuery("ul.menu li.cat-item").hover(function() {
		jQuery(this).find("a.rss").animate({opacity: "show", top: "1", right: "6"}, "333");
	}, function() {
		jQuery(this).find("a.rss").animate({opacity: "hide", top: "-15", right: "6"}, "333");
	});

  // reply/quote links
  jQuery(".comment-head").hover(function() {
		jQuery(this).find("p.controls").animate({opacity: "show", top: "4", right: "6"}, "333");
	}, function() {
		jQuery(this).find("p.controls").animate({opacity: "hide", top: "-15", right: "6"}, "333");
	});

  // fade span
  jQuery('.fadeThis, ul#footer-widgets li.widget li').append('<span class="hover"></span>').each(function () {
    var jQueryspan = jQuery('> span.hover', this).css('opacity', 0);
	  jQuery(this).hover(function () {
	    jQueryspan.stop().fadeTo(333, 1);
	  }, function () {
	    jQueryspan.stop().fadeTo(333, 0);
	  });
	});


  jQuery('#sidebar ul.menu li li a').mouseover(function () {
   	jQuery(this).animate({ marginLeft: "5px" }, 100 );
  });
  jQuery('#sidebar ul.menu li li a').mouseout(function () {
    jQuery(this).animate({ marginLeft: "0px" }, 100 );
  });


  jQuery('a.toplink').click(function(){
    jQuery('html').animate({scrollTop:0}, 'slow');
  });

  navigationeffects();

  // widget title adjustments
  jQuery('.titlewrap').each(function(){ jQuery(this).prependTo(this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode);  });
 });