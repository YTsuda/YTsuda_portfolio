
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
                this.menuElm = $('body.post-type-archive-work #main-space ul.menu');
                var scroll = $('.main-content-inner').jScrollPane({
                    "autoReinitialise" : true
                });
                this.scrollApi = scroll.data('jsp');

                this.menuElm.find('li a').bind('click', {'obj' : this}, this.menuClickListener);
                this.scrollByUrl();
            },
            /**
             * urlのアンカーから年号を取得、スクロールする。
             */
            scrollByUrl : function(){
                var year = null;
                if(!location.hash){
                    var date = new Date();
                    year = date.getYear() + 1900;
                }else{
                    year = location.hash.slice(1);
                }
                this.scrollToYear(year, false);
            },
            menuClickListener : function(event){
                var year = this.id.split('-').pop();
                event.data.obj.scrollToYear(year);
            },
            /**
             * 指定した制作年までスクロールする
             * メニューのボタンを指定状態にする
             *
             * @param int year      : スクロールさせたい制作年
             * @param mixed animate   : スクロール時のアニメーション
             */
            scrollToYear : function(year, animate){
                if(animate == undefined){
                    animate = 'fast';
                }
                this.scrollApi.scrollToElement($('#year-' + year), true, animate);
                this.menuElm.find('li a').removeClass('selected');
                this.menuElm.find('li a#year-menu-' + year).addClass('selected');
            }
        };

        new WorkScroll();
	});

