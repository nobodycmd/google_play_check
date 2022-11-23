/**
 * node.js 环境
 * 
 * npm install google-play-scraper
 */
 var request = require('request');

 var argv = process.argv.slice(2);
 console.log(argv)
 var input_term = argv[0];
 var input_limit = argv[1];
 
 function save_std(data) {
     //var json_string_results = JSON.stringify(data);
    console.log(data);
    request.post('http://gg.lucktp.com/api/v1/package/save-from-nodejs',data,function (error, response, body) {
        console.log(body);
    });
 }
 
 
 function save_err(data) {
     console.log("NodeJS> Error! " + data);
 }
 
 
 try {
     var gplay = require('google-play-scraper');
 }  catch(e) {
     console.error(e.message);
     console.error("google-play-scraper is probably not found. Try running `npm i google-play-scraper`.");
     process.exit(e.code);
 }
 
 gplay.search({
     term: input_term,
     num: input_limit,
    //  country:'us',
 }).then(save_std, save_err);
 
 
 