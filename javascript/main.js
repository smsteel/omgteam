/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function onAjaxMod() {
    
    $('a').each(function(i, el) {
            
            $(el).click(function() {
                $('#content').fadeOut("slow", function() {
                    $.get($(el).attr('href')+'&print_only_content=1', function(data) {
                        $('#content').html(data);
                        $('#content').fadeIn("slow");
                    });
                });
                return false;
            });
    });
    
    
    
    //$('#content').html();
    
    return true;
}
