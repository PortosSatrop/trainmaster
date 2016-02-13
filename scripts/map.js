$(document).ready(function ()
{

var image = $('#innermap');
image.mapster({
        fillOpacity: 1,
        fillColor: "FF0000",
        stroke: true,
        strokeColor: "3320FF",
        strokeOpacity: 0.8,
        strokeWidth: 4,
        singleSelect: true,
        mapKey: 'name',
        listKey: 'name',
    
        onClick: function (e) {

            var newToolTip = "defualt";

            // update text depending on area selected
            //$('#selections').html(xref[e.key]);
            
            // if selected, change the tooltip
            if (e.key === 'NH') {
                newToolTip = "OK. I know I have come down on the dip before, but let's be real. ";
            }

            image.mapster('set_options', { 
                areas: [{
                    key: "NH",
                    fillColor: "fff000",
                    toolTip: newToolTip
                    }]
                });
        },

        showToolTip: true,
        toolTipClose: ["tooltip-click", "area-click"],
        areas: [
            {
                key: "NH",
                fillColor: "ffffff"
            }
            ]
});
});

