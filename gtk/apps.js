#!/usr/bin/gjs

const Gtk    = imports.gi.Gtk;
const WebKit = imports.gi.WebKit;
const Uri    = "http://localhost/appfinder/html";

var web = {

    init: function() {
        Gtk.init(null);
        let win    = new Gtk.Window(),
            sw     = new Gtk.ScrolledWindow({}),
            webkit = new WebKit.WebView();
        win.title = "Apps";
        win.set_size_request(640, 480);
        win.add(sw);
        win.connect("delete-event", Gtk.main_quit);
        sw.add(webkit);
        webkit.load_uri(Uri);
        webkit.connect("title-changed", web.title_changed);
        //webkit.connect("load-finished", win.show_all);
        win.show_all();
        Gtk.main();
    },

    title_changed: function (widget, frame, title) {
        if(title!="Apps") eval(title);
    },

    call: function (func, args, callback) {
        //let script = func + "(" + JSON.stringify(args) + ", " + callback + ");");
        var script="alert('a');";
        webkit.execute_script(script);
    }

}

web.init();

