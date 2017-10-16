$( document ).ready(function() {
    $(document).on('click', '.btn-remove', function() {
        PostListActions.deletepost($(this).parents('tr').data('post-id'));
    });

    $(document).on('click', '.change-status', function() {
        PostListActions.changeStatus($(this).parents('tr').data('post-id'));
    });
});

var PostListActions = {
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