<?php


class CPriority_ext extends CPriority
{
    public static function ShowHeaderPhones($class = '')
    {
        global $arRegion;
        static $hphones_call;

        $iCalledID = ++$hphones_call;
        $arBackParametrs = self::GetBackParametrsValues(SITE_ID);
        $iCountPhones = ($arRegion ? count($arRegion['PHONES']) : $arBackParametrs['HEADER_PHONES']);
?>
        <? if ($arRegion): ?>
            <? $frame = new \Bitrix\Main\Page\FrameHelper('header-allphones-block' . $iCalledID); ?>
            <? $frame->begin(); ?>
        <? endif; ?>

        <? if ($iCountPhones): // count of phones
        ?>
            <?
            $phone = ($arRegion ? $arRegion['PHONES'][0] : $arBackParametrs['HEADER_PHONES_array_PHONE_VALUE_0']);
            $href = 'tel:' . str_replace(array(' ', '-', '–', '(', ')'), '', $phone);
            ?>
            <p class="label-text-item">Звоните</p>
            <div class="phone<?= ($iCountPhones > 1 ? ' with_dropdown' : '') ?>">
                <div>
                    <?php if (false): ?>
                        <?php if (false): ?>
                            <img src="/local/templates/aspro-priority/images/phone_icon.svg" class="phone_icon">
                        <?php else: ?>
                            <a href="#" class="phone_icon_wa"><img src="/local/templates/aspro-priority/images/phone_wa.svg" class="phone_icon"></a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a href="<?= $href ?>" class="phone_number phone_numbermsk <?= (date('G') >= 20 && date('i') >= 30) || (date('G') <= 8 && date('i') <= 30) ? '' : 'work' ?>"><?= $phone ?></a>
                </div>
                <? if ($iCountPhones > 1): // if more than one
                ?>
                    <div class="dropdown">
                        <div class="wrap">
                            <? for ($i = 1; $i < $iCountPhones; ++$i): ?>
                                <?
                                $phone = ($arRegion ? $arRegion['PHONES'][$i] : $arBackParametrs['HEADER_PHONES_array_PHONE_VALUE_' . $i]);
                                $href = 'tel:' . str_replace(array(' ', '-', '–', '(', ')'), '', $phone);
                                ?>
                                <div class="more_phone"><a href="<?= $href ?>"><?= $phone ?></a></div>
                            <? endfor; ?>
                        </div>
                    </div>
                <? endif; ?>
            </div>
        <? endif; ?>

        <? if ($arRegion): ?>
            <? $frame->end(); ?>
        <? endif; ?>
<?
    }
}
