
var fs = require('fs');
var http = require('http');
var BlueButton = require('bluebutton');

var args = process.argv[2];
var xml = fs.readFileSync(args, 'utf-8');
var myRecord = BlueButton(xml);

//Log the demographics data
//myRecord.data.document.title += " pankaj" ;
//var template = fs.readFileSync('./node_modules/bluebutton/build/ccda_template.ejs', 'utf-8');
var json =myRecord.data.json();
console.log(json);
