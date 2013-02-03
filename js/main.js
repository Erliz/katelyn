$(document).ready(
    function(){
        $('#content').fadeIn(1500);
    }
);

function notYetReady() {
    new Messi('We are working on this feature.', {title:'Sorry'});
}

function login() {
    new Messi(
        '<div class="signIn">'+
        '<label for="passwd">Please, enter your single-use password</label><br/><br/>' +
        '<input type="password" onkeyup="$(\'#signInPasswd\').val(this.value)">'+
        '</div>',
        {
            title:'Sign In',
            buttons: [{id: 0, label: 'Enter', val: 'submit'}],
            callback:function (val) {
                $('#signIn').submit();
            }
        });
}

function getUri(){
    return window.location.pathname;
}

function getHash(){
    var hash = window.location.hash;
    return (hash) ? hash.substring(1, hash.length) : false;
}