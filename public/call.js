$(document).ready(function() {
  var host = 'http://192.168.1.150';
  function formatText(text) {
    function createTel(str) {
      return "<a href='tel:"+str+"'>"+str+"</a>";
    }
    function format(str) {
      if(str[0] === '+') {
        return createTel(str);
      }
      return nl2br(str)
    }
    function nl2br(str) {
      return (str + '').replace(/\n/g, '<br>');
    }
    return "<span class='question-text'>"+format(text)+"</span><br>"
  }

  function showDialog(dialogData) {
    var content = formatText(dialogData.text);
    content += dialogData.buttons.map(function(button) {
      return "<button class='btn btn-primary answer' id='btn"+button.ID+"'>"+button.Text+"</button><br>";
    }).join('\n');
    $(".dialog").html(content);
  }

  function enableButtons(dialogData) {
    dialogData.buttons.forEach(function(button) {
      $('#btn'+button.ID).on('click', function() {
        loadNewDialog(button.ID);
      })
    })
  }

  function get(addr, callback) {
    $.get(host+addr, function(data) {
      if(data.success) {
        callback(data.result);
      } else {
        alert('ошибка:'+data.error);
      }
    });
  }

  function loadNewDialog(id) {
    console.log('load dialog', id);
    get('/API/calls.button?buttonID='+id, createDialog);
  }

  function createDialog(dialogData) {
    console.log('create dialog');
    showDialog(dialogData);
    enableButtons(dialogData);
  }

  function showStartDialogButton() {
    $('.dialog').html(
      '<button type="button" id="call" class="btn btn-primary answer">'+
			'Позвонить <i class="fa fa-phone"></i>'+
		'</button>')
    $('#complete').hide();
    $('#call').click(function() {
      console.log('ask for phone');
      $('#complete').show();
      // var testData = { text: '+7324', buttons: [{text: 'Ответил', id: '1'}, {text: 'Не ответил', id: '2'}] }
      // showDialog(testData);
      get('/API/calls.init', createDialog);
    })
  }

  console.log('dom ready');
  showStartDialogButton();
  $('#complete').on('click', function() {
    $.get(host+'/API/calls.complete', function(data) {
      showStartDialogButton();
    })
  })

});
