var PosX = [];
var PosY = [];
var timestamp = [];

window.onload = function() {

    //start the webgazer tracker
    webgazer.setRegression('ridge') /* currently must set regression and tracker */
        .setTracker('clmtrackr')
        .setGazeListener(function(data, clock) {
          if (data == null) {
              return;
          }
          PosX.push(data.x); //these x coordinates are relative to the viewport
          PosY.push(data.y); //these y coordinates are relative to the viewport
          timestamp.push(clock);
          //console.save(PosX,"/home/daniel/X.txt");
          //console.save(PosY,"/home/daniel/Y.txt");
          //console.save(timestamp,"/home/daniel/timestamps.txt"); //elapsed time in milliseconds since webgazer.begin() was called
        })
        .begin()
        .showPredictionPoints(true); /* shows a square every 100 milliseconds where current prediction is */


    //Set up the webgazer video feedback.
    var setup = function() {

        //Set up the main canvas. The main canvas is used to calibrate the webgazer.
        var canvas = document.getElementById("plotting_canvas");
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        canvas.style.position = 'fixed';
        
    };
    

    function checkIfReady() {
        if (webgazer.isReady()) {
            setup();
        } else {
            setTimeout(checkIfReady, 100);
        }
    }
    setTimeout(checkIfReady,100);
};

window.onbeforeunload = function() {
    //webgazer.end(); //Uncomment if you want to save the data even if you reload the page.
    window.localStorage.clear(); //Comment out if you want to save data across different sessions
}

/**
 * Restart the calibration process by clearing the local storage and reseting the calibration point
 */
function Restart(){
    document.getElementById("Accuracy").innerHTML = "<a>Not yet Calibrated</a>";
    ClearCalibration();
    PopUpInstruction();
}

console.save = function(data, filename){

    if(!data) {
        console.error('Console.save: No data')
        return;
    }

    if(!filename) filename = 'console.json'

    if(typeof data === "object"){
        data = JSON.stringify(data, undefined, 4)
    }

    var blob = new Blob([data], {type: 'text/json'}),
        e    = document.createEvent('MouseEvents'),
        a    = document.createElement('a')

    a.download = filename
    a.href = window.URL.createObjectURL(blob)
    a.dataset.downloadurl =  ['text/json', a.download, a.href].join(':')
    e.initMouseEvent('click', true, false, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null)
    a.dispatchEvent(e)
}

document.onkeyup=function(e){
    if(e.which == 27){
        //Pressionou ESC, aqui vai a função para esta tecla.
        console.save(PosX,"/home/daniel/X.txt");
        console.save(PosY,"/home/daniel/Y.txt");
        console.save(timestamp,"/home/daniel/timestamps.txt"); //elapsed time in milliseconds since webgazer.begin() was called
        webgazer.end();
    }
}
