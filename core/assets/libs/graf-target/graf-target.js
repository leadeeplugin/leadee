var indicatorLine = document.getElementById("indicator-line");
var mainLine = document.getElementById("main-line");
var image = document.getElementById("leadee-graf-emotion");
var num = document.getElementById("target-progress-num");
var smallIndicatorLine = "M 20 250 C 35 25 375 25 390 250";
var verySmallIndicatorLine = "M 20 120 C 30 -22 240 -22 250 120";
var bigIndicatorLine = "M 20 250 C 40 -56 500 -56 520 250";
var percentNow = 0;
var indicatorSize = bigIndicatorLine;

const circles = document.getElementsByClassName("circle");
let a;

if (window.innerWidth > 1800) {
    R = 160;
    upperX = 205;
    upperY = 270;
    changeIndicatorSize(smallIndicatorLine);
} else {
    R = 103;
    upperX = 135;
    upperY = 130;
    changeIndicatorSize(verySmallIndicatorLine);
}


for (let i = 0; i < circles.length; i++) {
    a = (180 / circles.length) * (i) + 3.5;
    cx = Math.cos(Math.PI * a / 180) * (R) + upperX;
    cy = Math.sin(Math.PI * a / 180) * -(R) + upperY;
    circles[i].style.cx = cx;
    circles[i].style.cy = cy;
}
indicatorLine.style.strokeDasharray = indicatorLine.getTotalLength();
indicatorLine.style.strokeDashoffset = indicatorLine.getTotalLength() * ((100 - percentNow) / 100);


window.onresize = (e) => {
    const circles = document.getElementsByClassName("circle");
    let a;
    if (window.innerWidth > 1800) {
        R = 160;
        upperX = 205;
        upperY = 270;
        changeIndicatorSize(smallIndicatorLine);
    } else {
        R = 103;
        upperX = 135;
        upperY = 130;
        changeIndicatorSize(verySmallIndicatorLine);
    }
    for (let i = 0; i < circles.length; i++) {
        a = (180 / circles.length) * (i) + 3.5;
        cx = Math.cos(Math.PI * a / 180) * (R) + upperX;
        cy = Math.sin(Math.PI * a / 180) * -(R) + upperY;
        circles[i].style.cx = cx;
        circles[i].style.cy = cy;
    }
    indicatorLine.style.strokeDasharray = indicatorLine.getTotalLength();
    indicatorLine.style.strokeDashoffset = indicatorLine.getTotalLength() * ((100 - percentNow) / 100);
}

function changeIndicatorSize(size){
    const nodeFirst = document.createAttribute("d");
    nodeFirst.value = size;
    indicatorLine.attributes.setNamedItem(nodeFirst);
    const nodeSecond = document.createAttribute("d");
    nodeSecond.value = size;
    mainLine.attributes.setNamedItem(nodeSecond);
}

function changeProgress(percent) {
    let indicColor = "";
    percentNow = percent;
    const colorDots = Math.floor(20 * (100 - percent) / 100);
    const circles = document.getElementsByClassName("circle");
    const length = indicatorLine.getTotalLength();

    const moveNum = length * ((100 - percent) / 100);

    if (percent <= 33) {
        image.src = dataOut.assetsPath + "/libs/graf-target/img/first.png";
        indicColor = "#E65425";

    } else if (percent > 33 && percent <= 66) {
        image.src = dataOut.assetsPath + "/libs/graf-target/img/second.png";
        indicColor = " #FBC343";
    } else if (percent > 66 && percent <= 100) {
        image.src = dataOut.assetsPath + "/libs/graf-target/img/third.png";
        indicColor = "#8AC44B";
    }

    const anim = indicatorLine.animate([{strokeDashoffset: moveNum, stroke: indicColor}], 1000);
    num.style.color = indicColor;
    for (let i = 0; i < 20; i++) {
        if (colorDots <= i) {
            circles[i].style.fill = "#635F6F";
        } else {
            circles[i].style.fill = "#DDDDDD";
        }

    }
    anim.onfinish = (e) => {
        indicatorLine.style.stroke = indicColor;
        indicatorLine.style.strokeDashoffset = moveNum;
    }
}



