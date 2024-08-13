var $ = jQuery;
$(document).ready(function () {
    var validateForm = false;
    var $msg = $('#validation-message');
    var $vinValidFlagInput = $('#vin_valid_flag');
    var $ctrl = $('#vin');
    $ctrl.keyup(function () {
      var value = $ctrl.val().toUpperCase().trim();
      if ( value.length < 17 && !validateForm ) {
        // don't validate
        $msg.html('');
        $vinValidFlagInput.val('false');
        return;
      }
      if ( validateVin(value) ) {
        $msg.html("\u2705 Your Tesla VIN is valid!");
        $vinValidFlagInput.val('true');
      } else if ( validateForm ) {
        $msg.html("\u274c Not a valid Tesla VIN");
        $vinValidFlagInput.val('false');
      }
    });

    function validateVin(vin) {
      if ( vin.length < 1 && validateForm ) {
        // reset form
        $msg.html('');
        $vinValidFlagInput.val('false');
        validateForm = false;
        return;
      }

      return validate(vin);

      function transliterate(c) {
        return '0123456789.ABCDEFGH..JKLMN.P.R..STUVWXYZ'.indexOf(c) % 10;
      }

      function get_check_digit(vin) {
        var map = '0123456789X';
        var weights = '8765432X098765432';
        var sum = 0;
        for ( var i = 0; i < 17; ++i )
          sum += transliterate(vin[ i ]) * map.indexOf(weights[ i ]);
        return map[ sum % 11 ];
      }

      function validate(vin) {
        validateForm = true;
        if ( vin.length !== 17 ) return false;
        if ( vin.slice(0, 3) !== "5YJ" ) return false;
        return get_check_digit(vin) === vin[ 8 ];
      }
    }

  });
