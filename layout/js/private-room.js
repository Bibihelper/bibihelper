/* Private room */

$(function() {
    $("#datepicker1").datepicker($.datepicker.regional["ru"]);
    
    $('#datepicker1').datepicker('option', 'beforeShow', function() {
        if (!$("#c-arrow-1").hasClass("ca-exp")) {
            $("#c-arrow-1").removeClass("c-arrow_expand").addClass("c-arrow_collapse").addClass("ca-exp");
        }
        return true;
    });
    
    $('#datepicker1').datepicker('option', 'onClose', function() {
        if ($("#c-arrow-1").hasClass("ca-exp")) {
            $("#c-arrow-1").removeClass("c-arrow_collapse").removeClass("ca-exp").addClass("c-arrow_expand");
        }
        return true;
    });
});

$("#c-arrow-1").click(function(e) {
    if ($(this).hasClass("ca-exp")) {
        $('#datepicker1').datepicker("hide");
    } else {
        $('#datepicker1').datepicker("show");
    }
    return true;
});

$(function() {
    $("#datepicker2").datepicker($.datepicker.regional["ru"]);                
    
    $('#datepicker2').datepicker('option', 'beforeShow', function() {
        if (!$("#c-arrow-2").hasClass("ca-exp")) {
            $("#c-arrow-2").removeClass("c-arrow_expand").addClass("c-arrow_collapse").addClass("ca-exp");
        }
        return true;
    });
    
    $('#datepicker2').datepicker('option', 'onClose', function() {
        if ($("#c-arrow-2").hasClass("ca-exp")) {
            $("#c-arrow-2").removeClass("c-arrow_collapse").removeClass("ca-exp").addClass("c-arrow_expand");
        }
        return true;
    });
});

$("#c-arrow-2").click(function(e) {
    if ($(this).hasClass("ca-exp")) {
        $('#datepicker2').datepicker("hide");
    } else {
        $('#datepicker2').datepicker("show");
    }
    return true;
});

$("#s-browse").click(function() {
    $("#s-br").click();
});

$("#s-br").change(function() {
    $("#s-filename").val($("#s-br").val());
});

$("#s-publish").click(function() {
    $(".s-off-ctrls").hide();
    $(".s-off-preview").css("float", "none");
    $(this).text("Удалить предложение").attr("data-btn-type", "2");
})

function updCbxState(cbx, state) {
    $(cbx).attr("data-ch", state);

    if (state == 1) {
        $(cbx).addClass("info__cbx_active");
    } else {
        $(cbx).removeClass("info__cbx_active");
    }
}

function updCbxStateNextElem(elm, stt) {
    var nxt = $(elm).next();
    var len = $(nxt).length;
    if (len != 0) {
        var nxx = $(nxt).children("span.info__chbx").children("div.info__cbx");
        updCbxState(nxx, stt);
        updCbxStateNextElem(nxt, stt);
    }
}

$("li.item-menu__i").click(function() {
    var cbx = $(this).children("span.info__chbx").children("div.info__cbx");
    var stt = $(cbx).attr("data-ch");
    stt = (stt == 0) ? 1 : 0;
    updCbxState(cbx, stt);
    if ($(this).hasClass("item-menu__i_first"))
        updCbxStateNextElem($(this), stt);
    return true;
});





