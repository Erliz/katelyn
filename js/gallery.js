function Photo(object) {
    this.id = object.id;
    this.title = object.title;
    this.description = object.description;
    this.album = object.album;
    this.is_vertical = object.is_vertical;
    this.time_upload = object.time_upload;

    this.getId=function(){
        return this.id;
    }

    this.getTitle = function () {
        return this.title;
    };

    this.getDescription = function () {
        return this.description;
    };

    this.getUrl = function () {
        return '/files/photo/' + this.id + '.jpg';
    };

    this.isVertical = function(){
        return this.is_vertical;
    }
}

function Collection(array) {
    // properties
    this.library = array;
    // methods
    this.getById = function (id) {
        var lib = this.library;
        for (var i = 0; i < this.getSize(); i++) {
            if (lib[i].id == id) return new Photo(lib[i]);
        }
        return false;
    };

    this.getPrev = function (id) {
        var lib = this.library;
        for (var i = 0; i < this.getSize(); i++) {
            if (lib[i].id == id) {
                if (typeof lib[i - 1] != "undefined") return new Photo(lib[i - 1]);
                else return false;
            }
        }
        return false;
    };

    this.getNext = function (id) {
        var lib = this.library;
        for (var i = 0; i < this.getSize(); i++) {
            if (lib[i].id == id) {
                if (typeof lib[i + 1] != "undefined") return new Photo(lib[i + 1]);
                else return false;
            }
        }
        return false;
    };

    this.getSize = function () {
        return this.library.length;
    };
}

function Gallery(id) {
    // selectors
    this.dom = {
        classes:{
            listing:'photo_page',
            pager:'pager',
            substrate:'substrate'
        },
        elements:{
            content:'content',
            photo_pager:'photo_pager',
            block:'photo_view_block'
        },
        tmpl:{
            block:'tmpl_photo_view'
        }
    };
    // properties
    this.album = {
        id:id,
        title:null,
        description:null,
        cover:null
    };
    this.collection = null;
    this.isShowing=false;
    // methods
    this.init = function () {
        this.requestCollection();
    };

    this.getDom = function (type, name) {
        var selector = type == 'classes' ? '.' : '#';
        var dom = this.dom[type][name];
        if (dom) return $(selector + dom);
        else return false;
    };

    this.getClass = function (name) {
        return this.getDom('classes', name);
    };

    this.getElement = function (name) {
        return this.getDom('elements', name);
    };

    this.getTmpl = function (name, data) {
        return this.getDom('tmpl', name).tmpl(data);
    };

    this.requestCollection = function () {
        if (this.collection) return;
        var object = this;
        $.ajax({
            async:false,
            type:'GET',
            url:'/ajax/album/collection/' + this.album.id,
            dataType:'json',
            success:function (data) {
                data = data.data;
                object.album.title = data.title;
                object.album.description = data.description;
                object.collection = new Collection(data.photos);
                object.album.cover = new Photo(object.collection.getById(data.cover));
            }
        });
    };

    this.sceneClear = function () {
        this.getClass('listing').hide();
        this.getElement('photo_pager').hide();
    };
    this.sceneFill = function () {
        this.getClass('listing').fadeIn();
        this.getElement('photo_pager').fadeIn();
    };

    this.showPhoto = function (id) {
        var photo = this.collection.getById(id);
        if(photo) var tpl = this.getTmpl('block',{photo:photo, next:this.collection.getNext(id), prev:this.collection.getPrev(id)});
        else {console.log('No such photo with id "'+id+'"');return;}
        var wrapper = this.getElement('content');
        if(!this.isShowing){
            this.sceneClear();
            var object = this;
            $('<div></div>').addClass('substrate').click(function(){object.close()}).appendTo(wrapper);
            tpl.hide().appendTo(wrapper).fadeIn();
            this.isShowing=true;
            $(document).keyup(function(e) {
                if (e.keyCode == 27) {
                    object.close();
                }
            });
        } else {
            this.getElement('block').remove();
            tpl.appendTo(wrapper);
        }
    };

    this.close = function(){
        this.getClass('substrate').remove();
        this.getElement('block').remove();
        this.sceneFill();
        $(document).keyup();
        this.isShowing=false;
    }
}
