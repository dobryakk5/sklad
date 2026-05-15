$(function () {

    $('.tabs_slider  .item-wrap .title').sliceHeight({allElements: true});
    $('.item-block.active .catalog.item-views.table .item .cont').sliceHeight({allElements: true});
    $('.item-block.active .catalog.item-views.table .item .slice_price').sliceHeight({allElements: true});
    $('.item-block.active .catalog.item-views.table .item .image>.wrap').sliceHeight({lineheight:-3, allElements: true})
    setTimeout(function(){
        $('.item-block.active .catalog.item-views.table .item').sliceHeight({classNull: '.footer-button', allElements: true});

    }, 1000);
})