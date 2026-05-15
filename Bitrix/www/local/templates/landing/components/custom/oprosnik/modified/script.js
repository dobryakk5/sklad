function checkNumType(val){
	val = Math.round(val);
	if(typeof(val) != "undefined") {
		$('.oprosnik_mainpage .question_block .button .NEXT_STEP').attr('data-next-question-id', $(this).find('input[type=number]').attr('data-next-question-id'));
		$('.oprosnik_mainpage .question_block .button .NEXT_STEP').attr('data-current-question-id', $(this).find('input[type=number]').attr('data-current-question-id'));

		currentAnswerId = [];
		currentAnswerValue = [];

		$('.oprosnik_mainpage .question_block .answers .item input[type=number]').each(function(index) {
			if(parseInt($(this).val()) > 0) {
				currentAnswerId.push($(this).attr('data-current-answer-id'));
				currentAnswerValue.push(val);
			}
		});

		if(parseInt(currentAnswerValue.length) > 0) {
			$('.oprosnik_mainpage .question_block .button .NEXT_STEP').attr('data-current-answer-id', currentAnswerId);
			$('.oprosnik_mainpage .question_block .button .NEXT_STEP').attr('data-current-answer-value', currentAnswerValue);

			$('.oprosnik_mainpage .question_block .button .NEXT_STEP').removeClass('disabled');
		} else {
			$('.oprosnik_mainpage .question_block .button .NEXT_STEP').addClass('disabled');
		}
	}
	return val;
}



$( document ).ready(function() {

    $('html').on('click', '.oprosnik_mainpage .question_block .answers .item', function() {
		var is_checkbox = $(this).find('input[type=checkbox]').attr('data-next-question-id');
		var is_number = $(this).find('input[type=number]').attr('data-next-question-id');
		var is_text = $(this).find('input[type=text]').attr('data-next-question-id');
		
		var currentAnswerId = [];
		var currentAnswerValue = [];		
	
		//если чекбоксы
		if(typeof(is_checkbox) != "undefined") {
			$('.oprosnik_mainpage .question_block .button .NEXT_STEP').attr('data-next-question-id', $(this).find('input[type=checkbox]').attr('data-next-question-id'));
			$('.oprosnik_mainpage .question_block .button .NEXT_STEP').attr('data-current-question-id', $(this).find('input[type=checkbox]').attr('data-current-question-id'));
			
			if(!$(this).find('input[type=checkbox]').hasClass('multi')) {
				$('.oprosnik_mainpage .question_block .answers .item input[type=checkbox]').prop('checked', false);
				//console.log('Сбросили чекбокс, если он не MULTI');
			}
			
			if($(this).find('input[type=checkbox]').prop('checked')) {
				$(this).find('input[type=checkbox]').prop('checked', false);
				//console.log('Сбросили чекбокс');
			} else {
				$(this).find('input[type=checkbox]').prop('checked', true);	
				//console.log('Установили чекбокс');
			}
			
			currentAnswerId = [];
			currentAnswerValue = [];

			$('.oprosnik_mainpage .question_block .answers .item input[type=checkbox]').each(function(index) {
				if($(this).prop('checked')) {
					currentAnswerId.push($(this).attr('data-current-answer-id'));
					currentAnswerValue.push($(this).val());
				}
			});	

			if(parseInt(currentAnswerValue.length) > 0) {			
				$('.oprosnik_mainpage .question_block .button .NEXT_STEP').attr('data-current-answer-id', currentAnswerId);
				$('.oprosnik_mainpage .question_block .button .NEXT_STEP').attr('data-current-answer-value', currentAnswerValue);	
				
				$('.oprosnik_mainpage .question_block .button .NEXT_STEP').removeClass('disabled');				
			} else {
				$('.oprosnik_mainpage .question_block .button .NEXT_STEP').addClass('disabled');
			}	
		}
		
		//если количество
		if(typeof(is_number) != "undefined") {
			$('.oprosnik_mainpage .question_block .button .NEXT_STEP').attr('data-next-question-id', $(this).find('input[type=number]').attr('data-next-question-id'));
			$('.oprosnik_mainpage .question_block .button .NEXT_STEP').attr('data-current-question-id', $(this).find('input[type=number]').attr('data-current-question-id'));
			
			currentAnswerId = [];
			currentAnswerValue = [];			
			
			$('.oprosnik_mainpage .question_block .answers .item input[type=number]').each(function(index) {
				if(parseInt($(this).val()) > 0) {
					currentAnswerId.push($(this).attr('data-current-answer-id'));
					currentAnswerValue.push($(this).val());
				}
			});

			if(parseInt(currentAnswerValue.length) > 0) {			
				$('.oprosnik_mainpage .question_block .button .NEXT_STEP').attr('data-current-answer-id', currentAnswerId);
				$('.oprosnik_mainpage .question_block .button .NEXT_STEP').attr('data-current-answer-value', currentAnswerValue);	
				
				$('.oprosnik_mainpage .question_block .button .NEXT_STEP').removeClass('disabled');				
			} else {
				$('.oprosnik_mainpage .question_block .button .NEXT_STEP').addClass('disabled');
			}
		}
		
		//если текст
		if(typeof(is_text) != "undefined") {			
			$('.oprosnik_mainpage .question_block .button .NEXT_STEP').attr('data-next-question-id', $(this).find('input[type=text]').attr('data-next-question-id'));
			$('.oprosnik_mainpage .question_block .button .NEXT_STEP').attr('data-current-question-id', $(this).find('input[type=text]').attr('data-current-question-id'));
			$('.oprosnik_mainpage .question_block .button .NEXT_STEP').attr('data-current-answer-id', $(this).find('input[type=text]').attr('data-current-answer-id'));
			$('.oprosnik_mainpage .question_block .button .NEXT_STEP').attr('data-current-answer-value', $(this).find('input[type=text]').val());
			
			if($(this).find('input[type=text]').val() != "") {				
				$('.oprosnik_mainpage .question_block .button .NEXT_STEP').removeClass('disabled');
			}
		}
	})
	
	$('html').on('keyup', '.oprosnik_mainpage .question_block .answers .item input[type=text]', function() {
		$('.oprosnik_mainpage .question_block .button .NEXT_STEP').attr('data-current-answer-value', $(this).val());
		if($(this).val() != "") {
			$('.oprosnik_mainpage .question_block .button .NEXT_STEP').removeClass('disabled');
		} else {
			$('.oprosnik_mainpage .question_block .button .NEXT_STEP').addClass('disabled');
		}
	});	
	
    $('html').on('click', '.oprosnik_mainpage .question_block .button .NEXT_STEP', function() {
		var btn = $(this);
		var PREV_QUESTION_ID = $('.oprosnik_mainpage .question_block .button .PREV_STEP').attr('data-prev-question-id');
		var NEXT_QUESTION_ID = $(this).attr('data-next-question-id');
		var CURRENT_QUESTION_ID = $(this).attr('data-current-question-id');
		var CURRENT_ANSWER_ID = $(this).attr('data-current-answer-id');
		var CURRENT_ANSWER_VALUE = $(this).attr('data-current-answer-value');
		var IS_FIRST_QUESTION = $('.oprosnik_mainpage .is_first_question').val();
		
        if($(btn).attr("data-ctrl") != "Y") {
            $(btn).attr("data-ctrl", "Y");
			$.post("/bitrix/components/custom/oprosnik/templates/.default/ajax.php", {ACTION:"NEXT_STEP", "PREV_QUESTION_ID":PREV_QUESTION_ID, "NEXT_QUESTION_ID":NEXT_QUESTION_ID, "CURRENT_QUESTION_ID":CURRENT_QUESTION_ID, "CURRENT_ANSWER_ID":CURRENT_ANSWER_ID, "CURRENT_ANSWER_VALUE":CURRENT_ANSWER_VALUE, "IS_FIRST_QUESTION":IS_FIRST_QUESTION}, function(data){
                $(btn).attr("data-ctrl", "N");
                if(data.indexOf("ERROR_") != -1){
                    //$(btn).before('<div class="errorBlock">'+data.replace("ERROR_", "")+'</div>');
                } else {
					if(data.indexOf("SHOW_FORM_") != -1){
						$('.oprosnik_mainpage .ajax_load').empty();
						$('.oprosnik_mainpage .form_block .oprosnik_form_from form #oprosnik_result').text(data.replace("SHOW_FORM_", ""));
						$('.oprosnik_mainpage .hidden_steps').show();
						$('.oprosnik_mainpage .form_block .oprosnik_form_from').show();
					} else {
						$('.oprosnik_mainpage .ajax_load').empty().append(data);
					}
					/************ LazyLoad ************/
					var lazyLoadInstance = new LazyLoad({
						elements_selector: ".lazy"
					});
					lazyLoadInstance.update();
					/***********************/
                }
            });
        }
	})
    $('html').on('click', '.oprosnik_mainpage .question_block .button .PREV_STEP', function() {
		var btn = $(this);
		var PREV_QUESTION_ID = $(this).attr('data-prev-question-id');
		var IS_FIRST_QUESTION = $('.oprosnik_mainpage .is_first_question').val();

        if($(btn).attr("data-ctrl") != "Y") {
            $(btn).attr("data-ctrl", "Y");
            $.post("/bitrix/components/custom/oprosnik/templates/.default/ajax.php", {ACTION:"PREV_STEP", "PREV_QUESTION_ID":PREV_QUESTION_ID, "IS_FIRST_QUESTION":IS_FIRST_QUESTION}, function(data){
                $(btn).attr("data-ctrl", "N");
                if(data.indexOf("ERROR_") != -1){
                    //$(btn).before('<div class="errorBlock">'+data.replace("ERROR_", "")+'</div>');					
                } else {
					$('.oprosnik_mainpage .ajax_load').empty().append(data);  

					$('.oprosnik_mainpage .question_block .answers .item.selected').each(function(index) {
						$(this).trigger('click');
					});
					$('.oprosnik_mainpage .question_block .answers .item').removeClass('selected');
					
					/************ LazyLoad ************/
					var lazyLoadInstance = new LazyLoad({
						elements_selector: ".lazy"
					});
					lazyLoadInstance.update();
					/***********************/					
                }
            });    
        }  		
	})

	//клик по предыдущему шагу
	$('html').on('click', '.oprosnik_mainpage .steps_block .step.clickPrevStep', function() {
		$('.oprosnik_mainpage .question_block .button .PREV_STEP').trigger('click');
	})
});
