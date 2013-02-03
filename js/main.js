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

function setHash(hash){
    window.location.hash=hash;
}

function sendFeedback(form){
    var values = {};
    $(form).find(':input').each(function() {
        values[this.name] = $(this).val();
    });
    console.log(values);
    $.ajax({
        async:false,
        type:'POST',
        url:'/ajax/sendMail/',
        dataType:'json',
        data: values,
        success:function (data) {
            if(data.data==true){
                new Messi('Ваше сообщение было отправленно', {title:'Feedback'});
            } else {
                new Messi('Возникла ошибка при отправке сообщения</br>Вы можите отправить сообщение по этой ссылке и адресу <a href="mailto:katelyn.k@ya.ru?subject=Feedback">Katelyn.K@ya.ru</a>', {title:'Feedback'});
            }
        }
    });
}