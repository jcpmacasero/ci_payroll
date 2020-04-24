var daysofweek = ['sun', 'mon', 'tus', 'wed', 'thu', 'fri', 'sat'];
var month =['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];

function clock(){
    // setting up my variables
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    var day = h<12 ? 'AM': 'PM';
    var daytoday = today.getDay();
    var date = today.getDate();
    var mon = today.getMonth();
    var year = today.getFullYear();

    // adding leading zeros to them
    if(h < 10)
    {
      h = '0'+h;
    }
    else
    {
      if(h> 12)
      {
        h = h-12;

        if(h<10)
        {
          h='0'+h;
        }
      }
      
    }
    // h = h<10? '0'+h: h;
    // h = h>12? (h-12) <10? '0'+h: (h-12):h;
    // h = h<10? '0'+h: h;
    m = m<10? '0'+m: m;
    s = s<10? '0'+s: s;


    // writing it down in the document
    $("#hours").html(h);
    $("#mins").html(m);
    // document.getElementById('sec').innerHTML = s;
    $("#am_pm").html(day);
    // console.log(daysofweek[daytoday]);
    $('#'+daysofweek[daytoday]).addClass('days_active');
    $('#day').html(date);
    $('#month').html(month[mon]);
    $('#year').html(year);

}
clock();
setInterval(clock,1000);