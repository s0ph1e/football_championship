$(document).ready(function() {
    
    // Скрываем строку команду, которую удалили
    $('.del_team').click(function(e) {
        e.preventDefault();
        // Получение id записи из id элемента
        var id = this.id.split('_')[1];
        $.ajax({
            url: site_url + "/team/delete/" + id
        }).done(function() { 
            $('#del_' + id).parent().parent().fadeOut()
        });
        
    return false;
    });

});
