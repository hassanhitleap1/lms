@extends('layout.app')
@section('title',  __('lang.View_Chat'))
@section('content')
    <div class="block-header">
        <h2>@yield('title')</h2>
    </div>
    <div class="content">
        <div class="body">
            <div class="clearfix">
                <div class="chat">
                    <ul id="chat" with-user="{{$_GET['with']}}">
                        @foreach($messages as $message)
                            @if($message->sender_user_id==\Illuminate\Support\Facades\Auth::user()->userid)
                                <li class="you">
                                    <a class="user" href="#"><img alt="" src="/images/user.png" /></a>
                                    <div class="date">
                                        {{\App\Helper\DateTimeHelper::getdifDateTime($message->created_at)}}
                                    </div>
                                    <div class="message">
                                        <div class="hider">
                                            <span>@lang('lang.Clicktoread')</span>
                                        </div>
                                        <p>
                                            {{$message->text}}
                                        </p>
                                    </div>
                                </li>
                            @else
                                <li class="other">
                                    <a class="user" href="#"><img alt="" src="/images/user.png" /></a>
                                    <div class="date">
                                        {{\App\Helper\DateTimeHelper::getdifDateTime($message->created_at)}}
                                    </div>
                                    <div class="message">
                                        <div class="hider">
                                            <span>@lang('lang.Clicktoread')</span>
                                        </div>
                                        <p>
                                            {{$message->text}}
                                        </p>
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    <div class="write-message-container active">
                        <input class="textarea" type="text" placeholder="Type here!" id="text">
                        <a class="btn btn-primary waves-effect float-right go-button" id="sendmessage">GO</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function () {
        scrollChat();
        $('#sendmessage').click(function () {
            sendMess();
        });
        $(document).keypress(function(e) {
            if(e.which == 13) {
                sendMess();
            }
        });
    });
    function sendMess() {
        text= $('#text').val();
        var d = new Date();
        withUser=$('#chat').attr('with-user');
        var strDate = 'now';
        $.ajax({
            url: SITE_URL + Language +'/messages/send-mess',
            type: 'POST',
            data:{text: text,withUser:withUser},
            dataType: 'json',
            success: function (text) {
                mess=' <li class="you">\n' +
                    '<a class="user" href="#"><img alt="" src="/images/user.png" /></a>\n' +
                    ' <div class="date">\n' + strDate+
                    '</div>\n' +
                    ' <div class="message">\n' +
                    '<div class="hider">\n' +
                    '<span>Click to read</span>\n' +
                    ' </div>\n' +
                    ' <p> '+ text.text+ '</p>\n' +
                    '</div>\n' +
                    '</li>';
                $('#chat').append(mess);
                $('#text').val('');
                scrollChat();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR+textStatus+errorThrown);
            }
        });
    }


    function printLastMessages(data) {
        content='';
        var strDate = 'now';
        if(data.lastMessages.length > 0){
            $.each(data.lastMessages, function( key, value ) {
                content+=' <li class="other">\n' +
                    '<a class="user" href="#"><img alt="" src="/images/user.png" /></a>\n' +
                    '<div class="date">\n' +strDate+'</div>\n' +
                    '<div class="message">\n' +
                    '<div class="hider">\n' +
                    '<span>Click to read</span>\n' +
                    '</div>\n' +
                    '<p>'+ value.text +'</p>\n' +
                    '</div>\n' +
                    '</li>';
            });
            $('#chat').append(content);
        }

        /*document.getElementById("chat").innerHTML=content;*/
    }
    function getLastMessages(){
        setTimeout(function(){
            withUser=$('#chat').attr('with-user');
            $.ajax({
                url: SITE_URL + '/en/messages/get-last-mess',
                type: 'GET',
                data:{withUser:withUser},
                dataType: 'json',
                success: function (data) {
                    printLastMessages(data);
                    getLastMessages();
                    scrollChat();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('error')
                }
            });

        }, 6000);
    }
   // Call our function
    getLastMessages();
</script>
@endsection
