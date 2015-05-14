$(document).ready(function () {
  $('.tooltip-right').tooltip({
    placement: 'right',
    viewport: {selector: 'body', padding: 2}
  })
  $('.tooltip-bottom').tooltip({
    placement: 'bottom',
    viewport: {selector: 'body', padding: 2}
  })
  $('.tooltip-viewport-right').tooltip({
    placement: 'right',
    viewport: {selector: '.container-viewport', padding: 2}
  })
  $('.tooltip-viewport-bottom').tooltip({
    placement: 'bottom',
    viewport: {selector: '.container-viewport', padding: 2}
  })
  



  $('.error').hide();
  $('#cpf').click(function(e){
    $('.error').hide('slow');
  });
  $('#senha').click(function(e){
    $('.error').hide('slow');
  });
  $("#btn-logon").click(function(e) {
    e.preventDefault()
    var name = $("#cpf").val();
    var senha = $("#senha").val();
    console.log("aki");
    var post_data = {'userName':name, 'userSenha':senha};
            $.post('coleta.php', post_data, function(response){  
                if(response.type == 'error')
                {
                    output = response.text;
                }else{
                    output = response.text;
                    window.location = "content.html";
                }
                //alert(output);
                $('.error').html(output);
                $('.error').show('slow');
            }, 'json');
  });
});


