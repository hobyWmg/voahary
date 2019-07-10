function defineMarkers(averagePrice, priceForDiesel) {
    var averageDieselPrice = averagePrice[0].avg_diesel;
    var result             = ((Number(priceForDiesel) - Number(averageDieselPrice))/Number(averageDieselPrice))*100;
    var imgAssetMarker = "";

    if (priceForDiesel === null || priceForDiesel === "" || priceForDiesel === "0") {
        imgAssetMarker = "/bundles/app/img/marker0.png";
    }
    else if (-6 > result) {
        imgAssetMarker = "/bundles/app/img/marker5.png";
    }
    else if ((-3 > result && result > -6)) {
        imgAssetMarker = "/bundles/app/img/marker4.png";
    }
    else if ((0 > result && result > -3)) {
        imgAssetMarker = "/bundles/app/img/marker3.png";
    }
    else if ((3 > result && result  > 0)) {
        imgAssetMarker = "/bundles/app/img/marker2.png";
    }
    else if (result > 3) {
        imgAssetMarker = "/bundles/app/img/marker1.png";
    }

    return imgAssetMarker;
}

function createMapPriceFromDatas(data) {
    var map2 = new google.maps.Map(document.getElementById('map2'), {
        zoom: 12,
        center: {lat: 48.860294, lng: 2.338629},
        mapTypeControlOptions: {
            mapTypeIds: [
                'prices',
                'roadmap'
            ]
        }
    });

    var infoWindow  = new google.maps.InfoWindow;
    var prices      = new google.maps.StyledMapType(
        [
            {
                "featureType": "administrative.locality",
                "stylers": [
                    {
                        "visibility": "simplified"
                    }
                ]
            },
            {
                "featureType": "administrative.neighborhood",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "landscape",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "poi",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#80cccc"
                    },
                    {
                        "hue": "#0cbfc1"
                    },
                    {
                        "visibility": "on"
                    }
                ]
            },
            {
                "featureType": "road",
                "stylers": [
                    {
                        "visibility": "on"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [
                    {
                        "lightness": 100
                    },
                    {
                        "visibility": "simplified"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "labels",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "stylers": [
                    {
                        "visibility": "simplified"
                    },
                    {
                        "weight": 1.5
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "labels",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "stylers": [
                    {
                        "visibility": "simplified"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road.local",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "transit",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "transit.line",
                "elementType": "geometry",
                "stylers": [
                    {
                        "lightness": 100
                    },
                    {
                        "visibility": "on"
                    }
                ]
            },
            {
                "featureType": "water",
                "stylers": [
                    {
                        "color": "#7dcdcd"
                    }
                ]
            }
        ],
        {name: 'Prices'});

    map2.mapTypes.set('prices', prices);
    map2.setMapTypeId('prices');


    Array.prototype.forEach.call(data[0], function (fuelStation) {
        var markerImage = defineMarkers(data[1], fuelStation.diesel);
        var address    = fuelStation.address;
        var point      = new google.maps.LatLng(
            fuelStation.latitude,
            fuelStation.longitude
        );
        var infowincontent = document.createElement('div');
        var strong     = document.createElement('text');
        strong.textContent = address.toUpperCase();
        infowincontent.appendChild(strong);
        infowincontent.appendChild(document.createElement('br'));
        infowincontent.appendChild(document.createElement('br'));

        var diesel = document.createElement('strong');
        if (fuelStation.diesel === null) {
            diesel.textContent = 'Diesel : - ';
        }
        else {
            diesel.textContent = 'Diesel :' + fuelStation.diesel + '€/L ';
        }
        infowincontent.appendChild(diesel);
        infowincontent.appendChild(document.createElement('br'));

        var unleaded95 = document.createElement('strong');
        if (fuelStation.unleaded95 === null) {
            unleaded95.textContent = 'Unleaded 95 : - ';
        }
        else {
            unleaded95.textContent = 'Unleaded 95 :' + fuelStation.unleaded95 + '€/L ';
        }
        infowincontent.appendChild(unleaded95);
        infowincontent.appendChild(document.createElement('br'));

        var unleaded98 = document.createElement('strong');
        if (fuelStation.unleaded98 === null) {
            unleaded98.textContent = 'Unleaded 98  : - ';
        }
        else {
            unleaded98.textContent = 'Unleaded 98  :' + fuelStation.unleaded98 + '€/L ';
        }
        infowincontent.appendChild(unleaded98);
        infowincontent.appendChild(document.createElement('br'));

        var marker = new google.maps.Marker({
            map: map2,
            position: point,
            icon: markerImage
        });
        marker.addListener('click', function () {
            infoWindow.setContent(infowincontent);
            infoWindow.open(map2, marker);
        });
    });
}

function initMapTraffic() {
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: {lat: 48.860294, lng: 2.338629}
    });

    var trafficLayer = new google.maps.TrafficLayer();
    trafficLayer.setMap(map);
    setInterval(function() {
            map.setOptions({
                styles: [{
                    "featureType": "road",
                    "elementType": "geometry.fill",
                    "stylers": [{
                        "saturation": Math.random()
                    }]
                }]
            })
        },
        60000
    );
}

function insertAvgPricesInDiv(avgprices) {
    var avgDiesel  = avgprices[0].avg_diesel;
    var avg95      = avgprices[0].avg_95;
    var avg98      = avgprices[0].avg_98;
    $('#display-avg-diesel').text(avgDiesel.substring(0, avgDiesel.indexOf('.') + 4) + ' €/L');
    $('#display-avg-95').text(avg95.substring(0, avg95.indexOf('.') + 4) + ' €/L');
    $('#display-avg-98').text(avg98.substring(0, avg98.indexOf('.') + 4) + ' €/L');
}

function initMapPrices(dateSelected, displayMarkers) {
    var url = Routing.generate('retrievePriceOfTheDayData');
    $.ajax({
        url: url,
        method: "post",
        data: {
            "date": dateSelected,
            "displayMarkers" : displayMarkers
        }
    }).done(function (data) {
        insertAvgPricesInDiv(data[1]);
        createMapPriceFromDatas(data);
    });
}

function loadMaps() {
    var date    = moment().format('YYYY-MM-DD');
    var displayMarkers = "all";
    initMapTraffic();
    initMapPrices(date, displayMarkers);
}

$(function() {
    $('input[name="dateForPrices"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            minDate: moment("2017-06-20"),
            maxDate: moment()
        },
        function (start) {
            initMapPrices(start.format("YYYY-MM-DD"), "all");
        })
});

$('#filter-all-diesel').click(function () {
    var date = $("input[name=dateForPrices]").val();
    var displayMarkers = "all";
    date = date.replace(/\//g, "-");
    dateFormatted = date.substr(6,4) + "-" + date.substr(0,5);
    initMapPrices(dateFormatted, displayMarkers);
});

$('#filter-cheap-diesel').click(function () {
    var date = $("input[name=dateForPrices]").val();
    var displayMarkers = "cheap";
    date = date.replace(/\//g, "-");
    dateFormatted = date.substr(6,4) + "-" + date.substr(0,5);
    initMapPrices(dateFormatted, displayMarkers);
});