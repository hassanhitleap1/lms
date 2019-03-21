function Scorm() {
    this.Initialize = function (emptyVal) {
        if (true) {
            return true;
        } else {
            return false;
        }
    };
    this.Terminate = function (emptyVal) {
        if (true) {
            return true;
        } else {
            return false;
        }
    };
    this.GetLastError = function () {
        return "0";//integer in the range from 0 to 65536 inclusive, 0=no errors
    };
    this.GetDiagnostic = function (ErrCode) {
        return "error description and how to solve it";//maximum length of 255
    };
    this.Commit = function (emptyVal) {
        if (true) {
            return true;
        } else {
             return false;
        }
    };
    this.GetErrorString = function (ErrCode) {
        return 'error description';//maximum length of 255
    };
    this.GetValue = function (cmi) {

        var url = '';
        switch (cmi) {
            case 'cmi.learner_name':
                url = 'http://localhost:8000/api/learner_name';
                break;
        }
        $.ajax({
            url: url,
            type: 'GET',
            callback: '?',
            cmi: cmi,
            data: {},
            dataType: 'application/json',
            success: function (data) {
                switch (cmi) {
                    case 'cmi.learner_name':
                        return data.username;
                        break;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error', {"username": "yosifkamals"});
                return {"username": "yosifkamals"};
            }
        });
    };

    this.SetValue = function (cmi, val) {
        console.log(cmi, val);
        var url = '';
        $data = {};
        switch (cmi) {
            case 'cmi.success_status':
                url = 'http://localhost:8000/api/success_status';
                $data = {mediaid: $("#receiver").attr('att'), status: val};
                break;
            case 'cmi.score.min':
                url = 'http://localhost:8000/api/scoremin';
                $data = {mediaid: $("#receiver").attr('att'), scoremin: val};
                break;
            case 'cmi.score.max':
                url = 'http://localhost:8000/api/scoremax';
                $data = {mediaid: $("#receiver").attr('att'), scoremax: val};
                break;
            case 'cmi.session_time':
                url = 'http://localhost:8000/api/sessiontime';
                $data = {mediaid: $("#receiver").attr('att'), time: val};
                break;
            case 'cmi.score.raw':
                url = 'http://localhost:8000/api/scoreraw';
                $data = {mediaid: $("#receiver").attr('att'), result: val};
                break;
            case 'cmi.completion_status':
                url = 'http://localhost:8000/api/completionstatus';
                $data = {mediaid: $("#receiver").attr('att'), completions: val};
                break;
        }
        $.ajax({
            url: url,
            type: 'GET',
            callback: '?',
            cmi: cmi,
            data: $data,
            dataType: 'application/json',
            success: function (data) {
                switch (cmi) {
                    case 'cmi.learner_name':
                        return data.username;
                        break;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('error');

            }
        });
    };
}


//var s=new Scorm();
//s.Initialize("");//return boolean true or false | to connect to LMS | call on laod
//s.GetValue("cmi");// return text value | to get student mark
//s.SetValue("cmi","25");//return boolean true or false | to set student mark
//s.Commit("");//return boolean true or false | to save student mark to DB
//s.GetLastError();//return text error code | to get last error code
//s.GetErrorString("ErrCode");//return text error description | to get last error description
//s.GetDiagnostic("ErrCode");//return text more info about error and how to solve it | to get last error more info
//s.Terminate("");//return boolean true or false | to disconnect from LMS | call on unload
//
//s.SetValue("cmi.completion_status","completed");//when complete game
//s.SetValue("cmi.success_status","passed");//when complete game set value to one of ("passed","failed","unknown")
//s.SetValue("cmi.score.raw",100);//to set user score in the game
//s.SetValue("cmi.score.min",10);//to set min score in the game
//s.SetValue("cmi.score.max",100);//to set max score in the game
//s.SetValue("cmi.session_time",555555);//to set Amount of seconds that the learner has spent
//
//s.GetValue("cmi.learner_name");//to get username
window.API_1484_11 = new Scorm();

alert();
window.addEventListener( "message",
    function (e) {
        console.log("msg",e.data);
        var data= e.data.split("#");
        window.API_1484_11.SetValue(data[0],data[1]);
    },
    false);