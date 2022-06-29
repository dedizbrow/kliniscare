$(document)
.on("click","#submit_profile",function(e){
    e.preventDefault();
    $form=$(this).closest('form');
    var data=$form.serialize();
    http_request('profile/save-profile','POST',data)
    .done(function(res){
        Msg.success(res.message);
    })
})