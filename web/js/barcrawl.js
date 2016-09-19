var drawMySVG = function(selector, data) {
	var bounds = findBounds(data);
	data =normalizeData(data, 100, 100);

	var clamp = function(a, min, max) {
		return Math.max(Math.min(a, max), min);
	}

	var lineFunction = d3.line()
		.x(function(d) { return d.x; })
		.y(function(d) { return d.y; })
	d3.interpolate(lineFunction, "linear");

	d3.select(selector).select('svg').remove();

	var svgObj = d3.select(selector).append("svg")
		.attr("width", "800")
		.attr("height", "400")
		//.attr("height", "
		//.attr("viewBox", [0, 0, bounds.w + 20, bounds.h + 20].join(' '))
		.attr("viewBox", "0 0 400 200")
		.attr("preserveAspectRatio", "xMidYMid meet");

	var gen = zeroToOneIncrementalGenerator();
	//var gen = countingGenerator();


	var piecewise = function(a, b) {
		var c = color(gen());
		if(a && b) {
			svgObj.append("path")
				.attr("d", lineFunction([a, b]))
				.attr("stroke", c)
				.attr("stroke-width", 1)
				.attr("fill", "none");
			svgObj.append("circle")
				.attr("cx", a.x)
				.attr("cy", a.y)
				.attr("r", 2)
				.attr("stroke", c)
				.attr("fill", c)
				.attr("fill-opacity", 0.2);
			svgObj.append("text")
				.attr("x", a.x)
				.attr("y", a.y)
				.attr("font-size", "10px")
				.text(a.name);
		} else {
			svgObj.append("circle")
				.attr("cx", a.x)
				.attr("cy", a.y)
				.attr("r", 2)
				.attr("stroke", "red")
				.attr("fill", "red")
				.attr("fill-opacity", 0.2);
			svgObj.append("text")
				.attr("x", a.x)
				.attr("y", a.y)
				.attr("font-size", "10px")
				.text(a.name);
		}
	}


	function delayedLoop(data, i, delay_ms) {
		if(i < data.length-2) {
			piecewise(data[i], data[i+1]);
			setTimeout(function() {delayedLoop(data, i+1, delay_ms)}, delay_ms)
		} else {
			piecewise(data[i], data[i+1]);
		}
	}

	delayedLoop(data, 0, 500);


/*

	data.forEach(function(item, index) {
		if(index < data.length-2) {
			//take the data points in pairs, 1+2, 2+3, 3+4, etc.
			piecewise(data[index], data[index+1]);
		}
	});
	*/

};

var findBounds = function(d) {
	var max_x = null;
	var max_y = null;
	var min_x = null;
	var min_y = null;

	d.forEach(function(item, index) {
		if(max_x == null || max_x < item.x) {
			max_x = item.x;
		}
		if(max_y == null || max_y < item.y) {
			max_y = item.y;
		}
		if(min_x == null || min_x > item.x) {
			min_x = item.x;
		}
		if(min_y == null || min_y > item.y) {
			min_y = item.y;
		}
	});

	return {
		'max_x': max_x,
		'max_y': max_y,
		'min_x': min_x,
		'min_y': min_y,
		'w': (max_x - min_x),
		'h': (max_y - min_y)
	};
}

var normalizeData = function(d, w, h) {
	bounds = findBounds(d);
	var scaleX = (w * bounds.w);
	var scaleY = (h / bounds.h);
	//var minScale = Math.min(scaleX, scaleY);

	d.forEach(function(item, index) {
		item.x = item.x * scaleX;
		//item.x = item.x * minScale;

		item.y = item.y * scaleY;
		//item.y = item.y * minScale;
	});

	var bounds = findBounds(d);
	var translateX = (bounds.min_x < 0)?-bounds.min_x:0;
	var translateY = (bounds.min_y < 0)?-bounds.min_y:0;


	d.forEach(function(item, index) {
		item.x += translateX

		item.y += translateY;
	});

	return d;
}

var color = function(t) {
	return d3.interpolateRainbow(t);
	//return d3.interpolateRgb(t);
	/*
	var value = 240 - (240*t);
	return 'rgb(' + [100+value, value/2, value/2].join(',') + ')';
	*/
}


var countingGenerator = function() {
	var i=0;
	return function() {
		i++;
		return i;
	};
}

var zeroToOneIncrementalGenerator = function() {
	var i=0.0;
	return function() { i += 0.2+ Math.random();
		if(i > 1.0) {
			return 1.0;
		} else {
			return i;
		}
	}
}

var fakeSVGData = function() {
	return [
		{ "x": 1, "y": 1 },
		{ "x": 2, "y": 2 },
		{ "x": 1, "y": 3 },
		{ "x": 0, "y": 2 },
		{ "x": 1, "y": 1 }
	];
}

var getSVGData = function(url) {
	return d3.json(url, function(json) {
		return barsToCoordList(json);
	});
}

var barsToCoordList = function(b) {
	var list = [];
	for(var p in b) {
		if(b.hasOwnProperty(p)) {
			list.push(b[p]);
		}
	}
	return list;
}

var drawDynamicSVGData = function(selector, url) {
	return d3.json(url, function(json) {
		drawMySVG(selector, barsToCoordList(json));
	});
}

//borrowed from http://techslides.com/demos/findme.html
function getLocation(){
	if (typeof(navigator) !== 'undefined' &&
		navigator.geolocation
	) {
		//remember this is async behavior, the browser does not have access to your locations right away but it does not want to stop executing code
		navigator.geolocation.getCurrentPosition(getAddress);
	} else{
		alert("Geolocation is not supported by this browser.");
	}
}

//borrowed from http://techslides.com/demos/findme.html with modification (callback)
function getAddress(position, callback){

	var lat = position.coords.latitude;
	var lon = position.coords.longitude;

	//grab address via Google API using your position
	var apiurl = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='+lat+','+lon+'&sensor=true';

	//Google does not allow Cross Domain Access so let's use a Proxy: http://benalman.com/projects/php-simple-proxy/
	var url = 'ba-simple-proxy.php?url='+encodeURIComponent(apiurl);

	//make the Ajax request
	var xhr = new XMLHttpRequest();

	xhr.open("GET", url);
	xhr.onload = function() {

		//if we make a successful request and it returns an address
		if(this.status==200 && JSON.parse(xhr.responseText).contents.results.length > 0){
			//get formatted address from https://developers.google.com/maps/documentation/geocoding/#ReverseGeocoding
			var result = JSON.parse(xhr.responseText).contents.results[0].formatted_address;
			callback(result)
		} else {
			//send some general error
			alert("Could not find your location.");
			callback("Error: could not find your location.");
		}

	}

	xhr.send();

}

function addZipFormValidator(selector) {
	var el = d3.select(selector);

	//if(el.
}

function submitZipForm() {
	var zip = d3.select('#zip').property('value');

	drawDynamicSVGData ('#barCrawl', '/realbars?zip=' + zip);
	//var data = getSVGData('/realbars?zip=' + zip);
	//drawMySVG('#barCrawl', data);
}

function addZipValidator(selector) {
	var el = d3.select(selector);

	el.on('change keyup', function(e) {
		this.value = this.value.replace(/[^0-9]/g, '');
		if(this.value.length > 5) {
			this.value = this.value.substr(0,5);
		}
	});

	var z = el.value;
}

//shim just for cmdline testing
if(typeof(alert) == 'undefined') {
	var alert = print;
}
