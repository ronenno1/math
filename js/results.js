$(document).ready(function() {
    $(".btn_show_participant").bind("click", show_participant);
    $(".btn_delete_participant").bind("click", delete_participant);
    $(".btn_remove_morfix").bind("click", remove_morfix);
    $("#select_all").change(function() {
        $('.check').prop('checked',this.checked);
    });
    $(".check").change(function(){
        var unchecked = $('.check:not(:checked)');
        if(unchecked.length)
            $('#select_all').prop('checked',false);
        else
            $('#select_all').prop('checked',true);
    });
});


function excel_output(id)
{
    $.post('./rpc/results.rpc.php',{action: 'excel_output', id: id}).done(function(ret){location.href=ret});
}

function excel_output_group()
{
    var ids    = $('.check:checked').map(function () {
        var tr    = $(this).parent().parent(); //tr
        var id = tr.children("td:nth-child(2)");
        return id.text();    
    }).get();
    if(ids.length == 0)
        return;
    $.post('./rpc/results.rpc.php',{action: 'excel_output_group', ids: ids}).done(function(ret){location.href=ret});
}

function show_participant()
{
    var tr        = $(this).parent().parent(); //tr
    var id     = tr.children("td:nth-child(2)");
    var id_val = id.html();    
    $.post('./rpc/results.rpc.php',{action: 'show_participant',id: id_val}).done(function(ret){
        $('#output').html(ret);
    });    
}

function delete_participant()
{
    var tr        = $(this).parent().parent(); //tr
    var id     = tr.children("td:nth-child(2)");
    var id_val = id.html();
    var delete_it = confirm('Are you sure?');
    if(delete_it)
        $.post('./rpc/results.rpc.php',{action: 'delete_participant',id: id_val}).done(function(){location.href='';});    
}

function remove_morfix()
{
    var tr          = $(this).parent().parent(); //tr
    var stimulate_text  = tr.find('.r_stimulate_text').text();
    var r_text  = tr.find('.r_text').text();
    console.log(r_text);

    var t = confirm('are you sure??');
    if(!t)
        return;

    $.post('./rpc/fix_empty.rpc.php',{action: 'save2db', wrong_text: r_text, morfix_text: '', stimulate_text: stimulate_text}).done(function(ret){
        tr.find('.r_morfix_text').text('');
    });    
}
