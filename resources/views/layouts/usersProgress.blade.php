<script src="{{asset('https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js')}}"></script>
<script src="{{asset('js/fancywebsocket.js')}}"></script>
<script>
    var Server;

    function log( text ) {
        $log = $('#log');
        //Add text to log
        $log.append(($log.val()?"\n":'')+text);
        //Autoscroll
        $log[0].scrollTop = $log[0].scrollHeight - $log[0].clientHeight;
    }

    function send( text ) {
        Server.send( 'message', text );
    }

    $(document).ready(function() {
        log('Connecting...');
        Server = new FancyWebSocket('ws://172.16.43.123:9300');

        $('#message').keypress(function(e) {
            if ( e.keyCode == 13 && this.value ) {
                log( 'You: ' + this.value );
                send( this.value );

                $(this).val('');
            }
        });

        //Let the user know we're connected
        Server.bind('open', function() {
            log( "Connected." );
        });

        //OH NOES! Disconnection occurred.
        Server.bind('close', function( data ) {
            log( "Disconnected." );
        });

        //Log any messages sent from server
        Server.bind('message', function( payload ) {
            log( payload );
        });

        Server.connect();
    });
</script>

<div id='body'>
    <textarea id='log' name='log' readonly='readonly'></textarea><br/>
    <input type='text' id='message' name='message' />
</div>

<div class="col-lg-12 hide_parent" style="height:255px;padding:20px;overflow-x:auto;display:inline-block;">

    <div class='col-lg-3 hide_me' style="background-color:#3b5998;color:white;padding:10px;border:1px solid black;height:210px;margin-right:10px;display:inline-block;">
        <div class="col-lg-12" style="font-size:70px;margin-right:auto;margin-left:auto">
            <i class="glyphicon glyphicon-user"></i>
        </div>

        <div style='height:20px;width:250px;border:2px solid black'><div id='textfile' style="height:16px;background-color:green;width:0%"></div></div>


    </div>


    <div class='col-lg-3 hide_now' style="background-color:#54aced;color:white;padding:10px;border:1px solid black;height:210px;margin-right:10px;display:inline-block;">
        <div class="col-lg-12" style="font-size:70px;margin-right:auto;margin-left:auto">
            <i class="glyphicon glyphicon-user"></i>
        </div>

    </div>



    <div class='col-lg-3 hide_now' style="background-color:#ce301c;color:white;padding:10px;border:1px solid black;height:210px;margin-right:10px;display:inline-block;">
        <div class="col-lg-12" style="font-size:70px;margin-right:auto;margin-left:auto">
            <i class="glyphicon glyphicon-user"></i>
        </div>
    </div>

</div>
