<html>
<head>
  <title>Visite</title>
</head>

<body>

</body>

<script src="{{ asset('assets/global/js/jquery.min.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/store.js/2.0.0/store.everything.min.js"></script> 

<script>

    function removeUserData()
    {
      if(typeof store !== 'undefined')
      {
        store.remove('user');
      }
    }

    $.post("/api/auth/login-url/{{$uuid}}", {
        })
        .done(function(data) {

          if(!("error" in data))
          {
              let url = "{{ URL::route('home') }}";

              if(typeof store == 'undefined')
              {
                url += "?" + "access_token=" + data.token;
              }else
              {
                store.set('user', data);
              }

            document.location.href = url;
          }else
          {
            removeUserData();
            document.location.href = "{{ URL::route('login') }}"
          }

        })
        .fail(function(err) {
            console.error(err);

            removeUserData();

            document.location.href = "{{ URL::route('login') }}"
        });

    
</script>

</html>


