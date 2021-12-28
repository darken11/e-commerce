$(function() {


    'user strict';

    /*
     ** Dashboard
     */

    $('.toggle-info').click(function() {
        $(this).toggleClass('selected').parent().next('.card-body').fadeToggle(100);
        if ($(this).hasClass('selected')) {
            $(this).html('<i class="fa fa-plus fa-lg"></i>');
        } else {
            $(this).html('<i class="fa fa-minus fa-lg"></i>');
        }
    });

    /*
     ** Trigger The selectboxit
     ** Calls the selectBoxIt method on your HTML select box.
     */
    $("select").selectBoxIt({
        autoWidth: false
    });


    /*
     **
     ** hide Placeholder on Form focus
     */

    $('[placeholder]').focus(function() {

        $(this).attr('data-text', $(this).attr('placeholder'));

        $(this).attr('placeholder', '');

    }).blur(function() {

        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    /*
     ** Add Asterisk on Required field
     */

    $('input').each(function() {

        if ($(this).attr('required') == 'required') {

            $(this).after('<span class="star">*</span>');

        }
    });
    /*
     ** convert password field on text field on haver
     */
    var passField = $('.password');
    $('.show-pass').hover(function() {
        $(passField.attr('type', 'text'));
    }, function() {
        $(passField.attr('type', 'password'));
    });

    /*
     ** confirmation message on delete
     */

    $('.confirm').click(function() {

        return confirm("Are You Sure To Delete It?");
    });
    /*
     ** Category view  Options
     */
    $('.cat h3').click(function() {

        $(this).next('.full-view').fadeToggle(200);

    });
    $('.options span').click(function() {
        $(this).addClass('active').siblings('span').removeClass('active');
        if ($(this).data('view') === 'full') {
            $('.cat .full-view').fadeIn(200);
        } else {
            $('.cat .full-view').fadeOut(200);
        }
    });



    // show and hide button Delete in the same block
    $('.child-link').hover(function() {
        $(this).find('.child-link-d').fadeIn(400);

    }, function() {
        $(this).find('.child-link-d').fadeOut(400);
    });
});