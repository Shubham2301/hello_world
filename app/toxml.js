var fs = require('fs');
var http = require('http');
var BlueButton = require('bluebutton');
var args = process.argv[2];
var json = fs.readFileSync(args, 'utf-8');

var template = fs.readFileSync('../node_modules/bluebutton/build/ccda_template.ejs', 'utf-8');
var myccd = BlueButton(json, {
  generatorType: 'ccda',
  template: template
});

console.log(myccd.data);
