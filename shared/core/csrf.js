(function(){
  function token(){
    return document.getElementById('csrf_token')?.value;
  }
  $.ajaxPrefilter(function(options, original, jqXHR){
    if((options.type || '').toUpperCase() === 'POST'){
      if(options.data instanceof FormData){
        options.data.append('csrf_token', token());
      }else{
        options.data = options.data ? options.data + '&' : '';
        options.data += 'csrf_token=' + encodeURIComponent(token());
      }
    }
  });
})();
