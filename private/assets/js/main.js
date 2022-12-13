const mailFeild = document.getElementById("userEmail");
const submit = document.getElementById("submitButton");
const loader = document.getElementById("loader");
const msgDiv = document.getElementById('message');
/////////////////////////////////////////////////////////////////////////////////////////////
function validateEmail(input) {//function to validate email
    let validEmail = /([A-Za-z0-9]{1,}[.+-_]{0,1}){1,}[A-Za-z0-9]@[A-Za-z0-9-]{2,}\.[A-Za-z]{2,}/;
    let user_mail = input.value;
    if(user_mail.match(validEmail)){
        if (user_mail.match(validEmail)[0] === user_mail) {
            return true;
        } else {
            return false; 
        }
    }
  }
/////////////////////////////////////////////////////////////////////////////////////////////
let msg;
submit.addEventListener("mouseover",function(){//check if user tries to reach submit button
    if( ! validateEmail(mailFeild)){
        let top = Math.floor(Math.random() * (window.screen.height - 800));
        let left = Math.floor(Math.random() * (window.screen.width - 800));
        submit.style.position = "relative";
        submit.style.top = top + 'px';
        submit.style.left = left + 'px';
        submit.style.border = "2px solid red";
        submit.style.zIndex = "2";
        
        if( typeof msg !== 'undefined'){
            clearTimeout(msg);
            msgDiv.style.animation = 'none';
            msgDiv.offsetHeight;
            msgDiv.style.animation = null;

        }
        msgDiv.innerHTML = 'Please enter valid E-mail address to proceed';
        msgDiv.classList.add('slide-anim')
        
        
        msg = setTimeout(() => {
            msgDiv.innerHTML = '';
            msgDiv.classList.remove('slide-anim')
        }, 2000);
    }
});

mailFeild.addEventListener("keyup",function(){//check entered email address after evry keyup
    if(validateEmail(mailFeild)){
        submit.style.position = "static";
        submit.style.border = "2px solid green";
    }
    else{
        submit.style.border = "2px solid red";
    }
})
/////////////////////////////////////////////////////////////////////////////////////////////
submit.addEventListener("click",function(event){//submiting user's email
    event.preventDefault();
    submit.style.border = "none";
    submit.setAttribute("hidden","");
    loader.removeAttribute("hidden");

    if(validateEmail(mailFeild)){
        let userEmail = mailFeild.value;
        fetch("http://localhost:8080/RTC_v3/public/validator.php",
        {
            method:"POST",
            body: JSON.stringify({
            "user_email" : userEmail,
            "other" : {
                "send_mail" : 1
            }
            }),
            headers:{
                "Content-type":"application/json",
            }
        })
        .then((response)=> {return response.json()})
        .then((data)=>{
            alert(data['message']);

            document.getElementById("form").reset();

            if (data['status']) {
                loader.classList.add('done')
                setTimeout(() => {
                    loader.setAttribute("hidden","");
                    loader.classList.remove('done');
                    submit.removeAttribute("hidden");
                }, 3500);
            }
        }).catch((err)=>console.log(err))
    }
});
/////////////////////////////////////////////////////////////////////////////////////////////
document.getElementById('Centre').scrollIntoView()// asthetic purpose
/////////////////////////////////////////////////////////////////////////////////////////////
const slilder = setInterval(()=>{// moves slider to left and reset immediately
    const imgDivs = document.querySelectorAll('.selected-img');
    imgDivs.forEach(div=>{
        div.classList.add('move');
    })
}, 1000);

const correction = setInterval(()=>{// correct the immediate reset effect by shifting image
    const imgDivs = document.querySelectorAll('.move');
    imgDivs.forEach(div=>{
        if (div.classList.contains('s1')) {
            div.classList.add('s2');
            div.firstChild.innerHTML = 'Grownups'
            div.classList.remove('s1');
        }else if(div.classList.contains('s2')) {
            div.classList.add('s3');
            div.firstChild.innerHTML = 'Angular Momentum'
            div.classList.remove('s2');
        }else if(div.classList.contains('s3')) {
            div.classList.add('s4');
            div.firstChild.innerHTML = 'Circuit Diagram'
            div.classList.remove('s3');
        }else if(div.classList.contains('s4')) {
            div.classList.add('s5');
            div.firstChild.innerHTML = 'Alternative Energy Revolution'
            div.classList.remove('s4');
        }else if(div.classList.contains('s5')) {
            div.classList.add('s1');
            div.firstChild.innerHTML = 'self description'
            div.classList.remove('s5');
        }
        div.classList.remove('move');
    })

}, 2000);
/////////////////////////////////////////////////////////////////////////////////////////////