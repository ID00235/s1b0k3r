$(function(){
      $('.numeric').mask("#", {reverse: true});
	  $('.datemask').mask('00/00/0000');
	  $('.decimal').mask("#.##0,00", {reverse: true});
	  $('.select2').select2({theme:'bootstrap'});
	  $('.datepicker').datepicker({
            format: "dd/mm/yyyy"
        });
     
})

var showNotify = function($title, $message){
    
    $.notify({
        icon: $base_url + '/img/success.png',
        title: $title,
        message: $message
    },{
        placement: {
            from: "top"
        },
        animate:{
            enter: "animated fadeInDown",
            exit: "animated fadeOutUp"
        },
        offset:{
            x:40,
            y:60
        },
        type: 'minimalist',
        delay: 4500,
        icon_type: 'image',
        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0} alert-dismissible" role="alert">' +
            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
            '<img data-notify="icon" class="img-circle pull-left">' +
            '<span data-notify="title">{1}</span>' +
            '<hr><span data-notify="message">{2}</span>' +
        '</div>'
    });

}

var showAlert = function($title, $message){

    $.notify({
        icon: $base_url + '/img/warning.png',
        title: $title,
        message: $message
    },{
        placement: {
            from: "top"
        },
        animate:{
            enter: "animated fadeInDown",
            exit: "animated fadeOutUp"
        },
        offset:{
            x:40,
            y:60
        },
        type: 'minimalist',
        delay: 4500,
        icon_type: 'image',
        template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0} alert-dismissible" role="alert">' +
            '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' +
            '<img data-notify="icon" class="img-circle pull-left">' +
            '<span data-notify="title">{1}</span>' +
            '<hr><span data-notify="message">{2}</span>' +
        '</div>'
    });
}


jQuery.extend(jQuery.validator.messages, {
    required: "Kolom Ini Harus Diisi!.",
    remote: "Please fix this field.",
    email: "Masukan Alamat Email yg Valid.",
    url: "Please enter a valid URL.",
    date: "Please enter a valid date.",
    dateISO: "Please enter a valid date (ISO).",
    number: "Please enter a valid number.",
    digits: "Please enter only digits.",
    creditcard: "Please enter a valid credit card number.",
    equalTo: "Please enter the same value again.",
    accept: "Please enter a value with a valid extension.",
    maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
    minlength: jQuery.validator.format("Please enter at least {0} characters."),
    rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
    range: jQuery.validator.format("Please enter a value between {0} and {1}."),
    max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
    min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
});

