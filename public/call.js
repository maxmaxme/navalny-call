$(document).ready(function() {
  var host = 'http://192.168.1.150';
  function showDialog(dialogData) {
    var content = "<span class='question-text'>"+dialogData.text+"</span><br>";
    content += dialogData.buttons.map(function(button) {
      return "<button class='btn btn-primary answer' id='btn"+button.ID+"'>"+button.Text+"</button><br>";
    }).join('\n');
    $(".content").html(content);
  }

  function enableButtons(dialogData) {
    dialogData.buttons.forEach(function(button) {
      $('#btn'+button.ID).on('click', function() {
        loadNewDialog(button.ID);
      })
    })
  }

  function loadNewDialog(id) {
    console.log('load dialog', id);
    $.get(host+'/API/calls.button?buttonID='+id, function(data) {
      createDialog(data.result);
    })
  }

  function createDialog(dialogData) {
    console.log('create dialog');
    showDialog(dialogData);
    enableButtons(dialogData);
  }

  console.log('dom ready');
  $('#call').click(function() {
    console.log('ask for phone');
    // var testData = { text: '+7324', buttons: [{text: 'Ответил', id: '1'}, {text: 'Не ответил', id: '2'}] }
    // showDialog(testData);
    $.get(host+'/API/calls.init', function(data) {
      createDialog(data.result);
    })

  })
});
