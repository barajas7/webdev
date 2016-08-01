/**
 * Created by Arturo Barajas on 4/8/2016.
 */
function hangman() {

    // The current word as letters are guessed
    var code = "";

    var wrongCount = 0;
    var answer = randomWord();
    var display = "";
    var hidden = ""

    console.log("Word:", answer);

    for (var i=0; i<answer.length; i++) {
        code += "_";
    }

    /*
     * Create the HTML and put it into the div
     */
    var area = document.getElementById("play-area");

    var html = '<p id="image"><img alt="hangman" src="images/hm0.png" height="300" width="125"/></p>' +
        '<p id="code">&nbsp;</p>' +
        '<form>' +
        '<input type ="hidden" id="word" value="' + answer + '">' +
        '<p>Letter: <input type="text" id="letter"></p>' +
        '<p><input id="guess" type="submit" value="Guess!"> ' +
        '<input id="reset" type="submit" value="New Game">' +
        '<p id="result">&nbsp;</p>' +
        '</form>';

    area.innerHTML = html;
    updateDisplay();

    document.getElementById("guess").onclick = function(event) {
        event.preventDefault();

        // If game is already won or lost
        if (wrongCount == 6 || code == answer) {
            return;
        }

        var letter = document.getElementById("letter").value;

        // Clear text box
        document.getElementById("letter").value = "";

        console.log("Letter: ", letter);
        if (letter === "") {
            document.getElementById("result").innerHTML = "You must enter a letter!";
            return;
        }

        var newCode = "";

        for (var i=0; i<answer.length; i++) {
            if (letter == answer[i]) {
                newCode += letter;
            }

            else if (code[i] != "_")
            {
                newCode += code[i];
            }

            else {
                newCode += "_";
            }
        }

        // If no letters changed
        if (newCode == code) {
            wrongCount++;
            updateImage(wrongCount);

            if (wrongCount == 6) {
                document.getElementById("result").innerHTML = "You guessed poorly!";
                code = answer;
            }
        }

        else {
            code = newCode;

            if (code == answer) {
                document.getElementById("result").innerHTML = "You Win!";
            }
        }

        updateDisplay();
    };

    // Restart game if New Game chosen
    document.getElementById("reset").onclick = function(event) {
        console.log("Pressed New Game");

        event.preventDefault();

        wrongCount = 0;
        updateWord();

        code = "";
        for (var i=0; i<answer.length; i++) {
            code += "_";
        }

        updateImage(wrongCount);
        updateDisplay();

        console.log("Word", answer);
    };

    function updateImage(count) {
        display = '<p id="image"><img alt="hangman" src="images/hm' + count.toString() + '.png" height="300" width="125" /></p>';

        document.getElementById("image").innerHTML = display;
    }

    function updateDisplay() {
        display = "";

        for (var i=0; i < code.length; i++) {
            display += code[i] + " ";
        }

        document.getElementById("code").innerHTML = display;
    }

    function updateWord() {

        answer = randomWord();
        document.getElementById("word").value = answer;
    }

    function randomWord() {
        var words = ["moon","home","mega","blue","send","frog","book","hair","late",
            "club","bold","lion","sand","pong","army","baby","baby","bank","bird","bomb","book",
            "boss","bowl","cave","desk","drum","dung","ears","eyes","film","fire","foot","fork",
            "game","gate","girl","hose","junk","maze","meat","milk","mist","nail","navy","ring",
            "rock","roof","room","rope","salt","ship","shop","star","worm","zone","cloud",
            "water","chair","cords","final","uncle","tight","hydro","evily","gamer","juice",
            "table","media","world","magic","crust","toast","adult","album","apple",
            "bible","bible","brain","chair","chief","child","clock","clown","comet","cycle",
            "dress","drill","drink","earth","fruit","horse","knife","mouth","onion","pants",
            "plane","radar","rifle","robot","shoes","slave","snail","solid","spice","spoon",
            "sword","table","teeth","tiger","torch","train","water","woman","money","zebra",
            "pencil","school","hammer","window","banana","softly","bottle","tomato","prison",
            "loudly","guitar","soccer","racket","flying","smooth","purple","hunter","forest",
            "banana","bottle","bridge","button","carpet","carrot","chisel","church","church",
            "circle","circus","circus","coffee","eraser","family","finger","flower","fungus",
            "garden","gloves","grapes","guitar","hammer","insect","liquid","magnet","meteor",
            "needle","pebble","pepper","pillow","planet","pocket","potato","prison","record",
            "rocket","saddle","school","shower","sphere","spiral","square","toilet","tongue",
            "tunnel","vacuum","weapon","window","sausage","blubber","network","walking","musical",
            "penguin","teacher","website","awesome","attatch","zooming","falling","moniter",
            "captain","bonding","shaving","desktop","flipper","monster","comment","element",
            "airport","balloon","bathtub","compass","crystal","diamond","feather","freeway",
            "highway","kitchen","library","monster","perfume","printer","pyramid","rainbow",
            "stomach","torpedo","vampire","vulture"];

        return words[Math.floor(Math.random() * words.length)];
    }
}