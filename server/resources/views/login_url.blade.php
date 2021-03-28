<html>
<head>
  <title>Visite</title>
</head>

<body>

</body>

<script src="{{ asset('assets/global/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/global/js/jquery.cookie.js') }}"></script>

<script>

    function setUserCookie(data)
    {
      const expiration_time_min = 14400;

      var expDate = new Date();
      $.cookie('user', JSON.stringify(data), { expires: expDate.setTime(expDate.getTime() + (expiration_time_min * 60 * 1000)), path: '/' });
    }

    $.post("/api/auth/login-url/{{$uuid}}", {
        })
        .done(function(data) {

          if(!("error" in data))
          {
            setUserCookie(data);

            document.location.href = "{{ URL::route('home') }}"
          }else
          {
            $.removeCookie('user', { path: '/' });
            document.location.href = "{{ URL::route('login') }}"
          }

        })
        .fail(function(err) {
            console.error(err);

            $.removeCookie('user', { path: '/' });

            document.location.href = "{{ URL::route('login') }}"
        });

    
</script>

</html>


