var IMC = 0; //Input Message Count

function message(object, message) {
    var parent = object.parents('div.input_wrap');
    parent.after(
        '<div class="form_row input_message" style="' +
        'color: #e60012;' +
        'font-size: 12px;' +
        'margin: 0;' +
        'line-height: 14px;' +
        'margin-top: 5px;' +
        'margin-bottom: 10px;' +
        'word-wrap:break-word;">' +
        message + '</div>');

    object.css({
        'opacity': '0',
        'margin-top': '-20px',
    });
    object.animate({
        'opacity': '1',
        'margin-top': '0',
    }, 300);
    IMC++;
}

function clean_message() {
    IMC = 0;
    $('.input_message').remove();
}


/* make it to json object */
function parse(target) {
    return {target: target, val: $(target).val()};
}

function callback(obj, status, success, fail) {
    if (status == "success" && obj.success) {
        if (success != undefined && typeof success === "function") {
            success();
        }
    } else {
        if (obj.message != undefined && obj.message.length > 0) {
            for (var i = 0; i < obj.message.length; ++i) {
                var json = obj.message[i];
                if(json.target === "alert") {
                    alert(json.text);
                } else {
                    message($(json.target), json.text);
                }
            }
        }
        if (fail != undefined && typeof fail === "function") {
            fail();
        }
    }
}

$(document).ready(function () {
    $('input[type=password]').each(function () {
        $parent = $(this).parents('label');
        $toggle = $('<div class="toggle"></div>');
        $toggle.click(function () {
            $input = $(this).prev('input');
            var type = $input[0].type;
            if (type == 'password') {
                $(this).addClass('on');
                $input.attr('type', 'text');
            } else {
                $(this).removeClass('on');
                $input.attr('type', 'password');
            }
        });
        $parent.append($toggle)
    });

    $('input[type=checkbox]').change(function() {
        if($(this).is(':checked')) {
            $(this).val('1');
        } else {
            $(this).val('0');
        }
    })
})

function pad(n, width) {
    n = n + '';
    return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
}

// $(document).ready(function () {
//     $('label').mousedown(function () {
//        var inputs = $(this).find('input');
//        if(inputs.length>0) {
//            inputs.addClass('clicked');
//            inputs.focusout(function () {
//               $(this).removeClass('clicked');
//            });
//        }
//     });
//     $('a, input').mousedown(function () {
//         $(this).addClass('clicked');
//         $(this).focusout(function () {
//             $(this).removeClass('clicked');
//         });
//     })
//     let wrap = $('.container_wrap > .wrap');
//     wrap_animate(wrap);
// });
