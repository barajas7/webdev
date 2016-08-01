function Game(sel) {

    var form = $(sel);
    this.sel = sel;

    console.log("Game constructor");

    var buttonR = $(sel + ' [name="r"]');
    this.buttonListener(buttonR, form);
    var buttonD = $(sel + ' [name="d"]');
    this.buttonListener(buttonD, form);
    var buttonO = $(sel + ' [name="o"]');
    this.buttonListener(buttonO, form);
    var buttonG = $(sel + ' [name="g"]');
    this.buttonListener(buttonG, form);


    var leaks = $(sel + ' [name="leak"]');
    for(var i = 0; i < leaks.length; i++) {

        this.buttonListener($(leaks[i]), form);
    }
}

Game.prototype.buttonListener = function(button, form) {
    var that = this;

    button.click(function(event) {
        event.preventDefault();

        var formData = form.serialize();
        formData += "&" + this.name + "=" + this.value;
        //formData.push({ name: this.name, value: this.value });
        console.log(formData);

        $.ajax({
            url: "post/game-post.php",
            data: formData,
            method: "POST",
            success: function(data){
                var json = parse_json(data);
                if(json.ok) {
                    if(json.reload) {
                        new Reload("body");
                    }
                    console.log(json);
                } else {
                    console.log("Success, fail");
                }
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    });
}



function Reload(sel) {
    var body = $(sel);

    $.ajax({
        url: "post/reload.php",
        data: {ok: true},
        method: "POST",
        success: function(data){
            var json = parse_json(data);
            if(json.ok) {
                // Successfully updated
                body.html(json.table);
                new Game("form");

            } else {
                // Update failed
                console.log("Success, fail")

            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}

function parse_json(json) {
    try {
        var data = $.parseJSON(json);
    } catch(err) {
        throw "JSON parse error: " + json;
    }

    return data;
}