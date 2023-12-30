/**
 * @param {string} s
 * @param {string} t
 * @return {boolean}
 */
var isSubsequence = function(s, t) {
    let tarr = t.split("");
    let temp = 0;

    for(let wrd of s){
        for(let i = 0; i < tarr.length; i++){
            console.log( wrd + " == " + t[i] + " from : "+tarr);
            if(wrd == tarr[i]){
                console.log( "True " + wrd + " == " + t[i] + "value of i = " + i);
                temp+=1;
                if(i == 0){
                    tarr.shift();
                }
                else{
                    tarr.splice(0,i);
                }
                
                console.log(tarr);
            }
        }
    };

    if(temp == s.length){
        return true;
    }
    else{
        return false;
    }
    
};

console.log(isSubsequence("bb", "ahbgdc"));