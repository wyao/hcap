var feature;

var projection = d3.geo.azimuthal()
    .scale(300)
    .origin([90,40])
    .mode("orthographic")
    .translate([450, 400]);
    //TODO: Replace with browser width and perform refresh. Alternatively float the div.

var circle = d3.geo.greatCircle()
    .origin(projection.origin());

// TODO fix d3.geo.azimuthal to be consistent with scale
var scale = {
  orthographic: 380,
  stereographic: 380,
  gnomonic: 380,
  equidistant: 380 / Math.PI * 2,
  equalarea: 380 / Math.SQRT2
};

var path = d3.geo.path()
    .projection(projection);

var svg = d3.select("#map").append("svg:svg")
    .on("mousedown", mousedown);

d3.json("js/world-countries.json", function(collection) {
  feature = svg.selectAll("path")
      .data(collection.features)
    .enter().append("svg:path")
      .attr("d", clip).attr("id", function(d) {return d.properties.name.replace(/\s/g,'');});

  feature.append("svg:title")
      .text(function(d) {return d.properties.name; });
});

d3.select(window)
    .on("mousemove", mousemove)
    .on("mouseup", mouseup);

var m0,
    o0;

function mousedown() {
  m0 = [d3.event.pageX, d3.event.pageY];
  o0 = projection.origin();
  d3.event.preventDefault();
}

function mousemove() {
  if (m0) {
    var m1 = [d3.event.pageX, d3.event.pageY],
        o1 = [o0[0] + (m0[0] - m1[0]) / 8, o0[1] + (m1[1] - m0[1]) / 8];
    projection.origin(o1);
    circle.origin(o1)
    refresh();
  }
}

function mouseup() {
  if (m0) {
    mousemove();
    m0 = null;
  }
}

function refresh(duration) {
  (duration ? feature.transition().duration(duration) : feature).attr("d", clip);
}

function clip(d) {
  return path(circle.clip(d));
}