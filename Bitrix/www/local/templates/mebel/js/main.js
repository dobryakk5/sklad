let isMobile = {
    Android: function () {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function () {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function () {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function () {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function () {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function () {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};
let body = document.querySelector('body');
if (isMobile.any()) {
    body.classList.add('touch');
    let arrow = document.querySelectorAll('.arrow');
    for (let i = 0; i < arrow.length; i++) {
        let thisLink = arrow[i].previousElementSibling;
        let subMenu = arrow[i].nextElementSibling;
        let thisArrow = arrow[i];

        thisLink.classList.add('parent');
        arrow[i].addEventListener('click', function () {
            subMenu.classList.toggle('open');
            thisArrow.classList.toggle('active');
        });
    }
} else {
    body.classList.add('mouse');
}

(function () {
    'use strict';
    window.onload = function () {
        let options =[
            {label:'0 М2',group:'small',value:1},
            {label:'',group:'small',value:2},
            {label:'2 М2',group:'small',value:3},
            {label:'',group:'small',value:4},
            {label:'',group:'small',value:5},
            {label:'5 М2',group:'semiSmall',value:6},
            {label:'',group:'semiSmall',value:7},
            {label:'',group:'semiSmall',value:8},
            {label:'',group:'semiSmall',value:9},
            {label:'8 М2',group:'medium',value:10},
            {label:'',group:'medium',value:11},
            {label:'',group:'medium',value:12},
            {label:'',group:'medium',value:13},
            {label:'11 М2',group:'semiBig',value:14},
            {label:'',group:'semiBig',value:15},
            {label:'',group:'semiBig',value:16},
            {label:'',group:'semiBig',value:17},
            {label:'15 М2',group:'big',value:18},
            {label:'',group:'big',value:19},
            {label:'',group:'big',value:20},
            {label:'',group:'big',value:21},
            {label:'30 М2',group:'big',value:22},
        ];
        let slider = new rSlider({
            target: '#sampleSlider',
            values: options,
            range: false,
            set: [{label:'5 М2',group:'small'}],
            tooltip: false,
            onChange: function (vals) {
                $('.flat-container .rs-flat .item[data-button='+vals.group+']').addClass("active").siblings().removeClass('active');
                $('.storage-wrapper .storage-list[data-block='+vals.group+']').show().siblings().hide();
            }
        });
        $('.flat-container .rs-flat .item').each(function () {
            let dataButton = $(this).data('button');
            let buttonWidth= 0;
            options.filter(function (item,index) {
                if(item.group === dataButton){
                    buttonWidth++;
                }
            })
            let width = $('.rs-container .rs-scale span').width();
            $(this).css('width', width * buttonWidth);
            $('.flat-container .rs-flat .item[data-button="small"]').css('width', width * (buttonWidth + 0.2));
            $('.flat-container .rs-flat .item[data-button="big"]').css('width', width * (buttonWidth - 1));
        });
    };
})();

(function ($) {
    $('#localAddressSelect').select3({
        theme: 'address-select'
    });
    $('#stockSelect').select3({
        theme: 'stock-select'
    });

    $('.accordeon-socials .accorlink').on('click', function() {
        if ($(this).hasClass("clicked")) {
            $(this).removeClass("clicked");
        } else {
            $(this).addClass("clicked");
        }
        let panel = $('.accordeon-socials .panel');
        if(panel.hasClass("open")) {
            panel.hide(300).removeClass("open");
        } else {
            panel.show(300).addClass("open");
        }
    });

    //img-svg
    $(document).ready(function () {
        $('.icon-svg img').each(function () {
            let $img = $(this);
            let imgClass = $img.attr('class');
            let imgURL = $img.attr('src');
            if (imgURL) {
                $.get(imgURL, function (data) {
                    let $svg = $(data).find('svg');
                    if (typeof imgClass !== 'undefined') {
                        $svg = $svg.attr('class', imgClass + ' replaced-svg');
                    }
                    $svg = $svg.removeAttr('xmlns:a').attr('style', function (i, style) {
                        return style && style.replace(/enable-background[^;]+;?/g, '');
                    });
                    if (!$svg.attr('viewBox') && $svg.attr('width') && $svg.attr('height')) {
                        $svg.attr('viewBox', '0 0 ' + $svg.attr('width') + ' ' + $svg.attr('height'));
                    }
                    if ($svg.attr('viewBox') && !$svg.attr('width') && !$svg.attr('height')) {
                        let width = $svg.attr("viewBox").split(' ')[2];
                        let height = $svg.attr("viewBox").split(' ')[3];
                        $svg.attr('width', width);
                        $svg.attr('height', height);
                    }
                    $img.replaceWith($svg);
                }, 'xml');
            }
        });
    });

    $(document).ready(function () {

        $('.header-burger').click(function () {
            $('.header-burger, .mobile-menu').toggleClass('active');
            $('body').toggleClass('lock');
        });
        $('.mobile-menu .btn-close').click(function () {
            $('.header-burger').trigger('click');
        });

        $('.slider-promotionsDiscounts').slick({
            arrows: true,
            dots: true,
            adaptiveHeight: false,
            slidesToShow: 3,
            slidesToScroll: 1,
            speed: 500,
            easing: 'linear',
            infinite: true,
            initialSlide: 0,
            autoplay: false,
            autoplaySpeed: 3000,
            pauseOnHover: true,
            pauseOnFocus: true,
            pauseOnDotsHover: true,
            draggable: true,
            swipe: true,
            touchThreshold: 5,
            touchMove: true,
            waitForAnimate: true,
            centerMode: false,
            variableWidth: false,
            rows: 1,
            slidesPerRow: 1,
            vertical: false,
            verticalSwiping: false,
            responsive: [
                {
                    breakpoint: 1626,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 992,
                    settings: {
                        slidesToShow: 1,
                    }
                },
            ],
        });
        $('.slider-clientsReviews').slick({
            arrows: true,
            dots: true,
            adaptiveHeight: false,
            slidesToShow: 3,
            slidesToScroll: 1,
            speed: 500,
            easing: 'linear',
            infinite: true,
            initialSlide: 0,
            autoplay: false,
            autoplaySpeed: 3000,
            pauseOnHover: true,
            pauseOnFocus: true,
            pauseOnDotsHover: true,
            draggable: true,
            swipe: true,
            touchThreshold: 5,
            touchMove: true,
            waitForAnimate: true,
            centerMode: false,
            variableWidth: false,
            rows: 1,
            slidesPerRow: 1,
            responsive: [
                {
                    breakpoint: 1200,
                    settings: {
                        slidesToShow: 2,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                    }
                },
            ],
        });
        $('.slider-about').slick({
            arrows: true,
            dots: false,
            adaptiveHeight: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            speed: 500,
            easing: 'linear',
            infinite: true,
            initialSlide: 0,
            autoplay: false,
            pauseOnHover: true,
            pauseOnFocus: true,
            pauseOnDotsHover: true,
            draggable: true,
            swipe: true,
            touchThreshold: 5,
            touchMove: true,
            waitForAnimate: true,
            variableWidth: false,
            rows: 1,
            slidesPerRow: 1,
        });
    });

    function setupForWidth(mql) {
        if (mql.matches) {

        }
    }

    $(function () {
        let mql = window.matchMedia('screen and (min-width: 768px)');
        mql.addListener(setupForWidth);
        setupForWidth(mql);
    });

    $(document).ready(function () {
        $('.storage-wrapper .storage-list .storage-item .desc').each(function () {
            if ($(this).text().length>30) {
                $(this).css('font-size', '14px');
            }
        });
        $('.storage-wrapper .storage-list .storage-item div').each(function () {
            if ($(this).hasClass('stock')) {
                $(this).parent().parent().parent().addClass('stock-item');
            }
        });
        $('.accordeon-label').each(function () {
            if ($(this).hasClass('opened')) {
                $('.accordeon-content', this).show();
            }
        });
    });

    $('.accordeon-label').on('click', function () {
        $(this).toggleClass('opened');
        $('.accordeon-content', this).toggle(300);
        if ($(this).hasClass('opened')) {
            $(this).siblings().removeClass('opened');
            $(this).siblings().children('.accordeon-content').hide(300);
        }
    });

    $(function() {
        $(".underfooter .little-one").click(function() {
            if (location.pathname.replace(/^\//,'') === this.pathname.replace(/^\//,'')
                && location.hostname === this.hostname) {

                let target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: target.offset().top - 0
                    }, 800);
                    return false;
                }
            }
        });
    });

    let observer = new MutationObserver(function (mutations) {
		mutations.forEach(function(mutation) {
			mutation.addedNodes.forEach(function(node) {
				if (node.id == 'modalReview') { 
					let element = node; 
					$(document).on('click', '.field__file-wrapper > div.field__file-button, .field__file-wrapper > div.field__file-fake', function () {
						let parents = $(this).parents();
						$('.input-files',parents[2]).trigger('click');
					})
					$('.input-files').on('change',function (e) {
						let label = this.nextElementSibling,
							labelVal = label.querySelector('.field__file-fake').innerText;
						let countFiles = '';
						if (this.files && this.files.length >= 1)
							countFiles = this.files.length;

						if (countFiles)
							for(let i = 0; i < $(this).get(0).files.length; ++i) {
								label.querySelector('.field__file-fake').innerText = $(this).get(0).files[i].name;
							}
						else
							label.querySelector('.field__file-fake').innerText = labelVal;
					});
				}
			})
		})
    });

    //let target = $(document).find('#modalReview'); 
    observer.observe(document,{
        childList: true,
        subtree: true,
    });
    
   $('a[href="#modalCall"]').on("click", function(){
        let url = '/ajax/form.php';
        $.ajax({
            url: url,
            type: 'POST',
            data: {id: 21, template: 'popup_mebel'},
            success: function(html){
                $("#modalCall").html(html);
            }
        })
    })
    $('a[href="#modalReview"]').on("click", function(){
        let url = '/ajax/form.php';
        $.ajax({
            url: url,
            type: 'POST',
            data: {id: 30, template: 'popup_mebel'},
            success: function(html){
                $("#modalReview").html(html);
            }
        })
    })
    $('a[href="#modalSendMail"]').on("click", function(){
        let url = '/ajax/form.php';
        $.ajax({
            url: url,
            type: 'POST',
            data: {id: 20, template: 'popup_mebel'},
            success: function(html){
                $("#modalSendMail").html(html);
            }
        })
    })
    
    $.extend( $.validator.messages, {
		required: BX.message('JS_REQUIRED'),
		email: BX.message('JS_FORMAT'),
		equalTo: BX.message('JS_PASSWORD_COPY'),
		minlength: BX.message('JS_PASSWORD_LENGTH'),
		remote: BX.message('JS_ERROR')
	});

	$.validator.addMethod(
		'regexp', function( value, element, regexp ){
			var re = new RegExp( regexp );
			return this.optional( element ) || re.test( value );
		},
		BX.message('JS_FORMAT')
	);

	$.validator.addMethod(
		'filesize', function( value, element, param ){
			return this.optional( element ) || ( element.files[0].size <= param )
		},
		BX.message('JS_FILE_SIZE')
	);

	$.validator.addMethod(
		'date', function( value, element, param ) {
			var status = false;
			if(!value || value.length <= 0){
				status = true;
			}
			else{
				var re = new RegExp('^([0-9]{2})(.)([0-9]{2})(.)([0-9]{4})$');
				var matches = re.exec(value);
				if(matches){
					var composedDate = new Date(matches[5], (matches[3] - 1), matches[1]);
					status = ((composedDate.getMonth() == (matches[3] - 1)) && (composedDate.getDate() == matches[1]) && (composedDate.getFullYear() == matches[5]));
				}
			}
			return status;
		}, BX.message('JS_DATE')
	);

	$.validator.addMethod(
		'datetime', function( value, element, param ) {
			var status = false;
			if(!value || value.length <= 0){
				status = true;
			}
			else{
				var re = new RegExp('^([0-9]{2})(.)([0-9]{2})(.)([0-9]{4}) ([0-9]{1,2}):([0-9]{1,2})$');
				var matches = re.exec(value);
				if(matches){
					var composedDate = new Date(matches[5], (matches[3] - 1), matches[1], matches[6], matches[7]);
					status = ((composedDate.getMonth() == (matches[3] - 1)) && (composedDate.getDate() == matches[1]) && (composedDate.getFullYear() == matches[5]) && (composedDate.getHours() == matches[6]) && (composedDate.getMinutes() == matches[7]));
				}
			}
			return status;
		}, BX.message('JS_DATETIME')
	);

	$.validator.addMethod(
		'extension', function(value, element, param){
			param = typeof param === 'string' ? param.replace(/,/g, '|') : 'png|jpe?g|gif';
			return this.optional(element) || value.match(new RegExp('.(' + param + ')$', 'i'));
		}, BX.message('JS_FILE_EXT')
	);

	$.validator.addMethod(
		'captcha', function( value, element, params ){
			return $.validator.methods.remote.call(this, value, element,{
				url: arPriorityOptions['SITE_DIR'] + 'ajax/check-captcha.php',
				type: 'post',
				data:{
					captcha_word: value,
					captcha_sid: function(){
						return $(element).closest('form').find('input[name="captcha_sid"]').val();
					}
				}
			});
		},
		BX.message('JS_ERROR')
    );

    $.validator.addMethod(
		'recaptcha', function(value, element, param){
			var id = $(element).closest('form').find('.g-recaptcha').attr('data-widgetid');
			if(typeof id !== 'undefined'){
				return grecaptcha.getResponse(id) != '';
			}
			else{
				return true;
			}
		}, BX.message('JS_RECAPTCHA_ERROR')
	);

	/*reload captcha*/
	$('body').on( 'click', '.refresh', function(e){
		e.preventDefault();
		$.ajax({
			url: arPriorityOptions['SITE_DIR'] + 'ajax/captcha.php'
		}).done(function(text){
			if($('.captcha_sid').length)
			{
				$('.captcha_sid').val(text);
				$('.captcha_img').attr('src', '/bitrix/tools/captcha.php?captcha_sid=' + text);
			}
		});
	});

	$.validator.addClassRules({
		'phone':{
			regexp: arPriorityOptions['THEME']['VALIDATE_PHONE_MASK']
		},
		'confirm_password':{
			equalTo: 'input.password',
			minlength: 6
		},
		'password':{
			minlength: 6
		},
		'inputfile':{
			extension: arPriorityOptions['THEME']['VALIDATE_FILE_EXT'],
			filesize: 5000000
		},
		'datetime':{
			datetime: ''
		},
		'captcha':{
			captcha: ''
		},
		'recaptcha':{
			recaptcha: ''
		}
	});

	$.validator.setDefaults({
	   highlight: function( element ){
			$(element).parent().addClass('error');
		},
		unhighlight: function( element ){
			$(element).parent().removeClass('error');
		}
	});
})(jQuery);

window.addGroup = function (element) {
    let target = $(element).data('target');
    if (target) {
        let day = $(element).data('day');
        let groups;
        let newGroupText;
        if (day) {
            groups = $('.' + target + 's[data-day=' + day + ']').toArray();
        } else {
            groups = $('.' + target + 's');
        }
        let limit = $(element).data('limit');
        let id = $(groups[groups.length - 1]).attr('id').split('-', 2);
        let index = parseInt(id[1]),
            newIndex = index + 1
        let lastGroup = $(groups[groups.length - 1]);
        let newGroup = lastGroup.clone(true).attr('id', target + '-' + newIndex);
        if (day) {
            newGroupText = newGroup.html().replaceAll(target + 's[' + day + '][' + index + ']', target + 's[' + day + '][' + newIndex + ']');
        } else {
            newGroupText = newGroup.html().replaceAll(target + 's[' + index + ']', target + 's[' + newIndex + ']');
        }
        if (limit) {
            $(element).attr('onclick', 'remove(this)').data('id', newIndex).addClass("opened");
        }
        if (groups.length > 0) {
            $('.remove-element', groups[0]).css('display', 'flex');
        }
        newGroup.html(newGroupText).hide();
        lastGroup.after(newGroup);
        setTimeout(
            function () {
                newGroup.show(200);
            },
            400)
        $('input, select, textarea', newGroup).val('');
        $('.field__file-fake', newGroup).text('Фаил не выбран');
        $('.remove-element', newGroup).attr('data-id', newIndex).css('display', 'flex');
    }
}
window.remove = function (button) {
    let $self = $(button),
        day = $(button).data('day'),
        target = $self.data('target'),
        limit = $(button).data('limit'),
        index = $self.data('id'),
        groupsLength = $('.' + target + (day ? 's[data-day=' + day + ']' : 's')).length,
        parent = $(button).closest('#' + target + '-' + index);
    if (limit) {
        $(button).attr('onclick', 'addGroup(this)').data('id', "").removeClass("opened");
    }

    if (target) {
        if (groupsLength > 1) {
            let $target = $('#' + target + '-' + index + (day ? '[data-day=' + day + ']' : ''));
            $target.hide('fast', function () {
                $target.remove();
                let groups = $('.' + target + (day ? 's[data-day=' + day + ']' : 's')).toArray()
                let parentGroup = $(groups[0]).parents();
                if (groups.length === 1) {
                    $('.remove-element', groups[0]).removeAttr('style')
                    $('.add-button', parentGroup[1]).removeAttr('style')
                }
            });
        } else {
            $('input, select, textarea', parent).val('');
            $('.field__file-fake', newGroup).text('Фаил не выбран');
        }
    }
    return false;
};

BX.addCustomEvent('onAjaxSuccess', function(){
    if ($('.phone-number').length > 0) {
        $(".phone-number").mask("+7(555) 555-5555");
    }
});
BX.addCustomEvent('onSubmitForm', function(eventdata){
	try{
		if(!window.renderRecaptchaById || !window.asproRecaptcha || !window.asproRecaptcha.key)
		{
			eventdata.form.submit();
			return true;
		}
		if(window.asproRecaptcha.params.recaptchaSize == 'invisible' && typeof grecaptcha != 'undefined')
		{
			if($(eventdata.form).find('.g-recaptcha-response').val())
			{
				eventdata.form.submit();
			}
			else
			{
				grecaptcha.execute($(eventdata.form).find('.g-recaptcha').data('widgetid'));
				return false;
			}
		}
		else
		{
			eventdata.form.submit();
		}

		return true;
	}catch (e){
		console.error(e);
		return true;
	}
})
