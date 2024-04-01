const request = require('request');
const crypto = require('crypto');
const fs = require('fs');


function generateRandomToken(length) {
    return new Promise((resolve, reject) => {
        crypto.randomBytes(length, (err, buffer) => {
            if (err) {
                reject(err);
            } else {
                resolve(buffer.toString('hex'));
            }
        });
    });
}


function Cookies(callback){
    const email="enter email";
    const password = "enter password"
    let options = {
        'method': 'POST',
        'url': 'https://www.solutioninn.com/sign',
        'headers': {
          'accept': '*/*',
          'accept-language': 'en-US,en;q=0.9,ru;q=0.8,zh-TW;q=0.7,zh;q=0.6',
          'content-type': 'application/x-www-form-urlencoded; charset=UTF-8',
          'dnt': '1',
          'origin': 'https://www.solutioninn.com',
          'referer': 'https://www.solutioninn.com/sign',
          'sec-ch-ua': '"Google Chrome";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
          'sec-ch-ua-mobile': '?0',
          'sec-ch-ua-platform': '"Linux"',
          'sec-fetch-dest': 'empty',
          'sec-fetch-mode': 'cors',
          'sec-fetch-site': 'same-origin',
          'user-agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36',
          'x-requested-with': 'XMLHttpRequest'
        },
        body: `eu=${email}&pw${password}&rm=0`
      
      };
    request(options, (error, response) => {
        if (error) throw new Error(error);
        const cookies = response.headers['set-cookie'];
        const cookiesJSON = {};
        cookies.forEach(cookie => {
        const parts = cookie.split(';')[0].split('=');
        cookiesJSON[parts[0]] = parts[1];
        });
        //console.log(cookiesJSON);
        const zenid = cookiesJSON.zenid;
        const u_id = cookiesJSON.u_id;
        const acc_type = cookiesJSON.acc_type;
        callback([zenid,u_id,acc_type]);
    });
}

function makeRequestWithCookies(cookie) {
    const cookie1 = cookie[0];
    const cookie2 = cookie[1];
    const cookie3 = cookie[2];
    const cookieObject = {'zenid': cookie1, 'u_id': cookie2, 'acc_type': cookie3};
    const options = {
        method: 'GET',
        url: 'https://www.solutioninn.com/study-help/questions/build-this-interface-with-java-and-give-me-the-code-292234',
        headers: {
            accept: 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
            'accept-language': 'en-US,en;q=0.9,ru;q=0.8,zh-TW;q=0.7,zh;q=0.6',
            'cache-control': 'max-age=0',
            'cookie': `zenid=${cookieObject['zenid']}; u_id=${cookieObject['u_id']}; acc_type=${cookieObject['acc_type']}`,
            'dnt': '1',
            'sec-ch-ua': '"Google Chrome";v="123", "Not:A-Brand";v="8", "Chromium";v="123"',
            'sec-ch-ua-mobile': '?0',
            'sec-ch-ua-platform': '"Linux"',
            'sec-fetch-dest': 'document',
            'sec-fetch-mode': 'navigate',
            'sec-fetch-site': 'none',
            'sec-fetch-user': '?1',
            'upgrade-insecure-requests': '1',
            'user-agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Safari/537.36'
        }
    };

    request(options, (error, response) => {
        if (error) throw new Error(error);
        const tokenLength = 16; 
        generateRandomToken(tokenLength)
            .then(token => {
                fs.writeFile(`${token}.html`, response.body, (err) => {
                    if (err) throw err;
                    console.log(`HTML file with response body has been saved as ${token}.html`);
                });
            })
            .catch(err => {
                console.error("Error generating token:", err);
            });
        });
    }   
Cookies(cookie => {
    makeRequestWithCookies(cookie);
});
