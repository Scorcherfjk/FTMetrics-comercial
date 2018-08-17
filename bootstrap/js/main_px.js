$( function(){
  var data = Bind({
      me: {
        score: 0,
      },
    }, {
    'me.score': {
      dom: '.score',
      transform: function (v) {
        return 5 * this.safe(v);
      }
    }
  });
});
