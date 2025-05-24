
"use strict";


$(document).ready(function(){

    /* Table should have at least 400px min-height */
    $('.table-section table').each(function(i,x){
        let current_height = $(x).height();
        let to400 = current_height > 400 ? 0 : (400 - current_height);
        $(x).css('min-height',current_height + to400 + 'px');
    });


    $('.pagination a.page-link').click(function(){
        return false;
    });


    footer_year('this_year');


});



function toggleNav() {
    $('nav').toggleClass('collapse');
    $('#search_box').toggleClass('collapse');
    $('#close_button').toggleClass('collapse');
}

function clearSearchbox() {
    $('#search_box').val('');
    rstSearch($('#search_box').val(),'note_body')
}


function toggle_showContent(fa_elem, table) {
    $(fa_elem).toggleClass('fa-eye-slash');
    $('#'+table+' .note_content').toggleClass('unblur');
    let as_is = get_lightcookie('trust_'+table) == "true" ? true : false;
    set_lightcookie('trust_'+table, !as_is, 30);
}


function sort(fa_elem, table_body_id) {
    
    if(fa_elem) {
        if($(fa_elem).hasClass('fa-arrow-down')) {
            fa_elem.classList.replace('fa-arrow-down', 'fa-arrow-up');
        }
        else fa_elem.classList.replace('fa-arrow-up', 'fa-arrow-down');
    }

    let temp = [];

    $('#'+table_body_id+' .note_body').each(function(i,x){
        temp.push($(x));
    })

   $('#'+table_body_id).html(temp.reverse());

}


function page_to(elem,table_id,start,length) {
    
    $('#'+table_id+' .note_body').each(function(i,x){
        if (i>=start && i<=start+length) {
            $(x).fadeIn('fast');
        }
        else $(x).hide();
    });

    if(elem) {
        $('#'+table_id+' + .pagination a').removeClass('active');
        $(elem).addClass('active');
    }

}


function toggle_showForm(shows,hides) {
    
    $('#'+hides).hide();
    $('#'+hides+' input').removeAttr('required');
    
    $('#'+shows).fadeIn('fast');
    $('#'+shows+' input').attr('required',true);

    if (hides == 'u') $('#floatingUsername').removeAttr('pattern');
    else $("#floatingUsername").attr('pattern',"[@a-zA-Z]{4,30}");

}



function footer_year(output_class) {
  let dd = new Date();
  output_class = document.getElementsByClassName(output_class);
  for(let x of output_class) x.innerText = dd.getFullYear(); 
}
