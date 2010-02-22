/*

            _/    _/_/    _/_/_/_/_/                              _/       
               _/    _/      _/      _/_/    _/    _/    _/_/_/  _/_/_/    
          _/  _/  _/_/      _/    _/    _/  _/    _/  _/        _/    _/   
         _/  _/    _/      _/    _/    _/  _/    _/  _/        _/    _/    
        _/    _/_/  _/    _/      _/_/      _/_/_/    _/_/_/  _/    _/     
       _/                                                                  
    _/

    Created by David Kaneda <http://www.davidkaneda.com>
    Documentation and issue tracking on Google Code <http://code.google.com/p/jqtouch/>
    
    Special thanks to Jonathan Stark <http://jonathanstark.com/>
    and pinch/zoom <http://www.pinchzoom.com/>
    
    (c) 2009 by jQTouch project members.
    See LICENSE.txt for license.

*/

(function($) {
    if ($.jQTouch) {
        $.jQTouch.addExtension(function Notice(jQT){            
            $.fn.makeNotice = function(options){
                var defaults = {
                    align: 'top',
                    spacing: 20
                }
                var settings = $.extend({}, defaults, options);
                settings.align = (settings.align == 'top') ? 'top' : 'bottom';
                
                return this.each(function(){
                    var $el = $(this);
                    
                    $el.css({
						'top': '50px',
						'left': '50px',
                        'display': 'block',
                        'min-height': '0 !important'
                    }).data('settings', settings);
                    
                });
            }

			$.fn.hideNotice = function(){
                return this.each(function(){
                    var $el = $(this);
                    var oh = $el.get(0).offsetHeight;

                    $el.css('top', -oh-10).data('noticeVisible', false);
                });
            }
        });
    }
})(jQuery);