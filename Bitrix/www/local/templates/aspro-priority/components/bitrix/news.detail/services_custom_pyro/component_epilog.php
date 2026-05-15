<?
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$slider1 = '';
if(count($templateData['SLIDER_1'])) {
    $slider1 .= '<div class="steps_col steps_col--left">';
    $slider1 .= '<div class="steps_slider-img-1 swiper-container" id="steps_slider-img-1">';
    $slider1 .= '<div class="swiper-wrapper">';
				foreach($templateData['SLIDER_1'] as $i => $arPhoto) {
                    $slider1 .= '<div class="steps_img-wrap steps_img-1 swiper-slide">';
						$slider1 .= '<img src="' . $arPhoto['PREVIEW']['src'] .'" title="' . $arPhoto['TITLE'] . '" alt="' . $arPhoto['ALT'] . '">';
					$slider1 .= '</div>';
				}
    $slider1 .= '</div>';
    $slider1 .= '<div class="steps__button-prev-wrap">';
    $slider1 .= '<div class="steps__button-prev"></div>';
    $slider1 .= '</div>';
    $slider1 .= '<div class="steps__button-next-wrap">';
    $slider1 .= '<div class="steps__button-next"></div>';
    $slider1 .= '</div>';
    $slider1 .= '<div class="steps_slider-img-1_dots steps_slider-img-dots"></div>';
    $slider1 .= '</div>';
    $slider1 .= '</div>';
}

$slider2 = '';
if(count($templateData['SLIDER_2'])) {
    $slider2 .= '<div class="steps_col steps_col--right">';
    $slider2 .= '<div class="steps_slider-img-2 swiper-container" id="steps_slider-img-2">';
    $slider2 .= '<div class="swiper-wrapper">';
    foreach($templateData['SLIDER_2'] as $i => $arPhoto) {
        $slider2 .= '<div class="steps_img-wrap steps_img-2 swiper-slide">';
        $slider2 .= '<img src="' . $arPhoto['PREVIEW']['src'] .'" title="' . $arPhoto['TITLE'] . '" alt="' . $arPhoto['ALT'] . '">';
        $slider2 .= '</div>';
    }
    $slider2 .= '</div>';
    $slider2 .= '<div class="steps__button-prev-wrap">';
    $slider2 .= '<div class="steps__button-prev"></div>';
    $slider2 .= '</div>';
    $slider2 .= '<div class="steps__button-next-wrap">';
    $slider2 .= '<div class="steps__button-next"></div>';
    $slider2 .= '</div>';
    $slider2 .= '<div class="steps_slider-img-2_dots steps_slider-img-dots"></div>';
    $slider2 .= '</div>';
    $slider2 .= '</div>';
}

$html = $templateData['DETAIL_TEXT'];
$html = str_replace('_#SLIDER_1#_', $slider1, $html);
$html = str_replace('_#SLIDER_2#_', $slider2, $html);
?>

<?= $html ?>