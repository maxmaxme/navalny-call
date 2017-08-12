$(document).ready(function() {

  function showDialog(dialogData) {
    var content = "<span class='question-text'>"+dialogData.text+"</span><br>";
    content += dialogData.buttons.map(function(button) {
      return "<button class='btn btn-primary answer'>"+button.Text+"</button><br>";
    }).join('\n');
    $(".content").html(content);
  }

  console.log('dom ready');
  $('#call').click(function() {
    console.log('ask for phone');
    // var testData = { text: '+7324', buttons: [{text: 'Ответил', id: '1'}, {text: 'Не ответил', id: '2'}] }
    // showDialog(testData);
    $.get('http://192.168.1.150/API/calls.init', function(data) {
      showDialog(data.result);
    })

  })
});
