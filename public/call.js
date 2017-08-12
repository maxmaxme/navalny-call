$(document).ready(function() {
  console.log('dom ready');
  $('#call').click(function() {

    $.get('http://192.168.1.150/API/test2', function(data) {
      console.log('data:', data);
    })

  })
});
