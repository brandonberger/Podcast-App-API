$("body").on("click", ".submit", function(event) {
    event.preventDefault();

    var form = $(this).parent();


    switch (form[0].name) {
        case 'user-entry':
            var data = {
                email: $('.'+form[0].name+' input[name="email"]').val(),
                first_name: $('.'+form[0].name+' input[name="first_name"]').val(),
                last_name: $('.'+form[0].name+' input[name="last_name"]').val(),
            };
            var route = 'addUser';
            break;
        case 'search-user':
            var data = {
                query: $('.'+form[0].name+' input[name="query"]').val()
            }
            var route = 'searchUsers';
            break;
    }

    $.post("api/"+route, data).then(function(results){
        console.log('success');
        $('.results-table').html(results);
    });
});


$.get("api/getUsers").then(function(results) {
    $('.user-table').html(results);
});


$.get("api/getTags").then(function(results) {
    $('.tags').html(results);
});