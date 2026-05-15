<?global $USER;?>
<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("form-iblock".$arParams["IBLOCK_ID"]);?>
<?if($USER->IsAuthorized()):?>
    <?
    $dbRes = CUser::GetList(($by = "id"), ($order = "asc"), array("ID" => $USER->GetID()), array("FIELDS" => array("ID", "PERSONAL_PHONE")));
    $arUser = $dbRes->Fetch();
    ?>
    <script type="text/javascript">
    $(document).ready(function() {
        try {
			//NAME
			if($('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=CLIENT_NAME]').length > 0) {
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=CLIENT_NAME]').val('<?=$USER->GetFullName()?>');
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=CLIENT_NAME]').parents('.form-group').addClass('input-filed');
			}
			if($('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=FIO]').length > 0) {
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=FIO]').val('<?=$USER->GetFullName()?>');
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=FIO]').parents('.form-group').addClass('input-filed');
			}	
			if($('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=NAME]').length > 0) {
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=NAME]').val('<?=$USER->GetFullName()?>');
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=NAME]').parents('.form-group').addClass('input-filed');
			}
			//PHONE
			if($('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=PHONE]').length > 0) {
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=PHONE]').val('<?=$arUser["PERSONAL_PHONE"]?>');
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=PHONE]').parents('.form-group').addClass('input-filed');
			}
			//EMAIL
			if($('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=EMAIL]').length > 0) {
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=EMAIL]').val('<?=$USER->GetEmail()?>');
				$('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=EMAIL]').parents('.form-group').addClass('input-filed');
			}	
			
			/*
            $('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=CLIENT_NAME], .form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=FIO], .form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=NAME]').val('<?=$USER->GetFullName()?>');
            $('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=PHONE]').val('<?=$arUser['PERSONAL_PHONE']?>');
            $('.form.iblock_id_<?=$arParams["IBLOCK_ID"]?> input[name=EMAIL]').val('<?=$USER->GetEmail()?>');
			*/
        }
        catch(e){
        }
    });
    </script>
<?endif;?>
<script>
	$(document).ready(function() {
		$('form[name="<?=$arResult["IBLOCK_CODE"]?>"]').attr("action", "/ajax/form.php?id=<?=$arParams["IBLOCK_ID"]?>&template=popup_mebel");
		$('form[name="<?=$arResult["IBLOCK_CODE"]?>"]').addClass("webform");
		
		/*$('#POPUP_SKLAD').select3({
			theme: 'stock-select'
		});*/
	})
	$(function() {
        let stars = $('.rating_wrap .star');
        let starsCurrent = $('.stars_current');
        let message = $('.rating_message');
        stars.on('click', function () {
            let currentStar = $(this);
            if (currentStar.data('current_width') === 20) {
                starsCurrent.css("width",currentStar.data('current_width') + '%');
                starsCurrent.attr('data-rating',currentStar.data('rating_value'));
                starsCurrent.data('rating',currentStar.data('rating_value'));
                message.text(currentStar.data('message'));
            } else if (currentStar.data('current_width') === 40) {
                starsCurrent.css("width",currentStar.data('current_width') + '%');
                starsCurrent.attr('data-rating',currentStar.data('rating_value'));
                starsCurrent.data('rating',currentStar.data('rating_value'));
                message.text(currentStar.data('message'));
            } else if (currentStar.data('current_width') === 60) {
                starsCurrent.css("width",currentStar.data('current_width') + '%');
                starsCurrent.attr('data-rating',currentStar.data('rating_value'));
                starsCurrent.data('rating',currentStar.data('rating_value'));
                message.text(currentStar.data('message'));
            } else if (currentStar.data('current_width') === 80) {
                starsCurrent.css("width",currentStar.data('current_width') + '%');
                starsCurrent.attr('data-rating',currentStar.data('rating_value'));
                starsCurrent.data('rating',currentStar.data('rating_value'));
                message.text(currentStar.data('message'));
            } else if (currentStar.data('current_width') === 100) {
                starsCurrent.css("width",currentStar.data('current_width') + '%');
                starsCurrent.attr('data-rating',currentStar.data('rating_value'));
                starsCurrent.data('rating',currentStar.data('rating_value'));
                message.text(currentStar.data('message'));
            }
        });
        stars.on('mouseover', function () {
            let currentStar = $(this);
            if (currentStar.data('current_width') === 20) {
                starsCurrent.css("width",currentStar.data('current_width') + '%');
                message.text(currentStar.data('message'));
            } else if (currentStar.data('current_width') === 40) {
                starsCurrent.css("width",currentStar.data('current_width') + '%');
                message.text(currentStar.data('message'));
            } else if (currentStar.data('current_width') === 60) {
                starsCurrent.css("width",currentStar.data('current_width') + '%');
                message.text(currentStar.data('message'));
            } else if (currentStar.data('current_width') === 80) {
                starsCurrent.css("width",currentStar.data('current_width') + '%');
                message.text(currentStar.data('message'));
            } else if (currentStar.data('current_width') === 100) {
                starsCurrent.css("width",currentStar.data('current_width') + '%');
                message.text(currentStar.data('message'));
            }
        });

        stars.on('mouseout', function () {
            if (starsCurrent.data('rating') === 0) {
                starsCurrent.css("width",0);
                message.text('Без оценки');
            }else {
                let width = stars.filter('[data-rating_value='+starsCurrent.data('rating')+']').data('current_width');
                let messageVal = stars.filter('[data-rating_value='+starsCurrent.data('rating')+']').data('message');
                starsCurrent.css("width",width+'%');
                message.text(messageVal);
            }
        });
		$(document).on('click', '.form .rating .star', function(){
			var $this = $(this),
				currentStarWidth = $this.data('current_width'),
				ratingValue = $this.data('rating_value'),
				ratingMessage = $this.data('message');

			$this.closest('.rating').find('.stars_current').data('rating', currentStarWidth);
			if($this.closest('.rating_wrap').find('input[name=RATING]').length){
				$this.closest('.rating_wrap').find('input[name=RATING]').val(ratingValue);
			}
			else{
				$this.closest('.rating_wrap').find('input[data-sid=RATING]').val(ratingValue);
			}
			$this.closest('.rating_wrap').find('.rating_message').data('message', ratingMessage);
		});
    });

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



</script>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("form-iblock".$arParams["IBLOCK_ID"], "");?> 