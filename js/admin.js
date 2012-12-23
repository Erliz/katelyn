function newAlbum() {
    newAlbumTitle='';
    new Messi(
        '<div class="signIn">'+
        '<label for="title">Please, enter your new album title</label><br/><br/>' +
        '<input type="text" id="newAlbumTitle" onkeyup="newAlbumTitle=this.value">'+
        '</div>',
        {
            title:'Новый Альбом',
            buttons: [{id: 0, label: 'Enter', val: 'submit'}],
            callback:function (val) {
                createNewAlbum(newAlbumTitle);
            }
        }
    );
}

function createNewAlbum(title){
    $.ajax({
        type: 'POST',
        url: '/ajax/admin/album/new/?debug',
        data: {title:title},
        dataType: 'json',
        success: function(data){
            if(typeof data.error != "undefined"){
                new Messi(
                    data.error,
                    {
                        title:'Новый Альбом',
                        titleClass: 'anim error'
                    }
                );
            } else {
                data=data.data;
                new Messi(
                    'Новый альбом "'+data.title+'" успешно создан',
                    {
                        title:'Новый Альбом',
                        titleClass: 'success',
                        buttons: [{id: 0, label: 'Enter', val: 'refresh'}],
                        callback:function () {
                            location.reload();
                        }
                    }
                );
            }
        }
    });
}