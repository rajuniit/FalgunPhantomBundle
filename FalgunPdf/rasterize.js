var page = require('webpage').create(),
    fs = require('fs'),
    system = require('system'),
    margin = system.args[5] || '0cm',
    orientation = system.args[6] || 'portrait',
    cookie_file = system.args[7] ,
    render_time = system.args[8] || 10000 ,
    time_out = system.args[9] || 90000 ,
    viewport_width = system.args[10] || 600,
    viewport_height= system.args[11] || 600,
    cookies = {},
    address, output, size, statusCode;

window.setTimeout(function () {
    console.log("Shit's being weird no result within: " + time_out + "ms");
    phantom.exit(1);
}, time_out);

try {
    f = fs.open(cookie_file, "r");
    console.log(f.read());
    cookies = JSON.parse(f.read());
    console.log(cookies);
    fs.remove(cookie_file)
} catch (e) {
    console.log(e);
}
phantom.cookiesEnabled = true;
phantom.cookies = cookies;
phantom.addCookie({
    'name':     cookies.name,
    'value':    cookies.value,
    'domain':   cookies.domain,
    'path':     '/',
    'httponly': true,
    'secure':   false,
    'expires':  (new Date()).getTime() + (1000 * 60 * 60)   /* <-- expires in 1 hour */
});
if (system.args.length < 3 || system.args.length > 12) {
    console.log('Usage: rasterize.js URL filename [paperwidth*paperheight|paperformat] [zoom] [margin] [orientation] [cookie_file] [render_time] [time_out] [viewport_width] [viewport_height]');
    console.log('  paper (pdf output) examples: "5in*7.5in", "10cm*20cm", "A4", "Letter"');
    phantom.exit(1);
} else {
    address = system.args[1];
    output = system.args[2];
    page.viewportSize = { width: viewport_width, height: viewport_height };
    if (system.args.length > 3 && system.args[2].substr(-4) === ".pdf") {
        size = system.args[3].split('*');
        page.paperSize = size.length === 2 ? { width:size[0], height:size[1], margin:'0px' }
            : { format:system.args[3], orientation:orientation, margin:margin };
    }
    if (system.args.length > 4) {
        page.zoomFactor = system.args[4];
    }

    // determine the statusCode
    page.onResourceReceived = function (resource) {
        if (resource.url == address) {
            statusCode = resource.status;
        }
    };

    page.open(address, function (status) {
        if (status !== 'success' || (statusCode != 200 && statusCode != null)) {
            console.log(statusCode, 'Unable to load the address!');
            if (fs.exists(output)) {
                fs.remove(output);
            }
            try {
                fs.touch(output);
            }
            catch (e) {
                phantom.exit(1);
                throw e
            }
            phantom.exit(1);
        } else {
            window.setTimeout(function () {
                page.render(output + '_tmp.pdf');

                if (fs.exists(output)) {
                    fs.remove(output);
                }

                try {
                    fs.move(output + '_tmp.pdf', output);
                }
                catch (e) {
                    console.log(e);
                    phantom.exit(1);
                    throw e
                }
                console.log('rendered to: ' + output, new Date().getTime());
                phantom.exit();
            }, render_time);
        }
    });
}