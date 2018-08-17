$( function(){
  var data = Bind({
      me: {
        score: 0,
      },
    }, {
    'me.score': '.score' 
  });
  function hola() {
    document.body.innerHTML($('.score').val() * 5)
  }
  setInterval(hola,1000);
});
