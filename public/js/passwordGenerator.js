const passwordGenerate = document.getElementById
("generate-password"); 
const passwordValue = document.getElementById
("password");
const upClose = document.getElementById
("upClose");
const downClose = document.getElementById
("downClose");

const min = 100000;
const max = 999999;
let randomPass;

passwordGenerate.onclick = function(){
    randomPass = Math.floor(Math.random() * (max-min)) + min;
    $(passwordValue).val(randomPass);
}

upClose.onclick = function(){
    $('#firstName').val("");
    $('#lastName').val("");
    $('#userType').val("");
    $('#email').val("");
    $(passwordValue).val("");
}

downClose.onclick = function(){
    $('#firstName').val("");
    $('#lastName').val("");
    $('#userType').val("");
    $('#email').val("");
    $(passwordValue).val("");
}