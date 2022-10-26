$(function () {
    /* キーの入力を検知 */
    $(document).on('input', '[name=buyValue]', function () {
      var val = $('[name=buyValue]').val();
      console.log(val);
    });
  });