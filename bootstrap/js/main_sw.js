$( function(){
  var data = Bind({
      me: {
        score: 0,
      },
    }, {
    'me.score': {
      dom: '.score',
      transform: function (v) {
        return 1.111 * this.safe(v);
      }
    }
  });
});
  