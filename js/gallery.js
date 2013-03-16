function Photo(object) {
    this.id = object.id;
    this.title = object.title;
    this.description = object.description;
    this.album = object.album;
    this.is_vertical = object.is_vertical;
    this.time_upload = object.time_upload;

    this.getId = function () {
        return this.id;
    };

    this.getTitle = function () {
        return this.title;
    };

    this.getDescription = function () {
        return this.description;
    };

    this.getUrl = function () {
        return '/files/photo/' + this.id + '.jpg';
    };

    this.isVertical = function () {
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

    this.getByKey = function (key) {
        return new Photo(this.library[key]) || false;
    };

    this.getPositionById = function (id) {
        for (var i = 0; i < this.getSize(); i++) {
            if (this.library[i].id == id) return i;
        }
        return false;
    };

    this.getLibrary = function () {
        return this.library;
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
        classes: {
            listing: 'photo_page',
            pager: 'pager',
            substrate: 'substrate'
        },
        elements: {
            content: 'content',
            photo_pager: 'photo_pager',
            viewPager: 'photo_view_pager',
            block: 'photo_view_block',
            social: 'photo_view_social'
        },
        tmpl: {
            block: 'tmpl_photo_view',
            pager: 'tmpl_photo_view_pager'
        }
    };
    // properties
    this.album = {
        id: id,
        title: null,
        description: null,
        cover: null
    };
    this.collection = null;
    this.isShowing = false;
    this.showingPhoto = null;
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
        var self = this;
        $.ajax({
            async: false,
            type: 'GET',
            url: '/ajax/album/collection/' + this.album.id,
            dataType: 'json',
            success: function (data) {
                data = data.data;
                self.album.title = data.title;
                self.album.description = data.description;
                self.collection = new Collection(data.photos);
                self.album.cover = new Photo(self.collection.getById(data.cover));
                self.checkHash();
            }
        });
    };

    this.checkHash = function () {
        var hash = getHash();
        if (hash) this.showPhoto(hash);
    };

    this.sceneClear = function () {
        this.getClass('listing').hide();
        this.getElement('photo_pager').hide();
    };
    this.sceneFill = function () {
        this.getClass('listing').fadeIn();
        this.getElement('photo_pager').fadeIn();
    };

    /**
     * @returns Collection
     */
    this.getCollection = function () {
        return this.collection;
    };

    this.showPhoto = function (id) {
        this.showingPhoto = this.collection.getById(id);
        if (this.showingPhoto) {
            var tpl = this.getTmpl(
                'block',
                {
                    photo: this.showingPhoto,
                    next: this.getCollection().getNext(id) || this.getCollection().getByKey(0)
                }
            );
            var pager = this.getTmpl(
                'pager',
                {
                    collection: this.getCollection().getLibrary(),
                    current: this.showingPhoto,
                    position: this.getCollection().getPositionById(id),
                    size: this.getCollection().getSize(),
                    next: this.getCollection().getNext(id),
                    prev: this.getCollection().getPrev(id)
                }
            );
            setHash(id);
        }
        else {
            console.log('No such photo with id "' + id + '"');
            return;
        }
        var wrapper = this.getElement('content');
        if (!this.isShowing) {
            this.sceneClear();
            var self = this;
            $('<div></div>').addClass('substrate').click(function () {
                self.close()
            }).appendTo(wrapper);
            console.log(tpl);
            tpl.hide().appendTo(wrapper).fadeIn();
            pager.hide().appendTo(wrapper).fadeIn();
            this.isShowing = true;
            $(document).keyup(function (e) {
                if (e.keyCode == 27) {
                    self.close();
                }
            });
        } else {
            this.removeView();
            tpl.appendTo(wrapper);
            pager.appendTo(wrapper);
        }
        yandexSocial();
    };

    this.close = function () {
        this.getClass('substrate').remove();
        this.removeView();
        this.sceneFill();
        setHash('');
        $(document).keyup();
        this.isShowing = false;
    };

    this.removeView = function () {
        this.getElement('social').remove();
        this.getElement('block').remove();
        this.getElement('viewPager').remove();
    };
}
