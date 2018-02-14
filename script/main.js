/**
 * Created by user on 13.02.2018.
 */
(function($) {

    var actions = {
        addcomment  : 'main.php?action=addcomment',
        editcomment : 'main.php?action=editcomment',
        replycomment: 'main.php?action=replycomment',
        getcomments : 'main.php?action=getcomments'
    };

    getComments();

    $('#form-comment').on('submit', function (e) {
        e.preventDefault();

        var form = $(this);

        var data = form.serialize();
        var url = actions[form.find('[name="action"]').val()];

        $.ajax({
            url: url,
            type: 'post',
            data: data,
            dataType: 'json',
            beforeSend: function() {
                $('.alert').remove();
            },

            success: function(json) {

                if (json['success']) {

                    getComments();
                    var html = '<div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>';
                    form.prepend(html);

                    form[0].reset();
                    form.find('textarea').text('');
                    form.find('[name="action"]').val('addcomment');
                    form.find('[name="id"]').val('');
                }

                if (json['error']) {

                    var html = '<div class="alert alert-warning alert-dismissible">';

                    for (var key in json['error']) {
                        html += '<i class="fa fa-check-circle"></i> ' + json['error'][key] + ' <br/>';
                    }


                    html += '<button type="button" class="close" data-dismiss="alert">&times;</button></div>';

                    form.prepend(html);
                }

            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $(document).delegate('.comment', 'click', function () {

        var id = $(this).closest('.comments').data('message-id');

        var name = $(this).closest('.comments').find('.name').text();
        var comment = $(this).closest('.comments').find('.comment').text();

        var form = $('form');

        form.find('[name="id"]').val(id);
        form.find('[name="name"]').val(name);
        form.find('[name="comment"]').text(comment);
        form.find('[name="action"]').val('editcomment');

        $('html, body').animate({ scrollTop: $(form).offset().top }, 500);
        
    });
    
    $(document).delegate('a.reply', 'click', function () {

        var id = $(this).closest('.comments').data('message-id');
        var form = $('form');

        form.find('[name="id"]').val(id);
        form.find('[name="action"]').val('replycomment');

        $('html, body').animate({ scrollTop: $(form).offset().top }, 500);
        
    });

    function getComments()
    {
        $.ajax({
            url: actions.getcomments,
            dataType: 'html',

            success: function(html) {

                if (html) {
                    $('#comments').html(html);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }


    $(document).ajaxStop(function() {
        $('.comment').tooltip({container: 'body', title: 'Править'});
    });

})(window.jQuery);