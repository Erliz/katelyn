$('#newAlbum').submit(function(e){
    e.preventDefault();
    var form = $(this);
    var layout = $('#createAlbumModal');
    var title = $(form.find('input')[0]).val();
    createNewAlbum(title);
    layout.modal('hide');
});

$('#createAlbumModal').on('hidden', function(){
    $(this).find('form')[0].reset();
}).on('shown', function(){
    $($(this).find('input')[0]).focus();
});

function createNewAlbum(title){
    var selector = $('#albumSelector');
    $.ajax({
        type: 'POST',
        url: '/ajax/admin/album/new/',
        data: {title:title},
        dataType: 'json',
        success: function(response){
            if(typeof response.error != "undefined"){
                msg.error('Ошибка!', 'Не удалось создать новый альбом');
                console.error('Album create error: ', response.error);
            } else {
                var album = response.data;
                msg.success('Новый альбом "'+album.title+'"', 'успешно создан');
                var newOption = $('<option value="'+album.id+'">'+album.title+'</option>');
                selector.append(newOption.prop('selected', true));
                setAlbum(newOption);
            }
        }
    });
}

function setAlbum(element){
    var value =$(element).val();
    var fu = $('#fileupload');
    if(value<=0){
        fu.removeClass('in');
        return;
    }
    fu.addClass('in');

    fu.fileupload({
        url: '/ajax/admin/photo/upload/?album='+value,
        maxFileSize: 5000000,
        acceptFileTypes: /(\.|\/)(jpe?g)$/i,
        process: [
            {
                action: 'load',
                fileTypes: /^image\/(jpeg)$/,
                maxFileSize: 20000000 // 20MB
            },
            {
                action: 'save'
            }
        ]
    });
}

function showCoverImg(id){
    var el = $('#album_cover_img');
    el.attr("src","/files/photo/thumbnail/"+id+".jpg");
    if(el.hasClass('hidden'))el.removeClass('hidden');
}

function Messenger(){
    this.layout = $('#messengerLayout');
    this.tpl = $('#messengerTemplate');
    this.library = [];

    this.draw = function(type, title, message){
        title = title || '';
        message = message || '';
        var tpl = this.getNewTemplate();
        tpl.removeAttr('id');
        tpl.addClass('alert-'+type);
        var titleTpl = $(tpl.find('*[data-type="title"]')[0]);
        var messageTpl= $(tpl.find('*[data-type="text"]')[0]);
        titleTpl.text(title);
        messageTpl.text(message);
        this.library.push(tpl)
        this.layout.append(tpl);
        tpl.alert();
        tpl.show();
        setTimeout(function(){
            tpl.alert('close')
        }, 30000);
    }

    this.success = function(title, message){
        this.draw('success',title, message);
    }

    this.error = function(title, message){
        this.draw('error',title, message);
    }

    this.info = function(title, message){
        this.draw('info',title, message);
    }

    this.getNewTemplate = function(){
        return this.tpl.clone();
    }
}

var msg = new Messenger();
