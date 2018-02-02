var openedComplaint = {};

var initMap = function () {

    $.get("http://localhost/www/timcomplaint-api/complaint/read.php", function (data, status) {
        console.log(data, status);
        var locations = [];
        if(!data.records){
            data.records = [];
        }
        data.records.forEach(function (rec, idx) {
            var _loc = rec.location;
            var _latlng = _loc.split(",");
            var locObj = {
                lat: _latlng[0],
                lng: _latlng[1],
                type: rec.category_name,
                status: rec.status,
                description: rec.description,
                created: rec.created,
                id: rec.id
            }
            locations.push(locObj);
        });

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 13,
            center: new google.maps.LatLng(45.759780, 21.230020),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });

        var infowindow = new google.maps.InfoWindow();

        var marker;

        locations.forEach(function (rec, idx) {
            var infoWinContent = "";

            infoWinContent = infoWinContent.concat('<b>', rec.type, '</b>', '<br>');
            infoWinContent = infoWinContent.concat('<b>', rec.status, '</b>', '<br>');
            infoWinContent = infoWinContent.concat('<div style="max-width:200px;">', rec.description, '</div>', '<br>');
            infoWinContent = infoWinContent.concat('<i>', rec.created, '</i>', '<br>');
            infoWinContent = infoWinContent.concat('<a onclick="editEvent(this,event);" href="#" data-complaintId="', rec.id, '">Edit</a>', ' | ', '<a href="#" onclick="deleteEvent(this,event);"  data-complaintId="', rec.id, '">Delete</a>');

            marker = new google.maps.Marker({
                position: new google.maps.LatLng(rec.lat, rec.lng),
                icon: './assets/pictures/pin.png',
                map: map
            });

            google.maps.event.addListener(marker, 'click', (function (marker, idx) {
                return function () {
                    infowindow.setContent(infoWinContent);
                    infowindow.open(map, marker);
                }
            })(marker, idx));

        });
    });

};

var editEvent = function(el,ev){
    ev.preventDefault();
    $('#editModal').modal('show');
    getOneComplaint(el.getAttribute('data-complaintId'));
};

var fillCarousel = function(pics) {
    pics.forEach(function(pic,idx){
        var picPath = "http://localhost/www/timcomplaint-api/uploads/"+pic.filename;
        var element;
        if(idx==0){
            element = '<div class="item active"><img src="'+picPath+'" alt="'+pic.filename+'"></div>';
        } else {
            element = '<div class="item"><img src="'+picPath+'" alt="'+pic.filename+'"></div>';
        }
        $('#carouselpictures').append(element);
    });
    $('#myCarousel').show();
};

var normalizeEditModal = function() {
    $('#myCarousel').hide();
    $('#carouselpictures').empty();
    $('#complainant').empty();
    $('#description').empty();
    $('#created').empty();
    $('#modified').empty();
};

var setDataInForm = function(data){
    if(data.pictures && data.pictures.length>0){
        fillCarousel(data.pictures);
    }
    $('#category').text(data.category_name);
    $('#complainant').text(data.user_name);
    $('#description').text(data.description);
    $('#created').text(data.created);
    $('#modified').text(data.modified);
    $("#status").val(data.status);
};

var getOneComplaint = function(id){
    normalizeEditModal();
    $.get("http://localhost/www/timcomplaint-api/complaint/read_one.php?id="+id, function (data, status) {
        if(status=="success" && data){
            openedComplaint = data;
            setDataInForm(data);
        } else {
            $('#editModal').modal('toggle');
        }
        console.log(data, status);
    });
};

var setComplaintInForm = function(complaint){
    infoWinContent = infoWinContent.concat('<b>', rec.type, '</b>', '<br>');
    infoWinContent = infoWinContent.concat('<b>', rec.status, '</b>', '<br>');
    infoWinContent = infoWinContent.concat('<div style="max-width:200px;">', rec.description, '</div>', '<br>');
    infoWinContent = infoWinContent.concat('<i>', rec.created, '</i>', '<br>');
}


var deleteEvent = function(el,ev){
    ev.preventDefault();
    $('#deleteModal').modal('show');
    $('#deleteModal').attr('data-complaintId',el.getAttribute('data-complaintId'))
};

var onSaveClick = function(){
    var _newStatus = $('#status').val();
    if(_newStatus == openedComplaint.status){
        $('#editModal').modal('toggle');
        return;
    } else {
        openedComplaint.status = _newStatus;
    }

    $.ajax({
        url: 'http://localhost/www/timcomplaint-api/complaint/update.php',
        contentType: "application/json",
        type: 'PUT',
        data: JSON.stringify(openedComplaint),
        success: function(response) {
            initMap();
            $('#editModal').modal('toggle');
            console.log(response);
        }
     });
    
};

var deleteComplaint = function(el,ev){
    var _complaintId = $('#deleteModal').attr('data-complaintId');
    $.post(
        "http://localhost/www/timcomplaint-api/complaint/delete.php",
        { 
            id: _complaintId
        },
        function(data){
            initMap();
        }
    );
};

