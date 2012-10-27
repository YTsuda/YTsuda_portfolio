
	jQuery(document).ready(function($) {
        function WorkScroll(){
            this.initialize.apply(this, arguments);
        }
        WorkScroll.prototype = {
            menuElm : null,
            scrollApi : null,
            /**
             * setup scrollbar
             */
            initialize: function(){
                var scroll = $('.main-content-inner').jScrollPane({
                    "autoReinitialise" : true
                });
                this.scrollApi = scroll.data('jsp');
            }
        };

        new WorkScroll();
	});

