<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>

<? if ($arParams["AJAX_LOAD"] != "Y") { ?>
    <div class="maxwidth-theme">
    <div class="shadow-box">
    <div class="oprosnik_mainpage">
    <h2 class="style-h3">Выбор бокса по параметрам</h2>
    <div class="ajax_load">
    <input type="hidden" class="is_first_question" value="Y"/>
<? } ?>
<? if (count($arResult["STEPS"]) > 0) { ?>
    <div class="steps_block">
        <? foreach ($arResult["STEPS"] as $arStep) { ?>
            <div class="step step_<?= $arStep["ID"] ?> <?= ($arStep["CURRENT"] == "Y") ? 'active' : '' ?>"><?= $arStep["VALUE"] ?></div>
        <? } ?>
    </div>
<? } ?>
<? if (!empty($arResult["QUESTION"])) { ?>
    <div class="content_block">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="question_block">
                    <div class="question">
                        <?= $arResult["QUESTION"]["NAME"] ?>
                    </div>
                    <? if (!empty($arParams["OPROSNIK_DATA"])) { ?>
                        <?
                        $currQuestionData = array();
                        foreach ($arParams["OPROSNIK_DATA"] as $keySavedData => $arSavedData) {
                            if ($arResult["QUESTION"]["ID"] == $keySavedData) {
                                $currQuestionData = array_pop($arParams["OPROSNIK_DATA"]);
                                break;
                            }
                        }
                        $prevQuestionData = end($arParams["OPROSNIK_DATA"]);
                        ?>
                    <? } ?>
                    <? if (is_array($arResult["QUESTION"]["ANSWERS"])  && count($arResult["QUESTION"]["ANSWERS"]) > 0) { ?>
                        <div class="answers">
                            <? foreach ($arResult["QUESTION"]["ANSWERS"] as $arAnswer) { ?>
                                <div class="item <? if (is_array($currQuestionData["CURRENT_ANSWER_ID"]) && in_array($arAnswer["ID"], $currQuestionData["CURRENT_ANSWER_ID"])) { ?>selected<? } ?>">
                                    <div class="icon"><img width="38" height="42" class="lazy" data-src="<?= $arAnswer["ICON"]["src"] ?>"
                                                           alt="<?= $arAnswer["NAME"] ?>"/></div>
                                    <? if ($arAnswer["IS_NUMBER"] == "Y") { ?>
                                        <div class="name"><?= $arAnswer["NAME"] ?></div>
                                        <div class="number">
                                            <input onkeyup="this.value = checkNumType(this.value)"
                                                   type="number"
                                                   min="0"
                                                   value="<? if (is_array($currQuestionData["CURRENT_ANSWER_ID"]) && in_array($arAnswer["ID"], $currQuestionData["CURRENT_ANSWER_ID"])) {
                                                       $keyval = array_search($arAnswer["ID"], $currQuestionData["CURRENT_ANSWER_ID"]); ?><?= $currQuestionData["CURRENT_ANSWER_VALUE"][$keyval] ?><? } else { ?>0<? } ?>"
                                                   data-next-question-id="<?= $arAnswer["NEXT_QUESTION"] ?>"
                                                   data-current-question-id="<?= $arResult["QUESTION"]["ID"] ?>"
                                                   data-current-answer-id="<?= $arAnswer["ID"] ?>"/></div>
                                    <? } elseif ($arAnswer["IS_TEXT"] == "Y") { ?>
                                        <div class="text"><input type="text" placeholder="<?= $arAnswer["NAME"] ?>"
                                                                 value="<? if (in_array($arAnswer["ID"], $currQuestionData["CURRENT_ANSWER_ID"])) {
                                                                     $keyval = array_search($arAnswer["ID"], $currQuestionData["CURRENT_ANSWER_ID"]); ?><?= $currQuestionData["CURRENT_ANSWER_VALUE"][$keyval] ?><? } ?>"
                                                                 data-next-question-id="<?= $arAnswer["NEXT_QUESTION"] ?>"
                                                                 data-current-question-id="<?= $arResult["QUESTION"]["ID"] ?>"
                                                                 data-current-answer-id="<?= $arAnswer["ID"] ?>"/></div>
                                    <? } else { ?>
                                        <div class="name"><?= $arAnswer["NAME"] ?></div>
                                        <div class="value"><input type="checkbox"
                                                                  class="<?= ($arAnswer["IS_CHECKBOX_MULTI"] == "Y") ? 'multi' : '' ?>"
                                                                  value="Y"
                                                                  data-next-question-id="<?= $arAnswer["NEXT_QUESTION"] ?>"
                                                                  data-current-question-id="<?= $arResult["QUESTION"]["ID"] ?>"
                                                                  data-current-answer-id="<?= $arAnswer["ID"] ?>"/><label></label>
                                        </div>
                                    <? } ?>
                                </div>
                            <? } ?>
                        </div>

                        <div class="button">
                            <div class="row">
                                <? if (!empty($arParams["OPROSNIK_DATA"])) { ?>
                                    <div class="col-md-6 col-xs-6">
                                        <span class="btn btn-default btn-transparent PREV_STEP"
                                              data-prev-question-id="<?= $prevQuestionData["CURRENT_QUESTION_ID"] ?>">Назад</span>
                                    </div>
                                    <script>
                                        $(document).ready(function () {
                                            //делаем кликабельным кнопку предыдущего шага

                                            $('.oprosnik_mainpage .steps_block .step.active').prev("div").addClass('clickPrevStep');
                                            $('.oprosnik_mainpage .steps_block .step.active').prevAll("div").addClass('prevBordered');

                                        });
                                    </script>
                                <? } ?>
                                <div class="col-md-6 col-xs-6">
                                    <span class="btn btn-default disabled NEXT_STEP" data-next-question-id=""
                                          data-current-question-id="" data-current-answer-id=""
                                          data-current-answer-value="">Далее</span>
                                </div>
                            </div>
                        </div>
                    <? } ?>
                </div>
            </div>
            <? if (strlen($arResult["QUESTION"]["PICTURE"]["src"]) > 0) { ?>
                <div class="col-md-5 col-xs-12">
                    <div class="image">
                        <img class="lazy" data-src="<?= $arResult["QUESTION"]["PICTURE"]["src"] ?>"
                             alt="<?= $arResult["QUESTION"]["NAME"] ?>"/>
                    </div>
                </div>
            <? } ?>
        </div>
    </div>
<? } ?>

<? if ($arParams["AJAX_LOAD"] != "Y") { ?>
    </div>


    <? /*
    <div class="hidden_steps" style="display:none;">
        <? if (count($arResult["STEPS"]) > 0) { ?>
            <div class="steps_block">
                <? foreach ($arResult["STEPS"] as $arStep) { ?>
                    <div class="step step_<?= $arStep["ID"] ?> <?= ($arStep["ID"] == $arResult["STEPS"][count($arResult["STEPS"]) - 1]["ID"]) ? 'active' : '' ?>"><?= $arStep["VALUE"] ?></div>
                <? } ?>
            </div>
        <? } ?>
    </div>
    */ ?>
    <div class="form_block"></div>

    </div>
    </div>
    </div>
<? } ?>