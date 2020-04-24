<div class="col-sm-12">
	<div class="row animated fadeInDown">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Draggable Events</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>                       
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div id='external-events'>
                        <p>Drag a event and drop into callendar.</p>
                        <div class='external-event navy-bg'>Holiday Double Pay</div>
                        <div class='external-event navy-bg'>Holiday +30% Pay</div>
                        <div class='external-event navy-bg'>No Operation</div>
                    </div>
                </div>
            </div>
            <div class="ibox float-e-margins">
               
            </div>
        </div>
        <div class="col-lg-9">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Striped Table </h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>                       
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
	$(document).ready(function() {          

        /* initialize the external events
         -----------------------------------------------------------------*/


        $('#external-events div.external-event').each(function() {

            // store data so the calendar knows to render an event upon drop
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                stick: true // maintain when user navigates (see docs on the renderEvent method)
            });

            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 1111999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });

        });


        /* initialize the calendar
         -----------------------------------------------------------------*/
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month'
            },
            editable: true,
            droppable: true, // this allows things to be dropped onto the calendar            
            eventDrop: function(event,delta,revertFunc) {                                
                if(!confirm("Are you sure about this change?")) {
                    revertFunc();
                }else {
                    editDrop(event);
                }                
            },
            eventReceive: function(event,view) {                
                addDrop(event);
                $('#calendar').fullCalendar('removeEvents',event._id);
            },
            eventSources:[
                {
                    events: function(start,end,timezone,callback) {
                        $('.fc-event').remove();
                        $.ajax({
                            url: base_url+'admin_calendar/calendar/getAllEvents',
                            dataType: 'JSON',
                            data: {
                                start:start.format("YYYY-MM-DD"),
                                end:end.format("YYYY-MM-DD"),
                            },
                            success: function(msg) {                                
                                let events = msg.events;
                                callback(events);
                            }
                        });
                    }
                }
            ]
            // events: [                
            //     {
            //         title: 'Long Event',
            //         start: new Date(y, m, d-5),
            //         end: new Date(y, m, d-2)
            //     },                
            // ]
        });


});

function editDrop(event) {        
    let start = event.start.format("YYYY-MM-DD");        
    let id = event.id;        

    $.ajax({
        url:base_url+'admin_calendar/calendar/editCalendar',
        data:{"id":id,"title":event.title,"eventUpdate":start},
        traditional:true,
        type:"POST",
        success: function(data) {

        }
    });
}

function addDrop(event) {
    let start = event.start.format("YYYY-MM-DD");

    $.ajax({
        url:base_url+'admin_calendar/calendar/addCalendar',
        data:{"title":event.title,"eventDate":start},
        traditional:true,
        type:"POST",
        success: function(data) {                
            event = {
                id: data.toString(),                    
                title: event.title,
                start: start,                    
            }                
            $('#calendar').fullCalendar('renderEvent',event,false);                
        }
    });   
}
</script>