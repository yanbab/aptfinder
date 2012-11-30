/**
 * Communication with gtk shell
 *
 */



var gtk = {

    call: function (func, args, callback) {
        var script = func + "(" + JSON.stringify(args) + ", " + callback + ");";
        gtk.execute_script(script);
    },
    
    execute_script: function(script) {
        document.title = "null";
        document.title = script;
        console.log("Exec: " + script);
    }

}


