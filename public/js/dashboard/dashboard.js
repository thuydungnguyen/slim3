$( document ).ready(function() {
    tinymce.init({
        selector: '#content',
        height: 500,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor textcolor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code help'
        ],
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        },
        toolbar: 'insert | undo redo |  styleselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css']
    });

    $(document).on('click', '.btn-remove', function() {
        PagesActions.deletepost($(this).parents('tr').data('post-id'));
    });

    $(document).on('click', '.change-status', function() {
        PagesActions.changeStatus($(this).parents('tr').data('post-id'));
    });
});

var PagesActions = {
    deletepost: function(id){
        $.ajax({
            url: baseUrl + '/admin/deletePost',
            type: 'POST',
            data: {id: id},
            success: function (data) {
                if(data['result'] === true){
                    alert('Your article has been deleted !');
                    $('tr[data-post-id='+data['id']+']').remove();
                }else{
                    alert('An error occured !');
                }
            }
        });
    },
    changeStatus: function(id){
        $.ajax({
            url: baseUrl + '/admin/changeStatus',
            type: 'POST',
            data: {id: id},
            success: function (data) {
                console.log(data)
                if(data['result'] === true){
                    var btn = $('tr[data-post-id='+data['id']+']').find('.change-status');
                    if(btn.hasClass('btn-warning')){
                        btn.removeClass('btn-warning').addClass('btn-success').html('Desactivate <i class="fa fa-toggle-off"></i>');
                    }else{
                        btn.removeClass('btn-success').addClass('btn-warning').html('Activate <i class="fa fa-toggle-on"></i>');
                    }
                }else{
                    alert('An error occured !');
                }
            }
        });
    }
}