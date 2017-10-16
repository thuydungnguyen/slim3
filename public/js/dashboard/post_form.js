$( document ).ready(function() {
    FormActions.initTinyMCE();

    FormActions.toggleDescription($('select[name=zone]').val());

    $(document).on('change', 'select[name=zone]', function(){
        FormActions.toggleDescription($(this).val());
    });

});

var FormActions = {
    initTinyMCE: function(){
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
    },
    toggleDescription: function (zone) {
        var descriptionInput = $(document).find("input[name=description]");

        if(zone === 'blog'){
            descriptionInput.removeAttr('required');
            descriptionInput.parents('.form-group').hide();
        }else{
            descriptionInput.parents('.form-group').show();
            descriptionInput.attr('required', 'true');
        }
    }
}